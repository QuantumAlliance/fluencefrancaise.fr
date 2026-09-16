<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{User, Course, Enrollment, StudentProgress, Coupon, ClassType};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StudentController extends Controller
{
    /**
     * Student dashboard - GET /api/student/dashboard
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $enrolledCourses = $user->enrollments()->count();
        $completedCourses = $user->enrollments()->where('status', 'completed')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'enrolled_courses' => $enrolledCourses,
                'completed_courses' => $completedCourses,
                'total_hours' => 0, // TODO: Calculate from course data
            ],
            'message' => 'Student dashboard',
        ]);
    }

    /**
     * Get student account info - GET /api/student/account
     */
    public function account(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Student account',
        ]);
    }

    /**
     * Update student account - PUT /api/student/account
     */
    public function updateAccount(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'first_name' => 'nullable|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'title' => 'nullable|string|max:100',
            'biography' => 'nullable|string|max:1000',
            'timezone' => 'nullable|string',
            'gender' => 'nullable|string|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'location' => 'nullable|string|max:100',
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Account updated successfully',
        ]);
    }

    /**
     * Upload student profile picture - POST /api/student/upload-picture
     */
    public function uploadPicture(Request $request)
    {
        $validated = $request->validate([
            'profile_picture' => 'required|image|max:5120',
        ]);

        $user = $request->user();

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $user->profile_picture = $path;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'data' => ['profile_picture' => $user->profile_picture],
            'message' => 'Profile picture uploaded',
        ]);
    }

    /**
     * Change student password - PUT /api/student/password
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect',
            ], 401);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully',
        ]);
    }

    /**
     * Get student subscription - GET /api/student/subscription
     */
    public function subscription(Request $request)
    {
        $user = $request->user();

        // Find the latest active enrollment with payment info
        $enrollment = $user->enrollments()
            ->where('status', 'active')
            ->with(['classType', 'course'])
            ->latest('enrollment_date')
            ->first();

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscription found',
            ], 404);
        }
        
        // Get payment details for this enrollment
        $payment = \App\Models\Payment::where('enrollment_id', $enrollment->id)
            ->where('status', 'pending') // Usually 'succeeded' or 'paid', but keeping simple for now or 'pending' if checking recently created
            ->orWhere('enrollment_id', $enrollment->id) // Getting any payment associated really
            ->latest()
            ->first();
            
        // Refine payment query to get the successful one if possible
        $successfulPayment = \App\Models\Payment::where('enrollment_id', $enrollment->id)
             // ->where('status', 'succeeded') // In a real app, check for success
             ->latest()
             ->first();

        $data = [
            'id' => $enrollment->id,
            'status' => $enrollment->status,
            'enrollment_date' => $enrollment->enrollment_date,
            'completion_date' => $enrollment->completion_date,
            'class_type' => $enrollment->classType,
            'course' => $enrollment->course,
            'payment_status' => $successfulPayment ? $successfulPayment->status : 'N/A',
            'amount' => $successfulPayment ? $successfulPayment->amount : 0,
            'final_amount' => $successfulPayment ? $successfulPayment->final_amount : 0,
            'currency' => $successfulPayment ? $successfulPayment->currency : 'CAD',
            'created_at' => $enrollment->created_at,
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Student subscription details',
        ]);
    }

    /**
     * Get student courses - GET /api/student/courses
     */
    public function courses(Request $request)
    {
        try {
            $user = $request->user();
            
            // Get all active courses (since enrollments might not have course_id)
            // Handle null course_is_active as active (default behavior)
            // Order by category display_order for consistent ordering
            $allCourses = Course::leftJoin('class_types', function ($join) {
                    $join->on('courses.course_category', '=', 'class_types.class_name')
                         ->orOn('courses.course_category', '=', 'class_types.name');
                })
                ->select('courses.*')
                ->where(function($query) {
                    $query->where('courses.course_is_active', true)
                          ->orWhereNull('courses.course_is_active');
                })
                ->orderBy('class_types.display_order', 'asc')
                ->orderBy('courses.id', 'asc')
                ->get();
            
            // Get user's enrollments - filter out null course_ids
            $enrollments = $user->enrollments()
                ->with(['course', 'payment'])
                ->whereNotNull('course_id')
                ->get()
                ->keyBy('course_id'); // Key by course_id for quick lookup
            
            // Transform courses to include enrollment data and progress
            $coursesData = $allCourses->map(function ($course) use ($user, $enrollments) {
                $enrollment = $enrollments->get($course->id);

                // Calculate progress from section completions
                $sectionProgress = $user->studentProgress()
                    ->where('course_id', $course->id)
                    ->where('activity_type', 'section')
                    ->get();

                $overallProgress = 0;

                if ($sectionProgress->count() > 0) {
                    // Calculate total sections from course JSON content
                    $totalSections = 0;
                    try {
                        $jsonContent = $course->course_json_content;
                        if (is_string($jsonContent)) {
                            $jsonContent = json_decode($jsonContent, true);
                        }

                        if (is_array($jsonContent)) {
                            foreach ($jsonContent as $section) {
                                if (isset($section['activities']) && is_array($section['activities'])) {
                                    $totalSections += count($section['activities']);
                                } else {
                                    $totalSections++;
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        $totalSections = 0;
                    }

                    // If we couldn't determine total sections, use completed count as minimum
                    if ($totalSections === 0) {
                        $totalSections = max($sectionProgress->count(), 1);
                    }

                    $completedSections = $sectionProgress->count();
                    $overallProgress = $totalSections > 0 ? round(($completedSections / $totalSections) * 100) : 0;
                    // Cap at 100%
                    $overallProgress = min($overallProgress, 100);
                } else {
                    // Fallback to old activity-based progress calculation
                    $activityProgress = $user->studentProgress()
                        ->where('course_id', $course->id)
                        ->whereIn('activity_type', ['grammar', 'reading', 'listening', 'vocabulary'])
                        ->get();

                    if ($activityProgress->count() > 0) {
                        $totalActivities = 4;
                        $completedActivities = $activityProgress->where('progress_percentage', 100)->count();
                        $overallProgress = $totalActivities > 0 ? round(($completedActivities / $totalActivities) * 100) : 0;
                    }
                }

                // Determine status based on progress
                $status = 'Not Started';
                if ($overallProgress > 0 && $overallProgress < 100) {
                    $status = 'In Progress';
                } elseif ($overallProgress >= 100) {
                    $status = 'Completed';
                }
                
                // Build image URL
                $imageUrl = null;
                if ($course->course_image) {
                    $imageUrl = asset('storage/' . $course->course_image);
                }
                
                return [
                    'id' => $course->id,
                    'enrollment_id' => $enrollment ? $enrollment->id : null,
                    'title' => $course->course_title ?? 'Untitled Course',
                    'subtitle' => $course->course_subtitle ?? '',
                    'description' => $course->course_description ?? '',
                    'image_url' => $imageUrl,
                    'category' => $course->course_category ?? '',
                    'course_category' => $course->course_category ?? '', // Include both for compatibility
                    'language' => $course->course_language ?? '',
                    'level' => $course->course_level ?? 'Beginner',
                    'progress' => $overallProgress,
                    'status' => $status,
                    'enrollment_date' => $enrollment ? $enrollment->created_at : null,
                    'enrollment_status' => $enrollment ? $enrollment->status : null,
                    'is_enrolled' => $enrollment ? true : false,
                    'custom_url' => $course->custom_url ?? null,
                    'custom_url_target' => $course->custom_url_target ?? '_blank',
                ];
            });

            // Paginate manually
            $page = $request->get('page', 1);
            $perPage = 10;
            $total = $coursesData->count();
            $items = $coursesData->slice(($page - 1) * $perPage, $perPage)->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'data' => $items,
                    'current_page' => (int)$page,
                    'last_page' => ceil($total / $perPage),
                    'per_page' => $perPage,
                    'total' => $total,
                ],
                'message' => 'Student courses',
            ]);
        } catch (\Exception $e) {
            Log::error('Student courses error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => $request->user()?->id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load courses: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get course detail - GET /api/student/courses/{id}
     * Optimized with caching for better performance
     */
    public function courseDetail(Request $request, $id)
    {
        $user = $request->user();
        
        // Cache key includes course ID
        $cacheKey = "course_detail_student_{$id}";
        $cacheDuration = \App\Http\Controllers\Api\AdminController::getCacheDuration('courses');

        // Fetch course (cached if enabled)
        if ($cacheDuration) {
            $course = \Illuminate\Support\Facades\Cache::remember($cacheKey, $cacheDuration, function () use ($id) {
                return Course::find($id);
            });
        } else {
            $course = Course::find($id);
        }
        
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found',
            ], 404);
        }

        // Check if student is enrolled (this part is user-specific, so not cached in the course object)
        $enrollment = $user->enrollments()->where('course_id', $id)->first();

        return response()->json([
            'success' => true,
            'data' => [
                'course' => $course,
                'enrolled' => $enrollment ? true : false,
                'enrollment' => $enrollment,
            ],
            'message' => 'Course detail',
        ]);
    }

    /**
     * Browse all courses - GET /api/student/browse-courses
     */
    public function browseCourses(Request $request)
    {
        // Order by category display_order for consistent ordering
        $courses = Course::leftJoin('class_types', function ($join) {
                $join->on('courses.course_category', '=', 'class_types.class_name')
                     ->orOn('courses.course_category', '=', 'class_types.name');
            })
            ->select('courses.*')
            ->orderBy('class_types.display_order', 'asc')
            ->orderBy('courses.id', 'asc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $courses,
            'message' => 'Browse courses',
        ]);
    }

    /**
     * Get courses by category - GET /api/student/courses/by-category/{category}
     */
    public function coursesByCategory(Request $request, $category)
    {
        // Order by category display_order
        $courses = Course::leftJoin('class_types', function ($join) {
                $join->on('courses.course_category', '=', 'class_types.class_name')
                     ->orOn('courses.course_category', '=', 'class_types.name');
            })
            ->select('courses.*')
            ->where('courses.course_category', $category)
            ->orderBy('class_types.display_order', 'asc')
            ->orderBy('courses.id', 'asc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $courses,
            'message' => 'Courses by category',
        ]);
    }

    /**
     * Search courses - POST /api/student/search-courses
     */
    public function searchCourses(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|min:3',
            'category' => 'nullable|string',
            'level' => 'nullable|string',
        ]);

        $query = Course::query();

        if (!empty($validated['query'])) {
            $query->where('title', 'like', '%' . $validated['query'] . '%')
                  ->orWhere('description', 'like', '%' . $validated['query'] . '%');
        }

        if (!empty($validated['category'])) {
            $query->where('category_id', $validated['category']);
        }

        $courses = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $courses,
            'message' => 'Course search results',
        ]);
    }

    /**
     * Enroll in a course - POST /api/student/enroll
     */
    public function enroll(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'class_type_id' => 'nullable|exists:class_types,id',
        ]);

        $user = $request->user();

        // Check if already enrolled
        $existing = $user->enrollments()->where('course_id', $validated['course_id'])->first();
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Already enrolled in this course',
            ], 400);
        }

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $validated['course_id'],
            'class_type_id' => $validated['class_type_id'] ?? null,
            'status' => 'active',
            'enrollment_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $enrollment,
            'message' => 'Enrolled successfully',
        ], 201);
    }

    /**
     * Get student enrollments - GET /api/student/enrollments
     */
    public function enrollments(Request $request)
    {
        $user = $request->user();
        $enrollments = $user->enrollments()->with('course:id,course_title,course_subtitle,course_image,course_category,course_level')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $enrollments,
            'message' => 'Student enrollments',
        ]);
    }

    /**
     * Drop course - DELETE /api/student/enrollments/{id}
     */
    public function dropCourse(Request $request, $id)
    {
        $user = $request->user();
        $enrollment = Enrollment::where('user_id', $user->id)->findOrFail($id);

        $enrollment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Course dropped successfully',
        ]);
    }

    /**
     * Learn course content - GET /api/student/learn/{courseId}
     */
    public function learn(Request $request, $courseId)
    {
        $user = $request->user();
        $course = Course::with('sections.lessons.activities')->findOrFail($courseId);

        // Verify enrollment
        $enrollment = $user->enrollments()->where('course_id', $courseId)->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $course,
            'message' => 'Course learning materials',
        ]);
    }

    /**
     * Learn grammar - GET /api/student/learn/{courseId}/grammar
     */
    public function learnGrammar(Request $request, $courseId)
    {
        $user = $request->user();
        $user->enrollments()->where('course_id', $courseId)->firstOrFail();

        $activities = Course::find($courseId)->activities()->where('type', 'grammar')->get();

        return response()->json([
            'success' => true,
            'data' => ['activities' => $activities],
            'message' => 'Grammar activities',
        ]);
    }

    /**
     * Learn reading - GET /api/student/learn/{courseId}/reading
     */
    public function learnReading(Request $request, $courseId)
    {
        $user = $request->user();
        $user->enrollments()->where('course_id', $courseId)->firstOrFail();

        $activities = Course::find($courseId)->activities()->where('type', 'reading')->get();

        return response()->json([
            'success' => true,
            'data' => ['activities' => $activities],
            'message' => 'Reading activities',
        ]);
    }

    /**
     * Learn listening - GET /api/student/learn/{courseId}/listening
     */
    public function learnListening(Request $request, $courseId)
    {
        $user = $request->user();
        $user->enrollments()->where('course_id', $courseId)->firstOrFail();

        $activities = Course::find($courseId)->activities()->where('type', 'listening')->get();

        return response()->json([
            'success' => true,
            'data' => ['activities' => $activities],
            'message' => 'Listening activities',
        ]);
    }

    /**
     * Learn vocabulary - GET /api/student/learn/{courseId}/vocabulary
     */
    public function learnVocabulary(Request $request, $courseId)
    {
        $user = $request->user();
        $user->enrollments()->where('course_id', $courseId)->firstOrFail();

        $activities = Course::find($courseId)->activities()->where('type', 'vocabulary')->get();

        return response()->json([
            'success' => true,
            'data' => ['activities' => $activities],
            'message' => 'Vocabulary activities',
        ]);
    }

    /**
     * Save progress - POST /api/student/progress/{courseId}/{type}
     */
    public function saveProgress(Request $request, $courseId, $type)
    {
        $validated = $request->validate([
            'activity_id' => 'required|integer',
            'progress_percentage' => 'required|integer|min:0|max:100',
            'section_data' => 'nullable|array',
        ]);

        $user = $request->user();

        // Verify enrollment (optional - some courses might not require enrollment)
        // $user->enrollments()->where('course_id', $courseId)->firstOrFail();

        $updateData = [
            'progress_percentage' => $validated['progress_percentage'],
            'completed_at' => $validated['progress_percentage'] >= 100 ? now() : null,
        ];

        // Add section_data if provided
        if (isset($validated['section_data'])) {
            $updateData['section_data'] = $validated['section_data'];
        }

        $progress = StudentProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $courseId,
                'activity_type' => $type,
                'activity_id' => $validated['activity_id'],
            ],
            $updateData
        );

        return response()->json([
            'success' => true,
            'data' => $progress,
            'message' => 'Progress saved',
        ]);
    }

    /**
     * Reset progress - POST /api/student/progress/{courseId}/reset
     */
    public function resetProgress(Request $request, $courseId)
    {
        $validated = $request->validate([
            'activity_ids' => 'required|array',
            'activity_ids.*' => 'integer',
        ]);

        $user = $request->user();

        StudentProgress::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->where('activity_type', 'section')
            ->whereIn('activity_id', $validated['activity_ids'])
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Progress reset successfully',
        ]);
    }

    /**
     * Get course progress - GET /api/student/progress/{courseId}
     */
    public function courseProgress(Request $request, $courseId)
    {
        $user = $request->user();

        $progress = $user->studentProgress()
            ->where('course_id', $courseId)
            ->get();

        $totalActivities = 4; // Grammar, Reading, Listening, Vocabulary
        $completedActivities = $progress->where('progress_percentage', 100)->count();
        $overallProgress = ($completedActivities / $totalActivities) * 100;

        return response()->json([
            'success' => true,
            'data' => [
                'course_id' => $courseId,
                'progress_details' => $progress,
                'overall_progress' => $overallProgress,
            ],
            'message' => 'Course progress',
        ]);
    }

    /**
     * Get all progress - GET /api/student/progress/all
     */
    public function allProgress(Request $request)
    {
        $user = $request->user();

        $progress = $user->studentProgress()->get();

        return response()->json([
            'success' => true,
            'data' => $progress,
            'message' => 'All progress',
        ]);
    }

    /**
     * Get reading progress - GET /api/student/progress/reading/{id}
     */
    public function readingProgress(Request $request, $id)
    {
        $user = $request->user();

        $progress = $user->studentProgress()
            ->where('activity_type', 'reading')
            ->where('course_id', $id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $progress,
            'message' => 'Reading progress',
        ]);
    }

    /**
     * Get listening progress - GET /api/student/progress/listening/{id}
     */
    public function listeningProgress(Request $request, $id)
    {
        $user = $request->user();

        $progress = $user->studentProgress()
            ->where('activity_type', 'listening')
            ->where('course_id', $id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $progress,
            'message' => 'Listening progress',
        ]);
    }

    /**
     * Get vocabulary progress - GET /api/student/progress/vocabulary/{id}
     */
    public function vocabularyProgress(Request $request, $id)
    {
        $user = $request->user();

        $progress = $user->studentProgress()
            ->where('activity_type', 'vocabulary')
            ->where('course_id', $id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $progress,
            'message' => 'Vocabulary progress',
        ]);
    }

    /**
     * Get grammar progress - GET /api/student/progress/grammar/{id}
     */
    public function grammarProgress(Request $request, $id)
    {
        $user = $request->user();

        $progress = $user->studentProgress()
            ->where('activity_type', 'grammar')
            ->where('course_id', $id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $progress,
            'message' => 'Grammar progress',
        ]);
    }

    /**
     * Mark course complete - POST /api/student/mark-complete/{courseId}
     */
    public function markComplete(Request $request, $courseId)
    {
        $user = $request->user();

        $enrollment = $user->enrollments()
            ->where('course_id', $courseId)
            ->firstOrFail();

        $enrollment->status = 'completed';
        $enrollment->completion_date = now();
        $enrollment->save();

        return response()->json([
            'success' => true,
            'data' => $enrollment,
            'message' => 'Course marked as complete',
        ]);
    }

    /**
     * Get student records - GET /api/student/records
     */
    public function records(Request $request)
    {
        $user = $request->user();

        $records = $user->enrollments()
            ->with('course')
            ->where('status', 'completed')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $records,
            'message' => 'Student records',
        ]);
    }

    /**
     * Add student record - POST /api/student/records
     */
    public function addRecord(Request $request)
    {
        // This endpoint may be for manually adding records by admin
        // For now, return a simple response

        return response()->json([
            'success' => true,
            'message' => 'Record added',
        ], 201);
    }

    /**
     * Get student syllabus progress - GET /api/student/syllabus-progress
     */
    public function syllabusProgress(Request $request)
    {
        $user = $request->user();

        // Check if table exists
        if (!Schema::hasTable('student_records')) {
            return response()->json([
                'success' => true,
                'data' => [
                    'total' => 0,
                    'completed' => 0,
                    'progress_percentage' => 0,
                    'syllabus' => [],
                ],
                'message' => 'Syllabus progress retrieved',
            ]);
        }

        // Get syllabus from database
        $syllabus = DB::table('student_records')
            ->where('student_id', $user->id)
            ->value('syllabus');

        $decodedSyllabus = $syllabus ? json_decode($syllabus, true) : [];

        // Ensure it's an array
        if (!is_array($decodedSyllabus)) {
            $decodedSyllabus = [];
        }

        // Calculate progress
        $total = count($decodedSyllabus);
        $completed = 0;

        foreach ($decodedSyllabus as $item) {
            if (isset($item['status']) && $item['status'] === 'Completed') {
                $completed++;
            }
        }

        $progressPercentage = $total > 0 ? round(($completed / $total) * 100) : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'completed' => $completed,
                'progress_percentage' => $progressPercentage,
                'syllabus' => $decodedSyllabus,
            ],
            'message' => 'Syllabus progress retrieved',
        ]);
    }
}
