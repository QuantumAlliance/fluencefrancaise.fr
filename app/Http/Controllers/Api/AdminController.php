<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{User, Course, Enrollment, Payment, Coupon, Page, ClassType, Settings, EmailSettings, TutorStudentAssignment, UserPreference, StudentProgress, MeetLog, Group, TutorVacation, TimerEditRequest};
use App\Services\GoogleMeetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // Change Total Users to Active Students (those with an 'active' enrollment)
        $activeUsers = User::where('user_type', 'student')
            ->whereHas('enrollments', function($q) {
                $q->where('status', 'active');
            })->count();
        $totalStudents = User::where('user_type', 'student')->count();
        $totalCourses = Course::count();
        $totalEnrollments = Enrollment::count();
        
        // Calculate new users this month for the UI
        $newUsersThisMonth = Enrollment::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->distinct('user_id')
            ->count('user_id');
        
        // Check if current user has permission to view total revenue
        $currentUser = $request->user();
        $hideTotalRevenue = false;
        
        if ($currentUser && isset($currentUser->permissions['hide_total_revenue'])) {
            $hideTotalRevenue = (bool)$currentUser->permissions['hide_total_revenue'];
        }
        
        $totalRevenue = 0;
        $revenueThisMonth = 0;
        $revenueThisWeek = 0;

        if (!$hideTotalRevenue) {
            $totalRevenue = Payment::sum('amount');
            $revenueThisMonth = Payment::whereMonth('paid_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount');
            $revenueThisWeek = Payment::whereBetween('paid_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->sum('amount');
        }

        return response()->json([
            'success' => true,
            'data' => [
                'active_users' => $activeUsers,
                'total_users' => $totalStudents, 
                'new_users_this_month' => $newUsersThisMonth,
                'total_courses' => $totalCourses,
                'total_enrollments' => $totalEnrollments,
                'total_revenue' => $totalRevenue,
                'revenue_this_month' => $revenueThisMonth,
                'revenue_this_week' => $revenueThisWeek,
            ],
            'message' => 'Admin dashboard',
        ]);
    }

    /**
     * Admin insights - GET /api/admin/insights
     */
    public function insights(Request $request)
    {
        $studentCount = User::where('user_type', 'student')->count();
        $tutorCount = User::where('user_type', 'tutor')->count();
        $adminCount = User::whereIn('user_type', ['admin', 'superadmin'])->count();
        $completedEnrollments = Enrollment::where('status', 'completed')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'students' => $studentCount,
                'tutors' => $tutorCount,
                'admins' => $adminCount,
                'completed_enrollments' => $completedEnrollments,
            ],
            'message' => 'Admin insights',
        ]);
    }

    /**
     * Combined Dashboard Data - GET /api/admin/dashboard-all
     * Returns all dashboard data in a single API call for better performance
     */
    public function dashboardAll(Request $request)
    {
        $currentUser = $request->user();
        $hideTotalRevenue = false;

        if ($currentUser && isset($currentUser->permissions['hide_total_revenue'])) {
            $hideTotalRevenue = (bool)$currentUser->permissions['hide_total_revenue'];
        }

        // Stats
        $activeStudentsCount = User::where('user_type', 'student')
            ->whereHas('enrollments', function($q) {
                $q->where('status', 'active');
            })->count();
        $totalStudents = User::where('user_type', 'student')->count();
        $totalCourses = Course::count();
        $totalEnrollments = Enrollment::count();
        $newUsersThisMonth = User::where('user_type', 'student')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $totalRevenue = 0;
        $revenueThisMonth = 0;
        $revenueThisWeek = 0;

        if (!$hideTotalRevenue) {
            $totalRevenue = Payment::sum('amount');
            $revenueThisMonth = Payment::whereMonth('paid_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount');
            $revenueThisWeek = Payment::whereBetween('paid_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->sum('amount');
        }

        // Insights (user distribution)
        $studentCount = User::where('user_type', 'student')->count();
        $tutorCount = User::where('user_type', 'tutor')->count();
        $adminCount = User::whereIn('user_type', ['admin', 'superadmin'])->count();

        // Recent users (last 5)
        $recentUsers = User::select('id', 'first_name', 'last_name', 'name', 'user_type', 'created_at')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Enrollments for trends (last 6 months only - optimized)
        $sixMonthsAgo = now()->subMonths(6)->startOfMonth();
        $enrollments = Enrollment::select('id', 'enrollment_date', 'created_at')
            ->whereNotNull('enrollment_date')->where('enrollment_date', '>=', $sixMonthsAgo)
            ->orderBy('enrollment_date', 'desc')
            ->get();

        // Top courses (first 5)
        $topCourses = Course::select('id', 'course_title')
            ->orderBy('display_order', 'asc')
            ->take(5)
            ->get();

        // User preferences
        $trendFilter = UserPreference::where('user_id', $currentUser->id)
            ->where('preference_key', 'admin_trend_filter')
            ->value('preference_value') ?? '6months';
        $revenueFilter = UserPreference::where('user_id', $currentUser->id)
            ->where('preference_key', 'admin_revenue_filter')
            ->value('preference_value') ?? 'total';

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'active_users' => $activeStudentsCount,
                    'total_users' => $totalStudents,
                    'new_users_this_month' => $newUsersThisMonth,
                    'total_courses' => $totalCourses,
                    'total_enrollments' => $totalEnrollments,
                    'total_revenue' => $totalRevenue,
                    'revenue_this_month' => $revenueThisMonth,
                    'revenue_this_week' => $revenueThisWeek,
                ],
                'insights' => [
                    'students' => $studentCount,
                    'tutors' => $tutorCount,
                    'admins' => $adminCount,
                ],
                'recent_users' => $recentUsers,
                'enrollments' => $enrollments,
                'top_courses' => $topCourses,
                'preferences' => [
                    'trend_filter' => $trendFilter,
                    'revenue_filter' => $revenueFilter,
                ],
            ],
            'message' => 'Dashboard data loaded',
        ]);
    }

    // COURSE MANAGEMENT (8 methods)

    /**
     * Get all courses - GET /api/admin/courses
     */
    public function courses(Request $request)
    {
        $page = $request->get('page', 1);
        $cacheKey = 'courses_list_page_' . $page;
        $cacheDuration = self::getCacheDuration('courses');

        // Use cache if enabled
        if ($cacheDuration) {
            $courses = Cache::remember($cacheKey, $cacheDuration, function () {
                return Course::leftJoin('class_types', function ($join) {
                        $join->on('courses.course_category', '=', 'class_types.class_name')
                             ->orOn('courses.course_category', '=', 'class_types.name');
                    })
                    ->select('courses.*')
                    ->orderBy('courses.display_order', 'asc')
                    ->orderBy('class_types.display_order', 'asc')
                    ->orderBy('courses.id', 'asc')
                    ->paginate(15);
            });
        } else {
            // No caching
            $courses = Course::leftJoin('class_types', function ($join) {
                    $join->on('courses.course_category', '=', 'class_types.class_name')
                         ->orOn('courses.course_category', '=', 'class_types.name');
                })
                ->select('courses.*')
                ->orderBy('courses.display_order', 'asc')
                ->orderBy('class_types.display_order', 'asc')
                ->orderBy('courses.id', 'asc')
                ->paginate(15);
        }

        return response()->json([
            'success' => true,
            'data' => $courses,
            'message' => 'All courses',
        ]);
    }

    /**
     * Get course detail - GET /api/admin/courses/{id}
     */
    public function courseDetail(Request $request, $id)
    {
        try {
            $course = Course::findOrFail($id);
            
            // Log the JSON content to debug
            \Log::info('Fetching course detail', [
                'course_id' => $id,
                'json_content_length' => strlen($course->course_json_content ?? ''),
                'json_preview' => substr($course->course_json_content ?? '', 0, 200)
            ]);
            
            // Parse JSON to verify it's valid and count sections
            if ($course->course_json_content) {
                try {
                    $decoded = json_decode($course->course_json_content, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        \Log::info('Course JSON has ' . count($decoded) . ' sections');
                    }
                } catch (\Exception $e) {
                    \Log::error('Error parsing course JSON: ' . $e->getMessage());
                }
            }

        return response()->json([
            'success' => true,
            'data' => $course,
            'message' => 'Course detail',
        ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching course detail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching course: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store course - POST /api/admin/courses
     */
    public function storeCourse(Request $request)
    {
        // Don't validate files with file rule - it triggers fileinfo extension requirement
        // Instead, validate them manually after storing
        $validated = $request->validate([
            'course_title' => 'nullable|string|max:200',
            'course_subtitle' => 'nullable|string|max:300',
            'course_description' => 'nullable|string',
            'course_category' => 'nullable|string',
            'course_language' => 'nullable|string',
            'course_level' => 'nullable|string',
            'course_level_custom' => 'nullable|string',
            'course_total_texts' => 'nullable|integer|min:1|max:100',
            'course_json_content' => 'nullable|string',
            'course_is_active' => 'nullable|boolean',
            'custom_url' => 'nullable|string|max:500',
            'custom_url_target' => 'nullable|string|in:_blank,_self',
        ]);

        // Set defaults for empty values
        if (empty($validated['course_total_texts'])) {
            $validated['course_total_texts'] = 5;
        }
        if (empty($validated['course_is_active'])) {
            $validated['course_is_active'] = true;
        }

        // Handle file uploads (manual validation without fileinfo extension dependency)
        $allFiles = $request->allFiles();
        if (isset($allFiles['course_image']) && $allFiles['course_image']) {
            $file = $allFiles['course_image'];
            $fileSize = $file->getSize();

            // Manual validation: check file size (5MB = 5242880 bytes)
            if ($fileSize > 5242880) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course image must not exceed 5MB'
                ], 422);
            }

            try {
                // Use move() instead of store() to avoid MIME type detection
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->move(storage_path('app/public/courses/images'), $fileName);
                $storedPath = 'courses/images/' . $fileName;
                $validated['course_image'] = $storedPath;
                \Log::info('Course image stored', ['path' => $storedPath]);
            } catch (\Exception $e) {
                \Log::error('Error storing course image: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store course image'
                ], 500);
            }
        }

        if (isset($allFiles['course_banner']) && $allFiles['course_banner']) {
            $file = $allFiles['course_banner'];
            $fileSize = $file->getSize();

            // Manual validation: check file size (5MB = 5242880 bytes)
            if ($fileSize > 5242880) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course banner must not exceed 5MB'
                ], 422);
            }

            try {
                // Use move() instead of store() to avoid MIME type detection
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->move(storage_path('app/public/courses/banners'), $fileName);
                $storedPath = 'courses/banners/' . $fileName;
                $validated['course_banner'] = $storedPath;
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store course banner'
                ], 500);
            }
        }

        $course = Course::create($validated);

        // Clear courses cache
        self::clearCacheOnUpdate('courses');

        return response()->json([
            'success' => true,
            'data' => $course,
            'message' => 'Course created',
        ], 201);
    }

    /**
     * Update course - PUT /api/admin/courses/{id}
     */
    public function updateCourse(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        // Don't validate files with file rule - it triggers fileinfo extension requirement
        // Instead, validate them manually after storing
        $validated = $request->validate([
            'course_title' => 'nullable|string|max:200',
            'course_subtitle' => 'nullable|string|max:300',
            'course_description' => 'nullable|string',
            'course_category' => 'nullable|string',
            'course_language' => 'nullable|string',
            'course_level' => 'nullable|string',
            'course_level_custom' => 'nullable|string',
            'course_total_texts' => 'nullable|integer|min:1|max:100',
            'course_json_content' => 'nullable|string', // No max length - allow large JSON
            'course_is_active' => 'nullable|boolean',
            'custom_url' => 'nullable|string|max:500',
            'custom_url_target' => 'nullable|string|in:_blank,_self',
        ]);

        // Validate and log JSON content before saving
        if (isset($validated['course_json_content']) && is_string($validated['course_json_content'])) {
            $decoded = json_decode($validated['course_json_content'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid JSON content: ' . json_last_error_msg(),
                ], 422);
            }
            if (is_array($decoded)) {
                \Log::info('Updating course with ' . count($decoded) . ' JSON sections', [
                    'course_id' => $id,
                    'json_length' => strlen($validated['course_json_content'])
                ]);
            }
        }

        // Set defaults for empty values
        if (empty($validated['course_total_texts'])) {
            $validated['course_total_texts'] = 5;
        }
        // Only set default for course_is_active if it's not in the request
        // Using isset() instead of empty() because empty(0) returns true for false boolean
        if (!isset($validated['course_is_active'])) {
            $validated['course_is_active'] = true;
        }

        // Handle file uploads (manual validation without fileinfo extension dependency)
        $allFiles = $request->allFiles();

        // Validate and store course image
        if (isset($allFiles['course_image']) && $allFiles['course_image']) {
            $file = $allFiles['course_image'];
            $fileSize = $file->getSize();

            // Manual validation: check file size (50MB = 52428800 bytes)
            if ($fileSize > 52428800) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course image must not exceed 50MB'
                ], 422);
            }

            try {
                // Use move() instead of store() to avoid MIME type detection
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->move(storage_path('app/public/courses/images'), $fileName);
                $storedPath = 'courses/images/' . $fileName;
                $validated['course_image'] = $storedPath;
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store course image'
                ], 500);
            }
        }

        // Validate and store course banner
        if (isset($allFiles['course_banner']) && $allFiles['course_banner']) {
            $file = $allFiles['course_banner'];
            $fileSize = $file->getSize();

            // Manual validation: check file size (50MB = 52428800 bytes)
            if ($fileSize > 52428800) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course banner must not exceed 50MB'
                ], 422);
            }

            try {
                // Use move() instead of store() to avoid MIME type detection
                $uniqueFileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->move(storage_path('app/public/courses/banners'), $uniqueFileName);
                $storedPath = 'courses/banners/' . $uniqueFileName;
                $validated['course_banner'] = $storedPath;
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store course banner'
                ], 500);
            }
        }

        // Update using Eloquent model
        $course->update($validated);

        // Refresh the model to ensure we have the latest data from database
        $course->refresh();

        // Ensure course_json_content is returned as a string (not array)
        $courseData = $course->toArray();
        if (isset($courseData['course_json_content']) && is_array($courseData['course_json_content'])) {
            $courseData['course_json_content'] = json_encode($courseData['course_json_content'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        // Clear courses cache
        self::clearCacheOnUpdate('courses');

        return response()->json([
            'success' => true,
            'data' => $courseData,
            'message' => 'Course updated',
        ]);
    }

    /**
     * Delete course - DELETE /api/admin/courses/{id}
     */
    public function deleteCourse(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        // Clear courses cache
        self::clearCacheOnUpdate('courses');

        return response()->json([
            'success' => true,
            'message' => 'Course deleted',
        ]);
    }

    /**
     * Upload course image - POST /api/admin/courses/{id}/upload-image
     */
    public function uploadImage(Request $request, $id)
    {
        $validated = $request->validate([
            'banner_image' => 'required|image|max:5120',
        ]);

        $course = Course::findOrFail($id);

        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('courses', 'public');
            $course->banner_image = $path;
            $course->save();
        }

        return response()->json([
            'success' => true,
            'data' => ['banner_image' => $course->banner_image],
            'message' => 'Course image uploaded',
        ]);
    }

    /**
     * Upload course content - POST /api/admin/courses/{id}/upload-content
     */
    public function uploadContent(Request $request, $id)
    {
        $validated = $request->validate([
            'content_json' => 'required|string',
        ]);
        
        // Map content_json from request to course_json_content in database
        $course = Course::findOrFail($id);
        $course->course_json_content = $validated['content_json'];
        $course->save();

        return response()->json([
            'success' => true,
            'data' => $course,
            'message' => 'Course content uploaded',
        ]);
    }

    /**
     * Bulk delete courses - POST /api/admin/courses/bulk-delete
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:courses,id',
        ]);

        Course::whereIn('id', $validated['ids'])->delete();

        return response()->json([
            'success' => true,
            'message' => 'Courses deleted',
        ]);
    }

    /**
     * Reorder courses - POST /api/admin/courses/reorder
     */
    public function reorderCourses(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|integer|exists:courses,id',
            'order.*.display_order' => 'required|integer|min:1',
        ]);

        foreach ($validated['order'] as $item) {
            Course::where('id', $item['id'])
                ->update(['display_order' => $item['display_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Courses reordered successfully',
        ]);
    }

    // STUDENT MANAGEMENT (5 methods)

    /**
     * Get all students - GET /api/admin/students
     */
    public function students(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        // Validate per_page to prevent abuse
        $perPage = min((int)$perPage, 200);
        $perPage = max($perPage, 1);

        $search = $request->input('search', '');
        $status = $request->input('status', '');
        $paymentStatus = $request->input('payment_status', '');
        $tutorAssignment = $request->input('tutor_assignment', '');
        $year = $request->input('year', '');

        $query = User::where('user_type', 'student')
            ->with(['studentAssignments' => function($q) {
                $q->with('tutor:id,first_name,last_name,name');
            }])
            ->with(['enrollments' => function($q) {
                $q->with('payment')->latest('enrollment_date');
            }]);

        // Apply search filter
        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('first_name', 'like', $searchTerm)
                  ->orWhere('last_name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm)
                  ->orWhere('name', 'like', $searchTerm)
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$searchTerm]);
            });
        }

        // Apply payment status filter
        if (!empty($paymentStatus)) {
            $query->where('payment_confirmed', $paymentStatus === 'paid');
        }

        // Apply tutor assignment filter
        if (!empty($tutorAssignment)) {
            if ($tutorAssignment === 'assigned') {
                $query->has('studentAssignments');
            } else {
                $query->doesntHave('studentAssignments');
            }
        }

        // Apply year filter
        if (!empty($year)) {
            $query->whereHas('enrollments', function($q) use ($year) {
                $q->whereYear('enrollment_date', $year)
                  ->orWhereYear('created_at', $year);
            });
        }

        // Apply status filter based on enrollment status
        if (!empty($status)) {
            if ($status === 'na') {
                // Special case for N/A: Imported, no payment status, pending status
                $query->whereHas('enrollments', function($q) {
                    $q->where(function($sq) {
                        $sq->whereNull('status')
                           ->orWhere('status', 'pending')
                           ->orWhere('status', '');
                    })->where(function($sq) {
                        $sq->whereJsonContains('form_data->_imported', true)
                           ->orWhereJsonContains('form_data->_imported', 'true')
                           ->orWhereJsonContains('form_data->_import_source', 'gravity_forms');
                    });
                });
            } else {
                $query->whereHas('enrollments', function($q) use ($status) {
                    $q->where('status', strtolower($status));
                });
            }
        }

        $students = $query->orderBy('created_at', 'desc') // Show newest students first
            ->paginate($perPage);

        // Add tutor_id to each student for easier frontend access
        $students->getCollection()->transform(function ($student) {
            $assignment = $student->studentAssignments->first();
            $student->tutor_id = $assignment ? $assignment->tutor_id : null;
            $student->tutor = $assignment ? $assignment->tutor : null;
            return $student;
        });

        // Get years that actually have enrollments for the filter
        $availableYears = Enrollment::selectRaw('YEAR(enrollment_date) as year')
            ->whereNotNull('enrollment_date')
            ->distinct()
            ->union(
                Enrollment::selectRaw('YEAR(created_at) as year')
                    ->distinct()
            )
            ->pluck('year')
            ->map(function($y) { return (int)$y; })
            ->filter()
            ->unique()
            ->sortDesc()
            ->values()
            ->toArray();

        return response()->json([
            'success' => true,
            'data' => $students,
            'available_years' => $availableYears,
            'message' => 'All students',
        ]);
    }

    /**
     * Get student detail - GET /api/admin/students/{id}
     */
    public function studentDetail(Request $request, $id)
    {
        $student = User::where('user_type', 'student')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $student,
            'message' => 'Student detail',
        ]);
    }

    /**
     * Store student - POST /api/admin/students
     */
    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'tutor_id' => 'nullable|exists:users,id',
            'payment_confirmed' => 'nullable|boolean',
        ]);

        $username = explode('@', $validated['email'])[0] . '_' . time();

        // Create full name from first_name and last_name for the 'name' field
        $fullName = trim($validated['first_name'] . ' ' . $validated['last_name']);

        $student = User::create([
            'name' => $fullName, // Required field in users table
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'username' => $username,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'user_type' => 'student',
            'tutor_id' => $validated['tutor_id'] ?? null,
            'payment_confirmed' => $validated['payment_confirmed'] ?? false,
        ]);

        return response()->json([
            'success' => true,
            'data' => $student,
            'message' => 'Student created',
        ], 201);
    }

    /**
     * Update student - PUT /api/admin/students/{id}
     */
    public function updateStudent(Request $request, $id)
    {
        $student = User::where('user_type', 'student')->findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'nullable|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'status' => 'nullable|string',
            'payment_confirmed' => 'nullable|boolean',
        ]);

        $student->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? $student->phone,
            'payment_confirmed' => $validated['payment_confirmed'] ?? $student->payment_confirmed,
        ]);

        if (isset($validated['status']) && $validated['status'] === 'Active') {
            $latestEnrollment = $student->enrollments()->latest('enrollment_date')->first() 
                            ?? $student->enrollments()->latest('created_at')->first();
            
            if ($latestEnrollment) {
                $latestEnrollment->status = 'active';
                $formData = $latestEnrollment->form_data ?? [];
                $formData['_manual_activation'] = true;
                $latestEnrollment->form_data = $formData;
                $latestEnrollment->save();
            }
        }

        return response()->json([
            'success' => true,
            'data' => $student,
            'message' => 'Student updated',
        ]);
    }

    /**
     * Delete student - DELETE /api/admin/students/{id}
     */
    public function deleteStudent(Request $request, $id)
    {
        $student = User::where('user_type', 'student')->findOrFail($id);

        // Delete related enrollments
        Enrollment::where('user_id', $student->id)->delete();

        // Delete student
        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student deleted',
        ]);
    }

    /**
     * Bulk delete students - POST /api/admin/students/bulk-delete
     */
    public function bulkDeleteStudents(Request $request)
    {
        \Log::info('DEBUG: Bulk delete request received', [
            'all_data' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'integer|exists:users,id',
        ]);

        \Log::info('DEBUG: Attempting bulk delete', [
            'student_ids' => $validated['student_ids'],
            'count' => count($validated['student_ids'])
        ]);

        // Delete related enrollments for all students
        $enrollmentDeleteCount = Enrollment::whereIn('user_id', $validated['student_ids'])->delete();
        \Log::info('DEBUG: Enrollments deleted', ['count' => $enrollmentDeleteCount]);

        // Delete students (don't filter by user_type - just delete by ID)
        $deletedCount = User::whereIn('id', $validated['student_ids'])->delete();
        \Log::info('DEBUG: Students deleted', ['count' => $deletedCount]);

        return response()->json([
            'success' => true,
            'deleted_count' => $deletedCount,
            'message' => "$deletedCount student(s) deleted successfully",
        ]);
    }

    /**
     * Assign tutor to student - POST /api/admin/students/{id}/assign-tutor
     */
    public function assignTutor(Request $request, $id)
    {
        $request->validate([
            'tutor_id' => 'nullable|integer|exists:users,id',
        ]);

        $student = User::where('user_type', 'student')->findOrFail($id);
        
        $tutorId = $request->input('tutor_id');
        
        // Verify tutor is actually a tutor if provided
        if ($tutorId) {
            $tutor = User::where('id', $tutorId)
                         ->where('user_type', 'tutor')
                         ->firstOrFail();
        }

        // Remove existing assignments for this student
        TutorStudentAssignment::where('student_id', $id)->delete();

        // If tutor_id is provided, create new assignment
        if ($tutorId) {
            TutorStudentAssignment::create([
                'tutor_id' => $tutorId,
                'student_id' => $id,
            ]);

        return response()->json([
            'success' => true,
                'message' => "Tutor assigned to student successfully",
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => "Tutor unassigned from student successfully",
        ]);
        }
    }

    /**
     * Toggle payment confirmation for a student - POST /api/admin/students/{id}/toggle-payment
     */
    public function togglePaymentConfirmed(Request $request, $id)
    {
        $student = User::where('user_type', 'student')->findOrFail($id);

        $student->payment_confirmed = !$student->payment_confirmed;
        $student->save();

        if ($student->payment_confirmed && $request->input('update_status')) {
            $latestEnrollment = $student->enrollments()->latest('enrollment_date')->first() 
                            ?? $student->enrollments()->latest('created_at')->first();
            
            if ($latestEnrollment) {
                $latestEnrollment->status = 'active';
                $formData = $latestEnrollment->form_data ?? [];
                $formData['_manual_activation'] = true;
                $latestEnrollment->form_data = $formData;
                $latestEnrollment->save();
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'payment_confirmed' => $student->payment_confirmed,
                'status' => $student->payment_confirmed && $request->input('update_status') ? 'Active' : null,
                'is_manual' => $student->payment_confirmed && $request->input('update_status')
            ],
            'message' => $student->payment_confirmed
                ? 'Access granted - student now has full access'
                : 'Access revoked - student access restricted'
        ]);
    }

    /**
     * Get student records - GET /api/admin/students/{id}/records
     */
    public function getStudentRecords(Request $request, $id)
    {
        $student = User::where('user_type', 'student')->findOrFail($id);
        
        // Check if table exists
        if (!Schema::hasTable('student_records')) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Student records retrieved (table not created yet)',
            ]);
        }
        
        // Get records from database (stored as JSON)
        $records = DB::table('student_records')
            ->where('student_id', $id)
            ->value('records');
        
        $decodedRecords = $records ? json_decode($records, true) : [];
        
        // Ensure it's an array
        if (!is_array($decodedRecords)) {
            $decodedRecords = [];
        }
        
        return response()->json([
            'success' => true,
            'data' => $decodedRecords,
            'message' => 'Student records retrieved',
        ]);
    }

    /**
     * Save student records - POST /api/admin/students/{id}/records
     */
    public function saveStudentRecords(Request $request, $id)
    {
        $student = User::where('user_type', 'student')->findOrFail($id);
        
        $validated = $request->validate([
            'records' => 'required|array',
            'records.*.id' => 'nullable|string',
            'records.*.saved_at' => 'nullable|string',
            'records.*.date' => 'nullable|date',
            'records.*.attendance' => 'nullable|string',
            'records.*.reason' => 'nullable|string',
            'records.*.reschedule' => 'nullable|date',
            'records.*.homework' => 'nullable|string',
            'records.*.progress' => 'nullable|string',
            'records.*.notes' => 'nullable|string',
        ]);

        // Ensure the table exists (in case migration hasn't run)
        if (!Schema::hasTable('student_records')) {
            return response()->json([
                'success' => false,
                'message' => 'Database table not found. Please run migrations.',
            ], 500);
        }

        // Get existing record to preserve created_at
        $existing = DB::table('student_records')->where('student_id', $id)->first();

        // Consolidate by unique ID (allows multiple records per date)
        $consolidatedRecords = [];
        foreach ($validated['records'] as $record) {
            if (empty($record['id'])) {
                $record['id'] = uniqid('rec_', true);
            }
            if (empty($record['saved_at'])) {
                $record['saved_at'] = now()->toDateTimeString();
            }
            $consolidatedRecords[$record['id']] = $record;
        }

        // Store records as JSON
        DB::table('student_records')->updateOrInsert(
            ['student_id' => $id],
            [
                'records' => json_encode(array_values($consolidatedRecords)),
                'updated_at' => now(),
                'created_at' => $existing->created_at ?? now()
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Records saved successfully',
        ]);
    }

    /**
     * Get student syllabus - GET /api/admin/students/{id}/syllabus
     */
    public function getStudentSyllabus(Request $request, $id)
    {
        $student = User::where('user_type', 'student')->findOrFail($id);
        
        // Check if table exists
        if (!Schema::hasTable('student_records')) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Student syllabus retrieved (table not created yet)',
            ]);
        }
        
        // Get syllabus from database (stored as JSON)
        $syllabus = DB::table('student_records')
            ->where('student_id', $id)
            ->value('syllabus');
        
        $decodedSyllabus = $syllabus ? json_decode($syllabus, true) : [];
        
        // Ensure it's an array
        if (!is_array($decodedSyllabus)) {
            $decodedSyllabus = [];
        }
        
        return response()->json([
            'success' => true,
            'data' => $decodedSyllabus,
            'message' => 'Student syllabus retrieved',
        ]);
    }

    /**
     * Save student syllabus - POST /api/admin/students/{id}/syllabus
     */
    public function saveStudentSyllabus(Request $request, $id)
    {
        $student = User::where('user_type', 'student')->findOrFail($id);
        
        $validated = $request->validate([
            'syllabus' => 'required|array',
            'syllabus.*.level' => 'nullable|string',
            'syllabus.*.topic' => 'required|string',
            'syllabus.*.date' => 'nullable|date',
            'syllabus.*.status' => 'required|string|in:Completed,In Progress',
        ]);

        // Ensure the table exists (in case migration hasn't run)
        if (!Schema::hasTable('student_records')) {
            return response()->json([
                'success' => false,
                'message' => 'Database table not found. Please run migrations.',
            ], 500);
        }

        // Get existing record to preserve created_at and records
        $existing = DB::table('student_records')->where('student_id', $id)->first();
        
        // Store syllabus as JSON, preserve existing records if any
        DB::table('student_records')->updateOrInsert(
            ['student_id' => $id],
            [
                'syllabus' => json_encode($validated['syllabus']),
                'records' => $existing->records ?? null, // Preserve existing records
                'updated_at' => now(),
                'created_at' => $existing->created_at ?? now()
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Syllabus saved successfully',
        ]);
    }

    // TUTOR MANAGEMENT (5 methods)

    /**
     * Get all tutors - GET /api/admin/tutors
     */
    public function tutors(Request $request)
    {
        $tutors = User::where('user_type', 'tutor')->get();

        // Attach pending timer edit request counts per tutor
        $pendingCounts = TimerEditRequest::where('status', 'pending')
            ->selectRaw('tutor_id, COUNT(*) as count')
            ->groupBy('tutor_id')
            ->pluck('count', 'tutor_id');

        $tutors->each(function ($tutor) use ($pendingCounts) {
            $tutor->pending_timer_edits = $pendingCounts->get($tutor->id, 0);
        });

        return response()->json([
            'success' => true,
            'data' => $tutors,
            'message' => 'All tutors',
        ]);
    }

    /**
     * Remove student assignment from tutor - POST /api/admin/tutors/{id}/remove-student
     */
    public function removeStudentFromTutor(Request $request, $id)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:users,id',
        ]);

        TutorStudentAssignment::where('tutor_id', $id)
            ->where('student_id', $validated['student_id'])
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student removed from tutor successfully',
        ]);
    }

    /**
     * Get tutor detail - GET /api/admin/tutors/{id}
     */
    public function tutorDetail(Request $request, $id)
    {
        $tutor = User::where('user_type', 'tutor')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $tutor,
            'message' => 'Tutor detail',
        ]);
    }

    /**
     * Get tutor stats - GET /api/admin/tutors/{id}/stats
     */
    public function tutorStats(Request $request, $id)
    {
        $tutor = User::where('user_type', 'tutor')->findOrFail($id);

        // Get assigned students
        $assignedStudentIds = TutorStudentAssignment::where('tutor_id', $id)
            ->pluck('student_id')
            ->unique();

        $assignedStudents = User::whereIn('id', $assignedStudentIds)
            ->where('user_type', 'student')
            ->get()
            ->map(function ($student) {
                $name = $student->name;
                if (!$name || trim($name) === '') {
                    $name = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
                }
                if (!$name || trim($name) === '') {
                    $name = $student->email ? explode('@', $student->email)[0] : 'Unknown';
                }
                return [
                    'id' => $student->id,
                    'name' => (string)$name,
                    'email' => $student->email ? (string)$student->email : 'N/A',
                    'profile_picture' => $student->profile_picture,
                    'created_at' => $student->created_at ? $student->created_at->toDateTimeString() : null,
                ];
            })
            ->values()
            ->toArray();

        // Get records for all assigned students
        $allRecords = [];
        if (Schema::hasTable('student_records')) {
            foreach ($assignedStudentIds as $studentId) {
                $record = DB::table('student_records')
                    ->where('student_id', $studentId)
                    ->first();

                if ($record) {
                    $recordsData = $record->records ? json_decode($record->records, true) : [];
                    $syllabusData = $record->syllabus ? json_decode($record->syllabus, true) : [];

                    if (!empty($recordsData) || !empty($syllabusData)) {
                        $student = User::find($studentId);
                        $allRecords[] = [
                            'student_id' => $studentId,
                            'student_name' => $student ? ($student->name || trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''))) : 'Unknown',
                            'records_count' => count($recordsData),
                            'syllabus_count' => count($syllabusData),
                            'records' => $recordsData,
                            'syllabus' => $syllabusData,
                        ];
                    }
                }
            }
        }

        // Get tutor pay rates from preferences
        $payRates = UserPreference::where('user_id', $id)
            ->where('preference_key', 'tutor_pay_rates')
            ->value('preference_value');
        
        $payRates = $payRates ? json_decode($payRates, true) : null;

        // Check for permission to see pay rates
        $currentUser = $request->user();
        if ($currentUser && isset($currentUser->permissions['hide_tutor_pay']) && $currentUser->permissions['hide_tutor_pay']) {
            $payRates = null;
        }

        // Get tutor's groups
        $groups = Group::where('tutor_id', $id)->with('students')->get()->map(function($group) {
            return [
                'id' => $group->id,
                'name' => $group->name,
                'student_ids' => $group->students->pluck('id')->toArray(),
                'student_count' => $group->students->count()
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'profile' => [
                    'id' => $tutor->id,
                    'first_name' => $tutor->first_name,
                    'last_name' => $tutor->last_name,
                    'name' => $tutor->name || trim(($tutor->first_name ?? '') . ' ' . ($tutor->last_name ?? '')),
                    'email' => $tutor->email,
                    'profile_picture' => $tutor->profile_picture,
                    'biography' => $tutor->biography,
                    'phone' => $tutor->phone,
                    'created_at' => $tutor->created_at ? $tutor->created_at->toDateTimeString() : null,
                    'updated_at' => $tutor->updated_at ? $tutor->updated_at->toDateTimeString() : null,
                    'working_status' => $tutor->working_status,
                ],
                'assigned_students' => $assignedStudents,
                'students_count' => count($assignedStudents),
                'records' => $allRecords,
                'groups' => $groups,
                'pay_rates' => $payRates,
            'vacation' => [
                'vacations' => TutorVacation::where('tutor_id', $id)
                    ->orderBy('start_date', 'asc')
                    ->get()
                    ->map(function ($v) {
                        return [
                            'id' => $v->id,
                            'start_date' => $v->start_date->format('Y-m-d'),
                            'end_date' => $v->end_date->format('Y-m-d'),
                            'total_days' => $v->start_date->diffInDays($v->end_date) + 1,
                            'reason' => $v->reason,
                            'status' => $v->status,
                        ];
                    }),
                'used_days' => TutorVacation::where('tutor_id', $id)
                    ->whereIn('status', ['approved', 'pending'])
                    ->get()
                    ->sum(function($v) {
                        return $v->start_date->diffInDays($v->end_date) + 1;
                    }),
                'max_days' => (int)(UserPreference::where('user_id', $id)
                    ->where('preference_key', 'max_vacation_days')
                    ->value('preference_value') ?? 0),
            ],
            ],
            'message' => 'Tutor stats',
        ]);
    }

    /**
     * Update tutor pay rates - POST /api/admin/tutors/{id}/pay-rates
     */
    public function updateTutorPayRates(Request $request, $id)
    {
        $currentUser = $request->user();
        if ($currentUser && isset($currentUser->permissions['hide_tutor_pay']) && $currentUser->permissions['hide_tutor_pay']) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to update tutor pay rates.'
            ], 403);
        }

        $validated = $request->validate([
            'pay_rates' => 'required|array',
        ]);

        UserPreference::updateOrCreate(
            ['user_id' => $id, 'preference_key' => 'tutor_pay_rates'],
            ['preference_value' => json_encode($validated['pay_rates'])]
        );

        return response()->json([
            'success' => true,
            'message' => 'Tutor pay rates updated successfully',
        ]);
    }

    /**
     * Store tutor - POST /api/admin/tutors
     */
    public function storeTutor(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'biography' => 'nullable|string|max:1000',
        ]);

        $username = explode('@', $validated['email'])[0] . '_' . time();
        $name = trim($validated['first_name'] . ' ' . $validated['last_name']);

        $tutor = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'name' => $name,
            'username' => $username,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'biography' => $validated['biography'] ?? null,
            'user_type' => 'tutor',
        ]);

        return response()->json([
            'success' => true,
            'data' => $tutor,
            'message' => 'Tutor created',
        ], 201);
    }

    /**
     * Update tutor - PUT /api/admin/tutors/{id}
     */
    public function updateTutor(Request $request, $id)
    {
        $tutor = User::where('user_type', 'tutor')->findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'nullable|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'name' => 'nullable|string|max:100',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:20',
            'biography' => 'nullable|string|max:1000',
        ]);

        // If first_name and last_name are provided, also set name
        if (isset($validated['first_name']) || isset($validated['last_name'])) {
            $validated['name'] = trim(($validated['first_name'] ?? '') . ' ' . ($validated['last_name'] ?? ''));
        }

        // Handle password hashing
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $tutor->update($validated);

        return response()->json([
            'success' => true,
            'data' => $tutor,
            'message' => 'Tutor updated',
        ]);
    }

    /**
     * Delete tutor - DELETE /api/admin/tutors/{id}
     */
    public function deleteTutor(Request $request, $id)
    {
        $tutor = User::where('user_type', 'tutor')->findOrFail($id);
        $tutor->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tutor deleted',
        ]);
    }

    /**
     * Update tutor working status - POST /api/admin/tutors/{id}/update-status
     */
    public function updateTutorStatus(Request $request, $id)
    {
        $tutor = User::where('user_type', 'tutor')->findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|string|in:full_time,part_time,stopped'
        ]);

        $tutor->working_status = $validated['status'];
        $tutor->save();

        $statusLabels = [
            'full_time' => 'Full Time',
            'part_time' => 'Part Time',
            'stopped' => 'No Classes'
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'working_status' => $tutor->working_status
            ],
            'message' => 'Status updated to ' . $statusLabels[$tutor->working_status],
        ]);
    }

    /**
     * Get tutor notes - GET /api/admin/tutors/{id}/notes
     */
    public function getTutorNotes(Request $request, $id)
    {
        $tutor = User::where('user_type', 'tutor')->findOrFail($id);
        
        $notes = \App\Models\TutorNote::where('tutor_id', $id)
            ->with('admin:id,name,first_name,last_name,email')
            ->orderBy('note_date', 'desc')
            ->get()
            ->map(function($note) {
                return [
                    'id' => $note->id,
                    'note_date' => $note->note_date->format('Y-m-d'),
                    'note' => $note->note,
                    'admin_name' => $note->admin->name ?? trim(($note->admin->first_name ?? '') . ' ' . ($note->admin->last_name ?? '')),
                    'created_at' => $note->created_at->toDateTimeString(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $notes,
            'message' => 'Tutor notes retrieved',
        ]);
    }

    /**
     * Save tutor note - POST /api/admin/tutors/{id}/notes
     */
    public function saveTutorNote(Request $request, $id)
    {
        $tutor = User::where('user_type', 'tutor')->findOrFail($id);
        
        $validated = $request->validate([
            'note_date' => 'required|date',
            'note' => 'required|string',
        ]);

        $note = \App\Models\TutorNote::create([
            'tutor_id' => $id,
            'admin_id' => $request->user()->id,
            'note_date' => $validated['note_date'],
            'note' => $validated['note'],
        ]);

        return response()->json([
            'success' => true,
            'data' => $note,
            'message' => 'Note saved successfully',
        ], 201);
    }

    /**
     * Delete tutor note - DELETE /api/admin/tutors/{id}/notes/{noteId}
     */
    public function deleteTutorNote(Request $request, $id, $noteId)
    {
        $note = \App\Models\TutorNote::where('tutor_id', $id)
            ->where('id', $noteId)
            ->firstOrFail();
        
        $note->delete();

        return response()->json([
            'success' => true,
            'message' => 'Note deleted successfully',
        ]);
    }

    // GENERAL USER MANAGEMENT (2 methods)


    /**
     * Get all users - GET /api/admin/users
     */
    public function users(Request $request)
    {
        $users = User::paginate(15);

        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'All users',
        ]);
    }

    /**
     * Bulk delete users - POST /api/admin/users/bulk-delete
     */
    public function usersBulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:users,id',
        ]);

        User::whereIn('id', $validated['ids'])->delete();

        return response()->json([
            'success' => true,
            'message' => 'Users deleted',
        ]);
    }

    /**
     * Get admin, superadmin and tutor users - GET /api/admin/manage-users
     */
    public function manageUsers(Request $request)
    {
        if ($request->user()->permissions && isset($request->user()->permissions['hide_manage_users']) && $request->user()->permissions['hide_manage_users']) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to access user management.'
            ], 403);
        }

        $type = $request->get('type', 'all'); // all, admin, super_admin, tutor

        $query = User::whereIn('user_type', ['admin', 'super_admin', 'tutor']);

        if ($type !== 'all') {
            $query->where('user_type', $type);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'Admin, superadmin and tutor users',
        ]);
    }

    /**
     * Create admin/superadmin/tutor user - POST /api/admin/manage-users
     */
    public function storeManageUser(Request $request)
    {
        if ($request->user()->permissions && isset($request->user()->permissions['hide_manage_users']) && $request->user()->permissions['hide_manage_users']) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to access user management.'
            ], 403);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'user_type' => 'required|in:admin,super_admin,tutor',
            'phone' => 'nullable|string|max:20',
            'permissions' => 'nullable|array',
        ]);

        $username = explode('@', $validated['email'])[0] . '_' . time();
        $fullName = trim($validated['first_name'] . ' ' . $validated['last_name']);

        $user = User::create([
            'name' => $fullName,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'username' => $username,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'user_type' => $validated['user_type'],
            'phone' => $validated['phone'] ?? null,
            'permissions' => $validated['permissions'] ?? [],
        ]);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => ucfirst($validated['user_type']) . ' created successfully',
        ], 201);
    }

    /**
     * Update admin/superadmin/tutor user - PUT /api/admin/manage-users/{id}
     */
    public function updateManageUser(Request $request, $id)
    {
        if ($request->user()->permissions && isset($request->user()->permissions['hide_manage_users']) && $request->user()->permissions['hide_manage_users']) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to access user management.'
            ], 403);
        }

        $user = User::whereIn('user_type', ['admin', 'super_admin', 'tutor'])->findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'nullable|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'user_type' => 'nullable|in:admin,super_admin,tutor',
            'phone' => 'nullable|string|max:20',
            'permissions' => 'nullable|array',
        ]);

        if (isset($validated['first_name']) || isset($validated['last_name'])) {
            $firstName = $validated['first_name'] ?? $user->first_name;
            $lastName = $validated['last_name'] ?? $user->last_name;
            $validated['name'] = trim($firstName . ' ' . $lastName);
        }

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'User updated successfully',
        ]);
    }

    /**
     * Delete admin/superadmin/tutor user - DELETE /api/admin/manage-users/{id}
     */
    public function deleteManageUser(Request $request, $id)
    {
        if ($request->user()->permissions && isset($request->user()->permissions['hide_manage_users']) && $request->user()->permissions['hide_manage_users']) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to access user management.'
            ], 403);
        }

        $user = User::whereIn('user_type', ['admin', 'super_admin', 'tutor'])->findOrFail($id);
        
        // Prevent deleting yourself
        if ($user->id === $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account',
            ], 400);
        }

        // Prevent regular admin from deleting superadmin
        $currentUser = $request->user();
        if ($currentUser->user_type === 'admin' && $user->user_type === 'super_admin') {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete superadmin users',
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }

    // ENROLLMENT MANAGEMENT (5 methods)

    /**
     * Get all enrollments - GET /api/admin/enrollments
     */
    public function enrollments(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        // Validate per_page to prevent abuse
        $perPage = min((int)$perPage, 200);
        $perPage = max($perPage, 1);

        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $search = $request->input('search', '');
        $status = $request->input('status', '');

        // Validate sort order
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? strtolower($sortOrder) : 'desc';

        // Allowed sort columns
        $allowedSortColumns = [
            'id',
            'created_at',
            'enrollment_date',
            'status',
            'entry_id' // Will be handled specially for imported enrollments
        ];

        // Default to created_at if invalid column
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }

        $query = Enrollment::select('id', 'user_id', 'course_id', 'class_type_id', 'status', 'enrollment_date', 'created_at', 'entry_id')
            ->with([
            'user:id,name,first_name,last_name,email,profile_picture',
            'course:id,course_title,course_subtitle,course_image,course_category,course_level',
            'classType:id,class_name,name,price,currency',
            'payment:id,enrollment_id,status,amount,paid_at'
        ]);

        // Apply search filter - search by student name, email, or course name
        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('user', function($userQuery) use ($searchTerm) {
                    $userQuery->where('first_name', 'like', $searchTerm)
                              ->orWhere('last_name', 'like', $searchTerm)
                              ->orWhere('email', 'like', $searchTerm)
                              ->orWhere('name', 'like', $searchTerm)
                              ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$searchTerm]);
                })
                ->orWhereHas('course', function($courseQuery) use ($searchTerm) {
                    $courseQuery->where('course_title', 'like', $searchTerm);
                })
                ->orWhereHas('classType', function($classTypeQuery) use ($searchTerm) {
                    $classTypeQuery->where('class_name', 'like', $searchTerm)
                                   ->orWhere('name', 'like', $searchTerm);
                });
            });
        }

        // Apply status filter
        if (!empty($status)) {
            $query->where('status', strtolower($status));
        }

        // Handle sorting - use id for entry_id to avoid slow JSON parsing
        if ($sortBy === 'entry_id') {
            $query->orderBy('id', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $enrollments = $query->paginate($perPage);

        // Map payment status to enrollment status (only if payment exists and status should override)
        $enrollments->getCollection()->transform(function ($enrollment) {
            // Get payment status if exists
            $paymentStatus = $enrollment->payment ? $enrollment->payment->status : null;
            
            // Only override enrollment status if payment exists and has a valid status
            // Otherwise, use the actual enrollment status from database
            if ($paymentStatus) {
                if (in_array($paymentStatus, ['pending', 'processing'])) {
                    $enrollment->status = 'processing';
                } elseif ($paymentStatus === 'completed') {
                    $enrollment->status = 'active';
                } elseif (in_array($paymentStatus, ['failed', 'cancelled'])) {
                    $enrollment->status = 'cancelled';
                }
            }
            // If no payment, keep the enrollment status as stored in database (pending, active, etc.)
            // Don't default to 'processing' - respect the actual status
            
            return $enrollment;
        });

        return response()->json([
            'success' => true,
            'data' => $enrollments,
            'message' => 'All enrollments',
        ]);
    }

    /**
     * Get enrollment detail - GET /api/admin/enrollments/{id}
     */
    public function enrollmentDetail(Request $request, $id)
    {
        $enrollment = Enrollment::with([
            'user:id,name,first_name,last_name,email,profile_picture,phone,title,biography',
            'course:id,course_title,course_subtitle,course_image,course_category,course_level',
            'classType:id,class_name,name,description,price,currency,duration',
            'payment:id,enrollment_id,status,amount,currency,paid_at,transaction_id'
        ])->findOrFail($id);

        // Map payment status to enrollment status (only if payment exists)
        $paymentStatus = $enrollment->payment ? $enrollment->payment->status : null;
        
        if ($paymentStatus) {
            if (in_array($paymentStatus, ['pending', 'processing'])) {
                $enrollment->status = 'processing';
            } elseif ($paymentStatus === 'completed') {
                $enrollment->status = 'active';
            } elseif (in_array($paymentStatus, ['failed', 'cancelled'])) {
                $enrollment->status = 'cancelled';
            }
        }
        // If no payment, keep the enrollment status as stored in database
        // Don't default to 'processing' - respect the actual status (pending, active, etc.)

        return response()->json([
            'success' => true,
            'data' => $enrollment,
            'message' => 'Enrollment detail',
        ]);
    }

    /**
     * Update enrollment - PUT /api/admin/enrollments/{id}
     */
    public function updateEnrollment(Request $request, $id)
    {
        $enrollment = Enrollment::findOrFail($id);

        $validated = $request->validate([
            'status' => 'nullable|string|in:active,completed,cancelled',
            'class_type_id' => 'nullable|exists:class_types,id',
        ]);

        $enrollment->update($validated);

        return response()->json([
            'success' => true,
            'data' => $enrollment,
            'message' => 'Enrollment updated',
        ]);
    }

    /**
     * Cancel enrollment - DELETE /api/admin/enrollments/{id}
     */
    public function cancelEnrollment(Request $request, $id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->status = 'cancelled';
        $enrollment->save();

        return response()->json([
            'success' => true,
            'message' => 'Enrollment cancelled',
        ]);
    }

    /**
     * Get class types - GET /api/admin/class-types or GET /api/class-types (public)
     */
    public function classTypes(Request $request)
    {
        try {
            // For admin, show all (active and inactive). For public, show only active
            $isAdmin = $request->user() && $request->user()->user_type === 'admin';
            $cacheKey = $isAdmin ? 'class_types_admin' : 'class_types_public';
            $cacheDuration = self::getCacheDuration('class_types');

            if ($cacheDuration) {
                $classTypes = Cache::remember($cacheKey, $cacheDuration, function () use ($isAdmin) {
                    $query = ClassType::query();
                    if (!$isAdmin) {
                        $query->where('is_active', true);
                    }
                    return $query->orderBy('display_order', 'asc')
                        ->orderBy('id', 'asc')
                        ->get();
                });
            } else {
                $query = ClassType::query();
                if (!$isAdmin) {
                    $query->where('is_active', true);
                }
                $classTypes = $query->orderBy('display_order', 'asc')
                    ->orderBy('id', 'asc')
                    ->get();
            }

            return response()->json([
                'success' => true,
                'data' => $classTypes,
                'message' => 'Class types',
            ]);
        } catch (\Exception $e) {
            // If table doesn't exist or any error, return empty array
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Class types',
            ]);
        }
    }

    /**
     * Store class type - POST /api/admin/class-types
     */
    public function storeClassType(Request $request)
    {
        $validated = $request->validate([
            'class_name' => 'required|string|max:255',
            'homepage_title' => 'nullable|string|max:255',
            'homepage_description' => 'nullable|string|max:500',
            'features' => 'nullable|array',
            'is_popular' => 'nullable|boolean',
            'name' => 'nullable|string|max:255', // Alias for class_name
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'duration' => 'required|string|in:weekly,monthly,quarterly',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'is_batch_full' => 'nullable|boolean',
            'batch_full_message' => 'nullable|string|max:255',
            'batch_date' => 'nullable|string|max:255',
            'batch_schedule' => 'nullable|string|max:255',
            'disable_coupons' => 'nullable|boolean',
        ]);

        // Use class_name or name
        $validated['class_name'] = $validated['class_name'] ?? $validated['name'] ?? '';
        unset($validated['name']);

        // Set defaults
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }
        if (!isset($validated['display_order'])) {
            $validated['display_order'] = 0;
        }

        $classType = ClassType::create($validated);

        // Clear class types cache
        self::clearCacheOnUpdate('class_types');

        return response()->json([
            'success' => true,
            'data' => $classType,
            'message' => 'Class type created',
        ], 201);
    }

    /**
     * Update class type - PUT /api/admin/class-types/{id}
     */
    public function updateClassType(Request $request, $id)
    {
        $classType = ClassType::findOrFail($id);

        $validated = $request->validate([
            'class_name' => 'nullable|string|max:255',
            'homepage_title' => 'nullable|string|max:255',
            'homepage_description' => 'nullable|string|max:500',
            'features' => 'nullable|array',
            'is_popular' => 'nullable|boolean',
            'name' => 'nullable|string|max:255', // Alias for class_name
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'duration' => 'required|string|in:weekly,monthly,quarterly',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'is_batch_full' => 'nullable|boolean',
            'batch_full_message' => 'nullable|string|max:255',
            'batch_date' => 'nullable|string|max:255',
            'batch_schedule' => 'nullable|string|max:255',
            'disable_coupons' => 'nullable|boolean',
        ]);

        // Use class_name or name
        if (isset($validated['name']) && !isset($validated['class_name'])) {
            $validated['class_name'] = $validated['name'];
        }
        unset($validated['name']);

        $classType->update($validated);

        // Clear class types cache
        self::clearCacheOnUpdate('class_types');

        return response()->json([
            'success' => true,
            'data' => $classType,
            'message' => 'Class type updated',
        ]);
    }

    /**
     * Delete class type - DELETE /api/admin/class-types/{id}
     */
    public function deleteClassType(Request $request, $id)
    {
        $classType = ClassType::findOrFail($id);
        $classType->delete();

        // Clear class types cache
        self::clearCacheOnUpdate('class_types');

        return response()->json([
            'success' => true,
            'message' => 'Class type deleted',
        ]);
    }

    /**
     * Reorder class types - POST /api/admin/class-types/reorder
     */
    public function reorderClassTypes(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|integer|exists:class_types,id',
            'order.*.display_order' => 'required|integer|min:1',
        ]);

        foreach ($validated['order'] as $item) {
            ClassType::where('id', $item['id'])
                ->update(['display_order' => $item['display_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Class types reordered successfully',
        ]);
    }

    /**
     * Get enrollment statistics - GET /api/admin/enrollment-stats
     */
    public function enrollmentStats(Request $request)
    {
        $totalEnrollments = Enrollment::count();
        $activeEnrollments = Enrollment::where('status', 'active')->count();
        $completedEnrollments = Enrollment::where('status', 'completed')->count();
        $cancelledEnrollments = Enrollment::where('status', 'cancelled')->count();
        $pendingEnrollments = Enrollment::where('status', 'pending')->count();

        // Get top courses by enrollment count
        $topCourses = Enrollment::select('course_id')
            ->selectRaw('COUNT(*) as enrollments_count')
            ->whereNotNull('course_id')
            ->groupBy('course_id')
            ->orderBy('enrollments_count', 'desc')
            ->limit(5)
            ->with('course:id,course_title')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->course_id,
                    'name' => $item->course ? ($item->course->course_title || $item->course->title) : 'Unknown Course',
                    'enrollments' => $item->enrollments_count
                ];
            });

        // Get enrollment trend (this month vs last month)
        $now = now();
        $thisMonthStart = $now->copy()->startOfMonth();
        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();

        $thisMonthEnrollments = Enrollment::where('created_at', '>=', $thisMonthStart)->count();
        $lastMonthEnrollments = Enrollment::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        
        $growth = $lastMonthEnrollments > 0 
            ? round((($thisMonthEnrollments - $lastMonthEnrollments) / $lastMonthEnrollments) * 100) 
            : ($thisMonthEnrollments > 0 ? 100 : 0);

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $totalEnrollments,
                'active' => $activeEnrollments,
                'completed' => $completedEnrollments,
                'cancelled' => $cancelledEnrollments,
                'pending' => $pendingEnrollments,
                'top_courses' => $topCourses,
                'trend' => [
                    'this_month' => $thisMonthEnrollments,
                    'last_month' => $lastMonthEnrollments,
                    'growth' => $growth
                ]
            ],
            'message' => 'Enrollment statistics',
        ]);
    }

    // PAYMENT MANAGEMENT (4 methods)

    /**
     * Get all payments - GET /api/admin/payments
     */
    public function payments(Request $request)
    {
        $payments = Payment::with('user')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $payments,
            'message' => 'All payments',
        ]);
    }

    /**
     * Get payment detail - GET /api/admin/payments/{id}
     */
    public function paymentDetail(Request $request, $id)
    {
        $payment = Payment::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $payment,
            'message' => 'Payment detail',
        ]);
    }

    /**
     * Get subscriptions - GET /api/admin/subscriptions
     */
    public function subscriptions(Request $request)
    {
        // TODO: Implement subscription logic

        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Subscriptions',
        ]);
    }

    /**
     * Update payment status - PUT /api/admin/payments/{id}/status
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string|in:pending,completed,failed,refunded',
        ]);

        $payment->status = $validated['status'];
        $payment->save();

        return response()->json([
            'success' => true,
            'data' => $payment,
            'message' => 'Payment status updated',
        ]);
    }

    // COUPON MANAGEMENT (4 methods)

    /**
     * Get all coupons - GET /api/admin/coupons
     */
    public function coupons(Request $request)
    {
        try {
            $coupons = Coupon::with('classTypes')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $coupons,
            'message' => 'All coupons',
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Failed to load coupons: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store coupon - POST /api/admin/coupons
     */
    public function storeCoupon(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'code' => 'required|string|unique:coupons',
            'discount_type' => 'required|string|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'usage_limit' => 'nullable|integer|min:0',
            'duration' => 'nullable|string|in:once,forever,repeating',
            'duration_in_months' => 'nullable|integer|min:1|max:24',
            'class_type_ids' => 'nullable|array',
            'class_type_ids.*' => 'exists:class_types,id',
        ]);

        $classTypeIds = $validated['class_type_ids'] ?? [];
        unset($validated['class_type_ids']);

        // Set default duration if not provided
        if (!isset($validated['duration'])) {
            $validated['duration'] = 'once';
        }

        // Clear duration_in_months if not repeating
        if ($validated['duration'] !== 'repeating') {
            $validated['duration_in_months'] = null;
        }

        $coupon = Coupon::create($validated);

        // Sync class types (empty array means applies to all)
        if (!empty($classTypeIds)) {
            $coupon->classTypes()->sync($classTypeIds);
        }

        return response()->json([
            'success' => true,
            'data' => $coupon->load('classTypes'),
            'message' => 'Coupon created',
        ], 201);
    }

    /**
     * Update coupon - PUT /api/admin/coupons/{id}
     */
    public function updateCoupon(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'code' => 'nullable|string|unique:coupons,code,' . $id,
            'discount_type' => 'nullable|string|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'usage_limit' => 'nullable|integer|min:0',
            'duration' => 'nullable|string|in:once,forever,repeating',
            'duration_in_months' => 'nullable|integer|min:1|max:24',
            'class_type_ids' => 'nullable|array',
            'class_type_ids.*' => 'exists:class_types,id',
        ]);

        $classTypeIds = $validated['class_type_ids'] ?? null;
        unset($validated['class_type_ids']);

        // Clear duration_in_months if not repeating
        if (isset($validated['duration']) && $validated['duration'] !== 'repeating') {
            $validated['duration_in_months'] = null;
        }

        $coupon->update($validated);

        // Sync class types if provided (null means don't change, empty array clears)
        if ($classTypeIds !== null) {
            $coupon->classTypes()->sync($classTypeIds);
        }

        return response()->json([
            'success' => true,
            'data' => $coupon->load('classTypes'),
            'message' => 'Coupon updated',
        ]);
    }

    /**
     * Delete coupon - DELETE /api/admin/coupons/{id}
     */
    public function deleteCoupon(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return response()->json([
            'success' => true,
            'message' => 'Coupon deleted',
        ]);
    }

    // PAGE MANAGEMENT (5 methods)

    /**
     * Get all pages - GET /api/admin/pages
     */
    public function pages(Request $request)
    {
        try {
            $query = Page::query();

            // Apply search filter
            if ($request->has('search') && $request->search) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('slug', 'LIKE', "%{$searchTerm}%");
                });
            }

            // Apply status filter
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            $pages = $query->orderBy('created_at', 'desc')->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $pages,
                'message' => 'All pages',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching pages: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load pages',
            ], 500);
        }
    }

    /**
     * Get page detail - GET /api/admin/pages/{id}
     */
    public function pageDetail(Request $request, $id)
    {
        try {
            $page = Page::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $page,
                'message' => 'Page detail',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching page detail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Page not found',
            ], 404);
        }
    }

    /**
     * Store page - POST /api/admin/pages
     */
    public function storePage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'status' => 'required|string|in:draft,published',
            'content' => 'nullable|string',
            'seo_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'no_index' => 'boolean',
            'no_follow' => 'boolean',
            'schema_type' => 'nullable|string|max:50',
        ]);

        // Ensure slug starts with forward slash
        if (!str_starts_with($validated['slug'], '/')) {
            $validated['slug'] = '/' . $validated['slug'];
        }

        $page = Page::create($validated);

        return response()->json([
            'success' => true,
            'data' => $page,
            'message' => 'Page created successfully',
        ], 201);
    }

    /**
     * Update page - PUT /api/admin/pages/{id}
     */
    public function updatePage(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $id,
            'status' => 'nullable|string|in:draft,published',
            'content' => 'nullable|string',
            'seo_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'no_index' => 'boolean',
            'no_follow' => 'boolean',
            'schema_type' => 'nullable|string|max:50',
        ]);

        // Ensure slug starts with forward slash if provided
        if (isset($validated['slug']) && !str_starts_with($validated['slug'], '/')) {
            $validated['slug'] = '/' . $validated['slug'];
        }

        $page->update($validated);

        return response()->json([
            'success' => true,
            'data' => $page,
            'message' => 'Page updated successfully',
        ]);
    }

    /**
     * Delete page - DELETE /api/admin/pages/{id}
     */
    public function deletePage(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return response()->json([
            'success' => true,
            'message' => 'Page deleted successfully',
        ]);
    }

    /**
     * Get page by slug - GET /api/pages/by-slug?slug=/page-slug (PUBLIC)
     */
    public function getPageBySlug(Request $request)
    {
        $slug = $request->query('slug');

        if (!$slug) {
            return response()->json([
                'success' => false,
                'message' => 'Slug parameter is required',
            ], 400);
        }

    try {
        // Debug: Log all published pages
        $allPages = Page::where('status', 'published')->pluck('slug')->toArray();
        \Log::info('All published page slugs in database', ['pages' => $allPages]);
        
        // Create all possible slug variations to match
        $slugVariations = [];
        
        // Original slug
        $slugVariations[] = $slug;
        
        // With leading slash
        if (!str_starts_with($slug, '/')) {
            $slugVariations[] = '/' . $slug;
        }
        
        // Without leading slash
        if (str_starts_with($slug, '/')) {
            $slugVariations[] = ltrim($slug, '/');
        }
        
        // Without trailing slash
        if (str_ends_with($slug, '/') && $slug !== '/') {
            $slugVariations[] = rtrim($slug, '/');
            // Also add version with leading slash removed AND trailing slash removed
            $noSlashes = trim($slug, '/');
            if ($noSlashes) {
                $slugVariations[] = $noSlashes;
                $slugVariations[] = '/' . $noSlashes;
            }
        }
        
        // With trailing slash
        if (!str_ends_with($slug, '/')) {
            $slugVariations[] = $slug . '/';
            if (str_starts_with($slug, '/')) {
                $slugVariations[] = ltrim($slug, '/') . '/';
            }
        }
        
        // Remove duplicates
        $slugVariations = array_unique($slugVariations);
        
        \Log::info('Searching for page with slug variations', [
            'requested_slug' => $slug,
            'variations' => $slugVariations,
            'available_pages' => $allPages
        ]);

        $page = Page::whereIn('slug', $slugVariations)
            ->where('status', 'published')
            ->first();
            
        if (!$page) {
            \Log::error('No page matched any variation', [
                'searched_for' => $slugVariations,
                'database_has' => $allPages
            ]);
            throw new \Exception('Page not found');
        }
        
        \Log::info('Page found!', ['matched_slug' => $page->slug]);

        return response()->json([
            'success' => true,
            'data' => $page,
            'message' => 'Page found',
        ]);
    } catch (\Exception $e) {
        \Log::error('Page not found final error', [
            'slug' => $slug,
            'error' => $e->getMessage()
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Page not found',
        ], 404);
    }
    }

    // SETTINGS MANAGEMENT (4 methods)

    /**
     * Get public settings (no auth required) - GET /api/settings/public
     */
    /**
     * Get student portal maintenance status - GET /api/student-portal/maintenance-status
     * Public endpoint to check if student portal is under maintenance
     */
    public function studentPortalMaintenanceStatus(Request $request)
    {
        $maintenanceMode = Settings::where('key', 'student_portal_maintenance_mode')->first();
        $maintenanceMessage = Settings::where('key', 'student_portal_maintenance_message')->first();

        $isEnabled = $maintenanceMode && $maintenanceMode->value === 'true';
        $message = $maintenanceMessage ? $maintenanceMessage->value : 'The student portal is currently under maintenance. Please try again later.';

        return response()->json([
            'success' => true,
            'data' => [
                'maintenance_mode' => $isEnabled,
                'message' => $isEnabled ? $message : null,
            ],
            'message' => 'Maintenance status retrieved',
        ]);
    }

    public function publicSettings(Request $request)
    {
        $cacheKey = 'public_settings';
        $cacheDuration = self::getCacheDuration('settings');

        if ($cacheDuration) {
            $settings = Cache::remember($cacheKey, $cacheDuration, function () {
                return Settings::whereIn('key', [
                    'site_name',
                    'site_url',
                    'site_logo',
                    'books_page_seo_title',
                    'books_page_meta_description',
                    'books_page_no_index',
                    'books_page_no_follow',
                    'footer_settings'
                ])
                    ->get()
                    ->pluck('value', 'key');
            });
        } else {
            $settings = Settings::whereIn('key', [
                'site_name',
                'site_url',
                'site_logo',
                'books_page_seo_title',
                'books_page_meta_description',
                'books_page_no_index',
                'books_page_no_follow',
                'footer_settings'
            ])
                ->get()
                ->pluck('value', 'key');
        }

        return response()->json([
            'success' => true,
            'data' => $settings,
            'message' => 'Public Settings',
        ]);
    }

    /**
     * Get all settings - GET /api/admin/settings
     */
    public function settings(Request $request)
    {
        $settings = Settings::all()->pluck('value', 'key');

        return response()->json([
            'success' => true,
            'data' => $settings,
            'message' => 'Settings',
        ]);
    }

    /**
     * Upload site logo - POST /api/admin/settings/upload-logo
     */
    public function uploadLogo(Request $request)
    {
        $validated = $request->validate([
            'logo' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $oldLogo = Settings::where('key', 'site_logo')->value('value');
            if ($oldLogo && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldLogo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('logo')->store('settings', 'public');
            
            Settings::updateOrCreate(
                ['key' => 'site_logo'],
                ['value' => $path]
            );

            return response()->json([
                'success' => true,
                'data' => ['site_logo' => $path],
                'message' => 'Logo uploaded successfully',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No file uploaded',
        ], 400);
    }

    /**
     * Update setting - PUT /api/admin/settings/{key}
     */
    public function updateSetting(Request $request, $key)
    {
        $validated = $request->validate([
            'value' => 'required|string',
        ]);

        $setting = Settings::where('key', $key)->first();

        if ($setting) {
            $setting->value = $validated['value'];
            $setting->save();
        } else {
            Settings::create([
                'key' => $key,
                'value' => $validated['value'],
            ]);
        }

        // Clear settings cache
        self::clearCacheOnUpdate('settings');

        return response()->json([
            'success' => true,
            'message' => 'Setting updated',
        ]);
    }

    /**
     * Get email settings - GET /api/admin/email-settings
     */
    public function emailSettings(Request $request)
    {
        // Read directly from .env file to get latest values (bypass config cache)
        $envPath = base_path('.env');
        $settings = [
            'smtp_host' => '',
            'smtp_port' => 587,
            'smtp_username' => '',
            'smtp_password' => '',
            'from_email' => '',
            'from_name' => '',
            'google_client_id' => '',
            'google_client_secret' => '',
            'google_redirect_uri' => '',
            'google_access_token' => '',
            'google_refresh_token' => '',
            'google_from_email' => '',
            'google_from_name' => '',
        ];
        
        if (File::exists($envPath)) {
            $envContent = File::get($envPath);
            
            // Extract SMTP values from .env file
            if (preg_match('/^MAIL_SMTP_HOST=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $settings['smtp_host'] = trim($matches[1], ' "\'');
            }
            if (preg_match('/^MAIL_SMTP_PORT=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $settings['smtp_port'] = (int)trim($matches[1], ' "\'') ?: 587;
            }
            if (preg_match('/^MAIL_SMTP_USERNAME=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $settings['smtp_username'] = trim($matches[1], ' "\'');
            }
            if (preg_match('/^MAIL_SMTP_PASSWORD=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $settings['smtp_password'] = trim($matches[1], ' "\'');
            }
            if (preg_match('/^MAIL_FROM_ADDRESS=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $settings['from_email'] = trim($matches[1], ' "\'');
            }
            if (preg_match('/^MAIL_FROM_NAME=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $settings['from_name'] = trim($matches[1], ' "\'');
            }
            
            // Extract Google OAuth values from .env file
            if (preg_match('/^GOOGLE_CLIENT_ID=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $settings['google_client_id'] = trim($matches[1], ' "\'');
            }
            if (preg_match('/^GOOGLE_CLIENT_SECRET=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $settings['google_client_secret'] = trim($matches[1], ' "\'');
            }
            if (preg_match('/^GOOGLE_REDIRECT_URI=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $settings['google_redirect_uri'] = trim($matches[1], ' "\'');
            }
            if (preg_match('/^GOOGLE_ACCESS_TOKEN=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $settings['google_access_token'] = trim($matches[1], ' "\'');
            }
            if (preg_match('/^GOOGLE_REFRESH_TOKEN=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $settings['google_refresh_token'] = trim($matches[1], ' "\'');
            }
            if (preg_match('/^GOOGLE_FROM_EMAIL=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $settings['google_from_email'] = trim($matches[1], ' "\'');
            }
            if (preg_match('/^GOOGLE_FROM_NAME=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $settings['google_from_name'] = trim($matches[1], ' "\'');
            }
        }

        // FALLBACK: If Google tokens are empty in .env (e.g. after production build), check database
        if (empty($settings['google_access_token'])) {
            $dbToken = Settings::where('key', 'google_access_token')->first();
            if ($dbToken) $settings['google_access_token'] = $dbToken->value;
        }
        if (empty($settings['google_refresh_token'])) {
            $dbRefresh = Settings::where('key', 'google_refresh_token')->first();
            if ($dbRefresh) $settings['google_refresh_token'] = $dbRefresh->value;
        }
        if (empty($settings['google_client_id'])) {
            $dbClientId = Settings::where('key', 'google_client_id')->first();
            if ($dbClientId) $settings['google_client_id'] = $dbClientId->value;
        }
        if (empty($settings['google_client_secret'])) {
            $dbClientSecret = Settings::where('key', 'google_client_secret')->first();
            if ($dbClientSecret) $settings['google_client_secret'] = $dbClientSecret->value;
        }

        // Don't return password in response for security
        unset($settings['smtp_password']);

        return response()->json([
            'success' => true,
            'data' => (object)$settings,
            'message' => 'Email settings',
        ]);
    }

    /**
     * Update email settings - PUT /api/admin/email-settings
     */
    public function updateEmailSettings(Request $request)
    {
        $validated = $request->validate([
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'from_email' => 'nullable|email|max:255',
            'from_name' => 'nullable|string|max:255',
            'google_client_id' => 'nullable|string|max:255',
            'google_client_secret' => 'nullable|string|max:255',
            'google_redirect_uri' => 'nullable|url|max:500',
            'google_access_token' => 'nullable|string',
            'google_refresh_token' => 'nullable|string',
            'google_from_email' => 'nullable|email|max:255',
            'google_from_name' => 'nullable|string|max:255',
        ]);

        try {
            $envPath = base_path('.env');
            
            if (!File::exists($envPath)) {
                return response()->json([
                    'success' => false,
                    'message' => '.env file not found. Please create it first.',
                ], 500);
            }

            $envContent = File::get($envPath);

            // Remove spaces from password (Gmail app passwords shouldn't have spaces)
            if (isset($validated['smtp_password'])) {
                $validated['smtp_password'] = preg_replace('/\s+/', '', $validated['smtp_password']);
            }

            // Set redirect URI if not provided
            if (!isset($validated['google_redirect_uri']) || empty($validated['google_redirect_uri'])) {
                $validated['google_redirect_uri'] = url('/api/admin/google-oauth/callback');
            }

            // Map validated data to .env keys
            $envVars = [];
            
            if (isset($validated['smtp_host'])) {
                $envVars['MAIL_SMTP_HOST'] = $validated['smtp_host'];
            }
            if (isset($validated['smtp_port'])) {
                $envVars['MAIL_SMTP_PORT'] = (string)$validated['smtp_port'];
            }
            if (isset($validated['smtp_username'])) {
                $envVars['MAIL_SMTP_USERNAME'] = $validated['smtp_username'];
            }
            if (isset($validated['smtp_password'])) {
                $envVars['MAIL_SMTP_PASSWORD'] = $validated['smtp_password'];
            }
            if (isset($validated['from_email'])) {
                $envVars['MAIL_FROM_ADDRESS'] = $validated['from_email'];
            }
            if (isset($validated['from_name'])) {
                $envVars['MAIL_FROM_NAME'] = $validated['from_name'];
            }
            if (isset($validated['google_client_id'])) {
                $envVars['GOOGLE_CLIENT_ID'] = $validated['google_client_id'];
            }
            // Only update google_client_secret if provided and not empty/null
            // Don't update if it's null (means user didn't change it, keep existing value)
            if (isset($validated['google_client_secret']) && 
                $validated['google_client_secret'] !== '' && 
                $validated['google_client_secret'] !== null) {
                $envVars['GOOGLE_CLIENT_SECRET'] = $validated['google_client_secret'];
            }
            if (isset($validated['google_redirect_uri'])) {
                $envVars['GOOGLE_REDIRECT_URI'] = $validated['google_redirect_uri'];
            }
            // Handle Google OAuth tokens - null means "remove/delete"
            if (array_key_exists('google_access_token', $validated)) {
                if ($validated['google_access_token'] === null) {
                    // Mark for deletion
                    $envVars['GOOGLE_ACCESS_TOKEN'] = '__DELETE__';
                } else {
                    $envVars['GOOGLE_ACCESS_TOKEN'] = $validated['google_access_token'];
                }
            }
            if (array_key_exists('google_refresh_token', $validated)) {
                if ($validated['google_refresh_token'] === null) {
                    // Mark for deletion
                    $envVars['GOOGLE_REFRESH_TOKEN'] = '__DELETE__';
                } else {
                    $envVars['GOOGLE_REFRESH_TOKEN'] = $validated['google_refresh_token'];
                }
            }
            if (isset($validated['google_from_email'])) {
                $envVars['GOOGLE_FROM_EMAIL'] = $validated['google_from_email'];
            }
            if (isset($validated['google_from_name'])) {
                $envVars['GOOGLE_FROM_NAME'] = $validated['google_from_name'];
            }

            // Update .env file
            foreach ($envVars as $key => $value) {
                // Handle deletion marker
                if ($value === '__DELETE__') {
                    // Remove the line from .env
                    $pattern = '/^' . preg_quote($key, '/') . '=.*?(?:\r?\n|$)/m';
                    $envContent = preg_replace($pattern, '', $envContent);
                    continue;
                }
                
                // Skip if null or empty string (empty string means "don't update")
                if ($value === null || (is_string($value) && trim($value) === '')) {
                    continue;
                }

                // Escape value if it contains special characters or quotes
                $escapedValue = $value;
                // Only escape if it contains spaces, #, or quotes
                if (preg_match('/[\s#"]/', $value)) {
                    // Escape quotes and wrap in quotes
                    $escapedValue = '"' . str_replace(['"', '\\'], ['\"', '\\\\'], $value) . '"';
                }
                
                // Check if key exists in .env - match entire line including long values
                $pattern = '/^' . preg_quote($key, '/') . '=.*?(?:\r?\n|$)/m';
                
                if (preg_match($pattern, $envContent)) {
                    // Update existing key - replace entire matched line
                    $newline = (strpos($envContent, "\r\n") !== false) ? "\r\n" : "\n";
                    // Use callback to avoid backreference issues with $ and \ in secrets
                    $envContent = preg_replace_callback($pattern, function() use ($key, $escapedValue, $newline) {
                        return $key . '=' . $escapedValue . $newline;
                    }, $envContent, 1);
                } else {
                    // Add new key at the end
                    if (substr($envContent, -1) !== "\n" && substr($envContent, -1) !== "\r\n") {
                        $envContent .= "\n";
                    }
                    $envContent .= $key . '=' . $escapedValue . "\n";
                }
            }

            // Write back to .env file
            if (!is_writable($envPath)) {
                return response()->json([
                    'success' => false,
                    'message' => '.env file is not writable. Please check file permissions.',
                ], 500);
            }
            
            File::put($envPath, $envContent);

            // ALSO update database fallback for Google OAuth tokens
            $dbMappings = [
                'GOOGLE_CLIENT_ID' => 'google_client_id',
                'GOOGLE_CLIENT_SECRET' => 'google_client_secret',
                'GOOGLE_ACCESS_TOKEN' => 'google_access_token',
                'GOOGLE_REFRESH_TOKEN' => 'google_refresh_token',
            ];

            foreach ($dbMappings as $envKey => $dbKey) {
                if (isset($envVars[$envKey])) {
                    if ($envVars[$envKey] === '__DELETE__') {
                        Settings::where('key', $dbKey)->delete();
                    } else {
                        Settings::updateOrCreate(['key' => $dbKey], ['value' => (string)$envVars[$envKey]]);
                    }
                }
            }
            
            // Clear config cache to reload .env values
            try {
                \Artisan::call('config:clear');
            } catch (\Exception $e) {
                \Log::warning('Failed to clear config cache: ' . $e->getMessage());
            }

            // Return updated settings (without password)
            $returnSettings = $envVars;
            unset($returnSettings['MAIL_SMTP_PASSWORD']);
            unset($returnSettings['GOOGLE_CLIENT_SECRET']);

            return response()->json([
                'success' => true,
                'data' => (object)$returnSettings,
                'message' => 'Email settings updated successfully in .env and database (fallback)',
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to update email settings', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update email settings: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test SMTP connection - POST /api/admin/test-smtp-connection
     */
    public function testSmtpConnection(Request $request)
    {
        // Read from .env file
        $envPath = base_path('.env');
        $smtpHost = '';
        $smtpPort = 587;
        $smtpUsername = '';
        
        if (File::exists($envPath)) {
            $envContent = File::get($envPath);
            if (preg_match('/^MAIL_SMTP_HOST=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $smtpHost = trim($matches[1], ' "\'');
            }
            if (preg_match('/^MAIL_SMTP_PORT=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $smtpPort = (int)trim($matches[1], ' "\'') ?: 587;
            }
            if (preg_match('/^MAIL_SMTP_USERNAME=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $smtpUsername = trim($matches[1], ' "\'');
            }
        }

        if (!$smtpHost || !$smtpUsername) {
            return response()->json([
                'success' => false,
                'message' => 'SMTP settings not configured. Please fill in all required fields and save first.',
            ], 400);
        }

        try {
            // For Laravel 10+ with Symfony Mailer, test connection using socket
            $host = $smtpHost;
            $port = $smtpPort;
            $timeout = 5;
            
            // Test basic socket connection
            $connection = @fsockopen($host, $port, $errno, $errstr, $timeout);
            
            if (!$connection) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot connect to SMTP server {$host}:{$port}. Error: {$errstr} ({$errno})",
                ], 400);
            }
            
            fclose($connection);
            
            // If we get here, connection was successful
            return response()->json([
                'success' => true,
                'message' => 'SMTP connection successful! Server is reachable at ' . $host . ':' . $port,
            ]);
        } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
            return response()->json([
                'success' => false,
                'message' => 'SMTP connection failed: ' . $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test email - POST /api/admin/test-email
     */
    public function testEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'nullable|email',
            'use_google_oauth' => 'nullable|boolean',
        ]);

        // Read from .env file
        $envPath = base_path('.env');
        $settings = [
            'smtp_host' => '',
            'smtp_port' => 587,
            'smtp_username' => '',
            'smtp_password' => '',
            'from_email' => '',
            'from_name' => '',
            'google_access_token' => '',
            'google_from_email' => '',
            'google_from_name' => '',
        ];
        
        if (File::exists($envPath)) {
            $envContent = File::get($envPath);
            if (preg_match('/^MAIL_SMTP_HOST=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $settings['smtp_host'] = trim($matches[1], ' "\'');
            }
            if (preg_match('/^MAIL_SMTP_PORT=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $settings['smtp_port'] = (int)trim($matches[1], ' "\'') ?: 587;
            }
            if (preg_match('/^MAIL_SMTP_USERNAME=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $settings['smtp_username'] = trim($matches[1], ' "\'');
            }
            if (preg_match('/^MAIL_SMTP_PASSWORD=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $settings['smtp_password'] = trim($matches[1], ' "\'');
            }
            if (preg_match('/^MAIL_FROM_ADDRESS=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $settings['from_email'] = trim($matches[1], ' "\'');
            }
            if (preg_match('/^MAIL_FROM_NAME=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $settings['from_name'] = trim($matches[1], ' "\'');
            }
            if (preg_match('/^GOOGLE_ACCESS_TOKEN=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $settings['google_access_token'] = trim($matches[1], ' "\'');
            }
            if (preg_match('/^GOOGLE_FROM_EMAIL=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $settings['google_from_email'] = trim($matches[1], ' "\'');
            }
            if (preg_match('/^GOOGLE_FROM_NAME=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $settings['google_from_name'] = trim($matches[1], ' "\'');
            }
        }

        $useGoogleOAuth = $validated['use_google_oauth'] ?? false;
        
        // Check if using Google OAuth
        if ($useGoogleOAuth) {
            try {
                $testEmail = $validated['email'] ?? $settings['google_from_email'] ?? $settings['smtp_username'];
                
                if (!$testEmail) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please provide an email address or configure From Email in Google settings.',
                    ], 400);
                }

                $appName = config('app.name', 'FocusFrame');
                $emailBody = "This is a test email from your Laravel application. If you received this, your Google OAuth configuration is working correctly!";

                $emailSent = \App\Services\GoogleMailService::sendEmail(
                    $testEmail, 
                    "{$appName} - Test Email (Google OAuth Configuration)", 
                    $emailBody
                );

                if (!$emailSent) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to send email via Google OAuth. Please check your logs for details.',
                    ], 400);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Test email sent successfully to ' . $testEmail . ' via Google OAuth',
                ]);
            } catch (\Exception $e) {
                \Log::error('Google OAuth email test failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send test email via Google OAuth: ' . $e->getMessage(),
                ], 500);
            }
        }

        // SMTP email sending (existing code)
        if (!$settings['smtp_host'] || !$settings['smtp_username']) {
            return response()->json([
                'success' => false,
                'message' => 'SMTP settings not configured. Please configure and save your email settings first.',
            ], 400);
        }

        try {
            // Configure mail settings dynamically
            config([
                'mail.mailers.smtp.host' => $settings['smtp_host'],
                'mail.mailers.smtp.port' => $settings['smtp_port'] ?? 587,
                'mail.mailers.smtp.username' => $settings['smtp_username'],
                'mail.mailers.smtp.password' => $settings['smtp_password'],
                'mail.mailers.smtp.encryption' => $settings['smtp_port'] == 465 ? 'ssl' : 'tls',
                'mail.from.address' => $settings['from_email'] ?? $settings['smtp_username'],
                'mail.from.name' => $settings['from_name'] ?? 'Test',
            ]);

            $testEmail = $validated['email'] ?? $settings['from_email'] ?? $settings['smtp_username'];

            // Send test email
            \Illuminate\Support\Facades\Mail::raw('This is a test email from your Laravel application. If you received this, your SMTP configuration is working correctly!', function ($message) use ($testEmail) {
                $message->to($testEmail)
                        ->subject('Test Email - SMTP Configuration');
            });

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully to ' . $testEmail,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Google OAuth Authorize - POST /api/admin/google-oauth/authorize
     */
    public function googleOAuthAuthorize(Request $request)
    {
        // Read from .env file
        $envPath = base_path('.env');
        $googleClientId = '';
        $googleClientSecret = '';
        $googleRedirectUri = '';
        
        if (!File::exists($envPath)) {
            \Log::error('Google OAuth: .env file not found', ['path' => $envPath]);
            return response()->json([
                'success' => false,
                'message' => '.env file not found. Please check your configuration.',
            ], 500);
        }
        
        $envContent = '';
        try {
            if (File::exists($envPath)) {
                $envContent = File::get($envPath);
            }
        } catch (\Exception $e) {
            \Log::warning('Could not read .env file', ['error' => $e->getMessage()]);
        }
        
        $googleClientId = null;
        $googleClientSecret = null;
        $googleRedirectUri = null;
        
        if (preg_match('/^GOOGLE_CLIENT_ID=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
            $googleClientId = trim($matches[1], ' "\'');
        }
        if (preg_match('/^GOOGLE_CLIENT_SECRET=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
            $googleClientSecret = trim($matches[1], ' "\'');
        }
        
        // Fallback to request data if .env is not yet updated/reloaded
        if (!$googleClientId && $request->has('client_id')) {
            $googleClientId = $request->input('client_id');
        }
        if (!$googleClientSecret && $request->has('client_secret')) {
            $googleClientSecret = $request->input('client_secret');
        }
        
        if (preg_match('/^GOOGLE_REDIRECT_URI=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
            $googleRedirectUri = trim($matches[1], ' "\'');
        }
        
        // Allow redirect URI to be passed in request (frontend can override .env value)
        // #region agent log
        try {
            $debugPayload = [
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'J',
                'location' => 'AdminController.php:googleOAuthAuthorize:check_redirect_uri',
                'message' => 'Checking redirect URI in request',
                'data' => [
                    'has_redirect_uri_param' => $request->has('redirect_uri'),
                    'redirect_uri_from_request' => $request->input('redirect_uri'),
                    'redirect_uri_from_env' => $googleRedirectUri ?: 'not_set',
                ],
                'timestamp' => round(microtime(true) * 1000),
            ];
            @file_put_contents(base_path('.cursor/debug.log'), json_encode($debugPayload) . PHP_EOL, FILE_APPEND);
        } catch (\Throwable $e) {
            // Swallow any logging errors
        }
        // #endregion
        
        if ($request->has('redirect_uri')) {
            $googleRedirectUri = $request->input('redirect_uri');
        }
        
        // #region agent log
        try {
            $debugPayload = [
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'J',
                'location' => 'AdminController.php:googleOAuthAuthorize:final_redirect_uri',
                'message' => 'Final redirect URI being used',
                'data' => [
                    'final_redirect_uri' => $googleRedirectUri ?: 'default',
                ],
                'timestamp' => round(microtime(true) * 1000),
            ];
            @file_put_contents(base_path('.cursor/debug.log'), json_encode($debugPayload) . PHP_EOL, FILE_APPEND);
        } catch (\Throwable $e) {
            // Swallow any logging errors
        }
        // #endregion
        
        \Log::info('Google OAuth Authorize', [
            'has_client_id' => !empty($googleClientId),
            'has_client_secret' => !empty($googleClientSecret),
            'client_id_length' => strlen($googleClientId),
            'client_secret_length' => strlen($googleClientSecret),
            'source' => $request->has('client_id') ? 'request' : '.env',
            'redirect_uri' => $googleRedirectUri ?: 'default'
        ]);
        
        if (!$googleClientId || !$googleClientSecret) {
            return response()->json([
                'success' => false,
                'message' => 'Google OAuth credentials not configured. Please fill in Client ID and Client Secret first and save the settings.',
            ], 400);
        }

        // Generate state token for CSRF protection
        $state = Str::random(40);
        // Store state in cache for 10 minutes (enough time for OAuth flow)
        Cache::put('google_oauth_state_' . $state, true, 600);

        // Build Google OAuth authorization URL
        $redirectUri = $googleRedirectUri ?: url('/api/admin/google-oauth/callback');

        // Also temporarily store credentials in cache to survive restart/callback
        // This allows us to defer saving to .env until AFTER the callback, avoiding server restart during the flow
        Cache::put('google_oauth_creds_' . $state, [
            'client_id' => $googleClientId,
            'client_secret' => $googleClientSecret,
            'redirect_uri' => $redirectUri
        ], 600);
        
        // #region agent log
        try {
            $debugPayload = [
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'J',
                'location' => 'AdminController.php:googleOAuthAuthorize:building_auth_url',
                'message' => 'Building auth URL with redirect URI',
                'data' => [
                    'redirect_uri_used' => $redirectUri,
                ],
                'timestamp' => round(microtime(true) * 1000),
            ];
            @file_put_contents(base_path('.cursor/debug.log'), json_encode($debugPayload) . PHP_EOL, FILE_APPEND);
        } catch (\Throwable $e) {
            // Swallow any logging errors
        }
        // #endregion
        
        $params = [
            'client_id' => $googleClientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'https://www.googleapis.com/auth/gmail.send https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/admin.reports.audit.readonly',
            'access_type' => 'offline',
            'prompt' => 'consent',
            'state' => $state,
        ];

        \Log::info('Building Google OAuth URL', [
            'redirect_uri' => $redirectUri,
            'state' => $state,
        ]);

        $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
        
        // #region agent log
        try {
            $debugPayload = [
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'J',
                'location' => 'AdminController.php:googleOAuthAuthorize:auth_url_built',
                'message' => 'Auth URL built',
                'data' => [
                    'auth_url' => $authUrl,
                ],
                'timestamp' => round(microtime(true) * 1000),
            ];
            @file_put_contents(base_path('.cursor/debug.log'), json_encode($debugPayload) . PHP_EOL, FILE_APPEND);
        } catch (\Throwable $e) {
            // Swallow any logging errors
        }
        // #endregion

        \Log::info('Google OAuth URL built successfully', ['url_length' => strlen($authUrl)]);

        return response()->json([
            'success' => true,
            'auth_url' => $authUrl,
            'message' => 'Redirecting to Google OAuth...',
        ]);
    }

    /**
     * Google OAuth Callback - GET /api/admin/google-oauth/callback
     */
    public function googleOAuthCallback(Request $request)
    {
        // #region agent log
        try {
            $debugPayload = [
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'G',
                'location' => 'AdminController.php:googleOAuthCallback:entry',
                'message' => 'Google OAuth callback entered',
                'data' => [
                    'has_state' => $request->query('state') ? true : false,
                    'has_code' => $request->query('code') ? true : false,
                    'has_error' => $request->query('error') ? true : false,
                ],
                'timestamp' => round(microtime(true) * 1000),
            ];
            @file_put_contents(base_path('.cursor/debug.log'), json_encode($debugPayload) . PHP_EOL, FILE_APPEND);
        } catch (\Throwable $e) {
            // Swallow any logging errors
        }
        // #endregion

        // Get frontend URL from .env (FRONTEND_URL) or fallback to app url
        $envPath = base_path('.env');
        $frontendUrl = env('FRONTEND_URL', url('/'));
        
        // Remove trailing slash if exists
        $frontendUrl = rtrim($frontendUrl, '/');

        // Verify state token
        $state = $request->query('state');

        if (!$state || !Cache::has('google_oauth_state_' . $state)) {
            // #region agent log
            try {
                $debugPayload = [
                    'sessionId' => 'debug-session',
                    'runId' => 'run1',
                    'hypothesisId' => 'G',
                    'location' => 'AdminController.php:googleOAuthCallback:invalid_state',
                    'message' => 'Invalid or missing state token',
                    'data' => [
                        'has_state' => $state ? true : false,
                    ],
                    'timestamp' => round(microtime(true) * 1000),
                ];
                @file_put_contents(base_path('.cursor/debug.log'), json_encode($debugPayload) . PHP_EOL, FILE_APPEND);
            } catch (\Throwable $e) {
                // Swallow any logging errors
            }
            // #endregion

            return redirect($frontendUrl . '/admin/settings?error=invalid_state&message=' . urlencode('Invalid state parameter. Please try again.'));
        }

        // Clear state from cache
        Cache::forget('google_oauth_state_' . $state);

        $code = $request->query('code');
        $error = $request->query('error');

        if ($error) {
            // #region agent log
            try {
                $debugPayload = [
                    'sessionId' => 'debug-session',
                    'runId' => 'run1',
                    'hypothesisId' => 'G',
                    'location' => 'AdminController.php:googleOAuthCallback:error_param',
                    'message' => 'Error parameter received from Google',
                    'data' => [
                        'error' => $error,
                        // Do not log error_description as it might contain sensitive info
                    ],
                    'timestamp' => round(microtime(true) * 1000),
                ];
                @file_put_contents(base_path('.cursor/debug.log'), json_encode($debugPayload) . PHP_EOL, FILE_APPEND);
            } catch (\Throwable $e) {
                // Swallow any logging errors
            }
            // #endregion

            return redirect($frontendUrl . '/admin/settings?error=' . urlencode($error) . '&message=' . urlencode($request->query('error_description', 'OAuth authorization failed')));
        }

        if (!$code) {
            // #region agent log
            try {
                $debugPayload = [
                    'sessionId' => 'debug-session',
                    'runId' => 'run1',
                    'hypothesisId' => 'G',
                    'location' => 'AdminController.php:googleOAuthCallback:no_code',
                    'message' => 'Authorization code missing in callback',
                    'data' => [],
                    'timestamp' => round(microtime(true) * 1000),
                ];
                @file_put_contents(base_path('.cursor/debug.log'), json_encode($debugPayload) . PHP_EOL, FILE_APPEND);
            } catch (\Throwable $e) {
                // Swallow any logging errors
            }
            // #endregion

            return redirect($frontendUrl . '/admin/settings?error=no_code&message=' . urlencode('Authorization code not received from Google.'));
        }

        // Read from .env file
        $googleClientId = '';
        $googleClientSecret = '';
        $googleRedirectUri = '';
        
        if (File::exists($envPath)) {
            $envContent = File::get($envPath);
            if (preg_match('/^GOOGLE_CLIENT_ID=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $googleClientId = trim($matches[1], ' "\'');
            }
            if (preg_match('/^GOOGLE_CLIENT_SECRET=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $googleClientSecret = trim($matches[1], ' "\'');
            }
            if (preg_match('/^GOOGLE_REDIRECT_URI=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $googleRedirectUri = trim($matches[1], ' "\'');
            }
        }

        // Try to retrieve credentials from cache (if they were passed in request but not saved to .env)
        if ($state && Cache::has('google_oauth_creds_' . $state)) {
            $cachedCreds = Cache::get('google_oauth_creds_' . $state);
            if (empty($googleClientId) && !empty($cachedCreds['client_id'])) {
                $googleClientId = $cachedCreds['client_id'];
            }
            if (empty($googleClientSecret) && !empty($cachedCreds['client_secret'])) {
                $googleClientSecret = $cachedCreds['client_secret'];
            }
            // Also restore redirect URI if it was cached. 
            // We MUST use the same redirect URI that was used during the authorization request.
            if (!empty($cachedCreds['redirect_uri'])) {
                $googleRedirectUri = $cachedCreds['redirect_uri'];
            }
        }
        
        if (!$googleClientId || !$googleClientSecret) {
            // #region agent log
            try {
                $debugPayload = [
                    'sessionId' => 'debug-session',
                    'runId' => 'run1',
                    'hypothesisId' => 'H',
                    'location' => 'AdminController.php:googleOAuthCallback:no_credentials',
                    'message' => 'Google OAuth credentials not found in .env',
                    'data' => [
                        'has_client_id' => !empty($googleClientId),
                        'has_client_secret' => !empty($googleClientSecret),
                    ],
                    'timestamp' => round(microtime(true) * 1000),
                ];
                @file_put_contents(base_path('.cursor/debug.log'), json_encode($debugPayload) . PHP_EOL, FILE_APPEND);
            } catch (\Throwable $e) {
                // Swallow any logging errors
            }
            // #endregion

            return redirect($frontendUrl . '/admin/settings?error=no_credentials&message=' . urlencode('Google OAuth credentials not found.'));
        }

        try {
            $redirectUri = $googleRedirectUri ?: url('/api/admin/google-oauth/callback');

            // #region agent log
            try {
                $debugPayload = [
                    'sessionId' => 'debug-session',
                    'runId' => 'run1',
                    'hypothesisId' => 'H',
                    'location' => 'AdminController.php:googleOAuthCallback:before_token_exchange',
                    'message' => 'About to exchange authorization code for tokens',
                    'data' => [
                        'has_client_id' => !empty($googleClientId),
                        'has_client_secret' => !empty($googleClientSecret),
                        'redirect_uri' => $redirectUri,
                    ],
                    'timestamp' => round(microtime(true) * 1000),
                ];
                @file_put_contents(base_path('.cursor/debug.log'), json_encode($debugPayload) . PHP_EOL, FILE_APPEND);
            } catch (\Throwable $e) {
                // Swallow any logging errors
            }
            // #endregion

            // Exchange authorization code for access token
            // Disable SSL verification for local development (Windows/Laragon SSL certificate issue)
            // In production, SSL verification should be enabled
            $httpClient = config('app.env') === 'production' 
                ? Http::asForm() 
                : Http::withoutVerifying()->asForm();
            
            \Log::info('Google OAuth Token Exchange Request', [
                'client_id' => $googleClientId,
                'redirect_uri' => $redirectUri,
                'code_exists' => !empty($code),
            ]);

            $response = $httpClient->post('https://oauth2.googleapis.com/token', [
                'client_id' => $googleClientId,
                'client_secret' => $googleClientSecret,
                'code' => $code,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $redirectUri,
            ]);

            if (!$response->successful()) {
                // #region agent log
                try {
                    $debugPayload = [
                        'sessionId' => 'debug-session',
                        'runId' => 'run1',
                        'hypothesisId' => 'H',
                        'location' => 'AdminController.php:googleOAuthCallback:token_exchange_failed',
                        'message' => 'Google OAuth token exchange failed',
                        'data' => [
                            'status' => $response->status(),
                            // Do not log full response body to avoid sensitive data
                        ],
                        'timestamp' => round(microtime(true) * 1000),
                    ];
                    @file_put_contents(base_path('.cursor/debug.log'), json_encode($debugPayload) . PHP_EOL, FILE_APPEND);
                } catch (\Throwable $e) {
                    // Swallow any logging errors
                }
                // #endregion

                \Log::error('Google OAuth token exchange failed', [
                    'response' => $response->body(),
                    'status' => $response->status(),
                ]);

                return redirect($frontendUrl . '/admin/settings?error=token_exchange_failed&message=' . urlencode('Failed to exchange authorization code for access token. Please check your credentials.'));
            }

            $tokenData = $response->json();

            // #region agent log
            try {
                $debugPayload = [
                    'sessionId' => 'debug-session',
                    'runId' => 'run1',
                    'hypothesisId' => 'H',
                    'location' => 'AdminController.php:googleOAuthCallback:token_exchange_success',
                    'message' => 'Google OAuth token exchange succeeded',
                    'data' => [
                        'has_access_token' => isset($tokenData['access_token']),
                        'has_refresh_token' => isset($tokenData['refresh_token']),
                        'token_type' => $tokenData['token_type'] ?? null,
                    ],
                    'timestamp' => round(microtime(true) * 1000),
                ];
                @file_put_contents(base_path('.cursor/debug.log'), json_encode($debugPayload) . PHP_EOL, FILE_APPEND);
            } catch (\Throwable $e) {
                // Swallow any logging errors
            }
            // #endregion

                // Store tokens in .env file (Try-catch for production permissions)
                try {
                    if (File::exists($envPath) && File::isWritable($envPath)) {
                        $envContent = File::get($envPath);
                        
                        // Update/Add Google tokens and credentials in .env
                        $envVars = [
                            'GOOGLE_CLIENT_ID' => $googleClientId,
                            'GOOGLE_CLIENT_SECRET' => $googleClientSecret,
                            'GOOGLE_ACCESS_TOKEN' => $tokenData['access_token'] ?? null,
                        ];
                        
                        if (isset($tokenData['refresh_token'])) {
                            $envVars['GOOGLE_REFRESH_TOKEN'] = $tokenData['refresh_token'];
                        }

                        foreach ($envVars as $key => $value) {
                            if ($value === null) continue;

                            $pattern = '/^' . preg_quote($key, '/') . '=.*?(?:\r?\n|$)/m';
                            $newline = (strpos($envContent, "\r\n") !== false) ? "\r\n" : "\n";
                            
                            $escapedValue = $value;
                            if (preg_match('/[\s#"]/', $value)) {
                                $escapedValue = '"' . str_replace(['"', '\\'], ['\"', '\\\\'], $value) . '"';
                            }

                            if (preg_match($pattern, $envContent)) {
                                $envContent = preg_replace_callback($pattern, function() use ($key, $escapedValue, $newline) {
                                    return $key . '=' . $escapedValue . $newline;
                                }, $envContent, 1);
                            } else {
                                if (substr($envContent, -1) !== "\n" && substr($envContent, -1) !== "\r\n") {
                                    $envContent .= $newline;
                                }
                                $envContent .= $key . '=' . $escapedValue . $newline;
                            }
                        }
                        
                        File::put($envPath, $envContent);
                        
                        // Clear config cache safely
                        try {
                            \Artisan::call('config:clear');
                        } catch (\Exception $e) {
                            \Log::warning('Failed to clear config cache: ' . $e->getMessage());
                        }
                    } else {
                        \Log::warning('Google OAuth: .env file is not writable. Skipping .env update, but database will be updated.');
                    }
                } catch (\Exception $e) {
                    \Log::warning('Failed to update .env file: ' . $e->getMessage());
                }

                // ALSO store in database as a persistent fallback for production
                // We store the full set of credentials here so the Service can always recover
                foreach ([
                    'GOOGLE_CLIENT_ID' => $googleClientId,
                    'GOOGLE_CLIENT_SECRET' => $googleClientSecret,
                    'GOOGLE_ACCESS_TOKEN' => $tokenData['access_token'] ?? null,
                    'GOOGLE_REFRESH_TOKEN' => $tokenData['refresh_token'] ?? null,
                ] as $key => $value) {
                    if ($value === null) continue;
                    $dbKey = strtolower($key);
                    try {
                        Settings::updateOrCreate(['key' => $dbKey], ['value' => (string)$value]);
                    } catch (\Exception $e) {
                        \Log::error('Failed to save ' . $key . ' to Settings table: ' . $e->getMessage());
                    }
                }

                // Update EmailSettings table which is used by GoogleMeetService
                $emailSettings = EmailSettings::first() ?? new EmailSettings();
                $emailSettings->google_client_id = $googleClientId;
                $emailSettings->google_client_secret = $googleClientSecret;
                if (!empty($googleRedirectUri)) {
                    $emailSettings->google_redirect_uri = $googleRedirectUri;
                }
                
                if (isset($tokenData['access_token'])) {
                    $emailSettings->google_access_token = $tokenData['access_token'];
                }
                if (isset($tokenData['refresh_token'])) {
                    $emailSettings->google_refresh_token = $tokenData['refresh_token'];
                }
                $emailSettings->save();
            
            // #region agent log
            try {
                $debugPayload = [
                    'sessionId' => 'debug-session',
                    'runId' => 'run1',
                    'hypothesisId' => 'H',
                    'location' => 'AdminController.php:googleOAuthCallback:success_redirect',
                    'message' => 'Google OAuth callback completed successfully',
                    'data' => [
                        'frontend_url' => $frontendUrl,
                    ],
                    'timestamp' => round(microtime(true) * 1000),
                ];
                @file_put_contents(base_path('.cursor/debug.log'), json_encode($debugPayload) . PHP_EOL, FILE_APPEND);
            } catch (\Throwable $e) {
                // Swallow any logging errors
            }
            // #endregion

            return redirect($frontendUrl . '/admin/settings?success=oauth_success&message=' . urlencode('Google OAuth authentication successful! Access token has been saved.'));
        } catch (\Throwable $e) {
            \Log::error('Google OAuth callback error', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect($frontendUrl . '/admin/settings?error=oauth_error&message=' . urlencode('An error occurred during OAuth authentication: ' . $e->getMessage()));
        }
    }

    /**
     * Get Stripe settings - GET /api/admin/stripe-settings
     */
    public function stripeSettings(Request $request)
    {
        // Read directly from .env file to get latest values (bypass config cache)
        $envPath = base_path('.env');
        $publishableKey = '';
        $secretKey = '';
        $testMode = true;
        $liveMode = false;
        
        if (File::exists($envPath)) {
            $envContent = File::get($envPath);
            
            // Extract values directly from .env file - handle quoted and unquoted values
            // Match everything after = until end of line (including long keys)
            if (preg_match('/^STRIPE_PUBLISHABLE_KEY=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $publishableKey = trim($matches[1], ' "\'');
            }
            
            if (preg_match('/^STRIPE_SECRET_KEY=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                $secretKey = trim($matches[1], ' "\'');
            }
            
            if (preg_match('/^STRIPE_TEST_MODE=(.*)$/m', $envContent, $matches)) {
                $testMode = trim($matches[1], ' "\'') === 'true';
            }
            
            if (preg_match('/^STRIPE_LIVE_MODE=(.*)$/m', $envContent, $matches)) {
                $liveMode = trim($matches[1], ' "\'') === 'true';
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'stripe_publishable_key' => $publishableKey,
                'stripe_secret_key' => $secretKey ? '••••••••••••' : '', // Mask secret key for security
                'stripe_test_mode' => $testMode,
                'stripe_live_mode' => $liveMode,
            ],
            'message' => 'Stripe settings',
        ]);
    }

    /**
     * Update Stripe settings - PUT /api/admin/stripe-settings
     */
    public function updateStripeSettings(Request $request)
    {
        $validated = $request->validate([
            'stripe_publishable_key' => 'nullable|string',
            'stripe_secret_key' => 'nullable|string',
            'stripe_test_mode' => 'nullable|boolean',
            'stripe_live_mode' => 'nullable|boolean',
        ]);

        $envPath = base_path('.env');
        
        if (!File::exists($envPath)) {
            return response()->json([
                'success' => false,
                'message' => '.env file not found',
            ], 404);
        }

        // Read .env file
        $envContent = File::get($envPath);
        
        // Get current secret key to check if user is trying to update it
        $currentSecretKey = env('STRIPE_SECRET_KEY', '');
        $secretKeyToUpdate = $validated['stripe_secret_key'] ?? null;
        
        // If secret key is masked (contains dots) or empty, don't update it - keep existing value
        if ($secretKeyToUpdate !== null) {
            if (strpos($secretKeyToUpdate, '••••') !== false || trim($secretKeyToUpdate) === '') {
                $secretKeyToUpdate = null; // Keep existing value, don't update
            }
        }

        // Update or add Stripe settings
        // Only include values that are actually provided (not null/empty)
        $envVars = [];
        
        if (isset($validated['stripe_publishable_key']) && trim($validated['stripe_publishable_key']) !== '') {
            $envVars['STRIPE_PUBLISHABLE_KEY'] = trim($validated['stripe_publishable_key']);
        }
        
        if ($secretKeyToUpdate !== null && trim($secretKeyToUpdate) !== '') {
            $envVars['STRIPE_SECRET_KEY'] = trim($secretKeyToUpdate);
        }
        
        if (isset($validated['stripe_test_mode'])) {
            $envVars['STRIPE_TEST_MODE'] = $validated['stripe_test_mode'] ? 'true' : 'false';
        }
        
        if (isset($validated['stripe_live_mode'])) {
            $envVars['STRIPE_LIVE_MODE'] = $validated['stripe_live_mode'] ? 'true' : 'false';
        }

        foreach ($envVars as $key => $value) {
            // Skip if null or empty string (empty string means "don't update")
            if ($value === null || (is_string($value) && trim($value) === '')) {
                continue;
            }

            // Escape value if it contains special characters or quotes
            // Stripe keys can be long, so don't truncate them
            $escapedValue = $value;
            // Only escape if it contains spaces, #, or quotes
            if (preg_match('/[\s#"]/', $value)) {
                // Escape quotes and wrap in quotes
                $escapedValue = '"' . str_replace(['"', '\\'], ['\"', '\\\\'], $value) . '"';
            }
            
            // Check if key exists in .env - match entire line including long values
            // Use pattern that properly handles long keys by matching until end of line
            $pattern = '/^' . preg_quote($key, '/') . '=.*?(?:\r?\n|$)/m';
            
            if (preg_match($pattern, $envContent)) {
                // Update existing key - replace entire matched line
                // Preserve newline format
                $newline = (strpos($envContent, "\r\n") !== false) ? "\r\n" : "\n";
                $envContent = preg_replace($pattern, $key . '=' . $escapedValue . $newline, $envContent, 1);
            } else {
                // Add new key at the end
                if (substr($envContent, -1) !== "\n" && substr($envContent, -1) !== "\r\n") {
                    $envContent .= "\n";
                }
                $envContent .= $key . '=' . $escapedValue . "\n";
            }
        }

        // Write back to .env file
        try {
            // Ensure we have write permissions
            if (!is_writable($envPath)) {
                return response()->json([
                    'success' => false,
                    'message' => '.env file is not writable. Please check file permissions.',
                ], 500);
            }
            
            // Write to .env file - ensure we write the full content without truncation
            $bytesWritten = File::put($envPath, $envContent);
            
            if ($bytesWritten === false) {
                throw new \Exception('Failed to write to .env file');
            }
            
            // Verify the file was written correctly by reading it back
            $verifyContent = File::get($envPath);
            foreach ($envVars as $verifyKey => $verifyValue) {
                if (preg_match('/^' . preg_quote($verifyKey, '/') . '=(.*?)(?:\r?\n|$)/m', $verifyContent, $verifyMatches)) {
                    $savedValue = trim($verifyMatches[1], ' "\'');
                    if ($savedValue !== $verifyValue) {
                        \Log::error("Stripe key mismatch: {$verifyKey} - Expected length: " . strlen($verifyValue) . ", Saved length: " . strlen($savedValue));
                    }
                }
            }
            
            // Clear config cache to reload .env values
            try {
                \Artisan::call('config:clear');
            } catch (\Exception $e) {
                // Config clear failed, but that's okay - .env is updated
                \Log::warning('Failed to clear config cache: ' . $e->getMessage());
            }
            
        } catch (\Exception $e) {
            \Log::error('Failed to update .env file: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update .env file: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Stripe settings updated successfully',
        ]);
    }

    /**
     * Import enrollment data from Gravity Forms JSON export - POST /api/admin/import-enrollments
     */
    public function importEnrollments(Request $request)
    {
        try {
            $data = $request->input('data');

            if (!$data || !is_array($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid data format'
                ], 422);
            }

            $importedCount = 0;
            $skipped = 0;
            $duplicateEntryIds = 0;
            $missingClassTypeCount = 0;
            $usersToInsert = [];
            $enrollmentsToInsert = [];
            $course = Course::first();

            // If no course exists, create a default one
            if (!$course) {
                $course = Course::create([
                    'course_title' => 'Default Course',
                    'course_subtitle' => 'Bulk import',
                    'course_description' => 'Default course for bulk student imports',
                    'course_category' => 'General',
                    'course_language' => 'English',
                    'course_level' => 'Beginner',
                    'course_is_active' => true,
                ]);
            }

            // CRITICAL: Delete existing duplicates before import to avoid constraint violations
            $incomingEmails = [];
            foreach ($data as $entry) {
                if (is_array($entry) && !empty($entry['Email Address'])) {
                    $email = trim($entry['Email Address']);
                    if ($email) {
                        $incomingEmails[] = $email;
                    }
                }
            }
            $incomingEmails = array_unique($incomingEmails);

            if (!empty($incomingEmails)) {
                \Log::info('Import: Deleting existing users', ['emails' => $incomingEmails]);

                // Use raw SQL to disable FK checks and delete
                DB::statement('SET FOREIGN_KEY_CHECKS=0');

                // Find users first to see if they exist
                $userIds = User::whereIn('email', $incomingEmails)->pluck('id')->toArray();
                \Log::info('Import: Found user IDs', ['user_ids' => $userIds, 'count' => count($userIds)]);

                // If no users found, try case-insensitive search
                if (empty($userIds)) {
                    $usersRaw = DB::table('users')->whereIn('email', $incomingEmails)->get();
                    \Log::info('Import: Raw query result', ['count' => $usersRaw->count(), 'users' => $usersRaw->toArray()]);
                }

                if (!empty($userIds)) {
                    $deletedPayments = Payment::whereIn('enrollment_id',
                        Enrollment::whereIn('user_id', $userIds)->pluck('id')
                    )->delete();

                    $deletedEnrollments = Enrollment::whereIn('user_id', $userIds)->delete();

                    $deletedUsers = User::whereIn('id', $userIds)->delete();
                } else {
                    $deletedPayments = 0;
                    $deletedEnrollments = 0;
                    $deletedUsers = 0;
                }

                DB::statement('SET FOREIGN_KEY_CHECKS=1');

                \Log::info('Import: Cleanup complete', [
                    'emails' => count($incomingEmails),
                    'deleted_users' => $deletedUsers,
                    'deleted_enrollments' => $deletedEnrollments,
                    'deleted_payments' => $deletedPayments
                ]);
            }

            // Get existing users by email (for reuse, not for skipping)
            $existingUsers = User::where('user_type', 'student')->get()->keyBy('email');
            
            // Get existing entry_ids from database to prevent duplicate imports
            // Query enrollments where form_data contains entry_id
            $existingEntryIds = [];
            if (config('database.default') === 'mysql') {
                $existingEnrollments = DB::table('enrollments')
                    ->whereNotNull('form_data')
                    ->get();
                
                foreach ($existingEnrollments as $enrollment) {
                    $formData = json_decode($enrollment->form_data, true);
                    if (isset($formData['entry_id']) && !empty($formData['entry_id'])) {
                        $existingEntryIds[] = (string)$formData['entry_id'];
                    }
                }
            } else {
                // Fallback for other databases
                $existingEnrollments = Enrollment::whereNotNull('form_data')->get();
                foreach ($existingEnrollments as $enrollment) {
                    $formData = $enrollment->form_data;
                    if (isset($formData['entry_id']) && !empty($formData['entry_id'])) {
                        $existingEntryIds[] = (string)$formData['entry_id'];
                    }
                }
            }
            $existingEntryIdsSet = array_flip($existingEntryIds);
            
            // Track entry_ids processed in current batch to prevent duplicates within batch
            $processedEntryIdsInBatch = [];

            // First pass: Prepare data and validate (without hashing passwords yet)
            $firstEntryLogged = false;
            foreach ($data as $entry) {
                try {
                    if (!is_array($entry)) {
                        $skipped++;
                        continue;
                    }

                    // Log the first entry to debug field names
                    if (!$firstEntryLogged) {
                        \Log::info('DEBUG: First entry keys', ['keys' => array_keys($entry)]);
                        \Log::info('DEBUG: First entry sample data', ['entry' => $entry]);
                        $firstEntryLogged = true;
                    }

                    // Map Gravity Forms field names to standard form field names
                    // This ensures consistency with data from the registration form
                    
                    // Extract coupon code (format: "FRENCH50 (FRENCH50: -$ 50.00 CAD)" -> "FRENCH50")
                    $couponCodeRaw = isset($entry['Do You Have  a Coupon Code?']) ? trim($entry['Do You Have  a Coupon Code?'] ?? '') : '';
                    $couponCode = '';
                    if (!empty($couponCodeRaw)) {
                        // Extract just the code part (before parenthesis)
                        if (strpos($couponCodeRaw, ' ') !== false) {
                            $couponCode = trim(explode(' ', $couponCodeRaw)[0]);
                        } else {
                            $couponCode = $couponCodeRaw;
                        }
                    }
                    
                    // Get payment amount (use Payment Amount if available, otherwise use the commitment field)
                    $paymentAmount = 0;
                    if (isset($entry['Payment Amount']) && !empty($entry['Payment Amount'])) {
                        $paymentAmount = (float)($entry['Payment Amount']);
                    } elseif (isset($entry['No Commitment. Payment can be cancelled anytime upon notifying before processing.']) && !empty($entry['No Commitment. Payment can be cancelled anytime upon notifying before processing.'])) {
                        $paymentAmount = (float)($entry['No Commitment. Payment can be cancelled anytime upon notifying before processing.']);
                    }
                    
                    // Calculate discount amount from coupon if present
                    $discountAmount = 0;
                    if (!empty($couponCodeRaw) && preg_match('/-\$\s*([\d.]+)/', $couponCodeRaw, $matches)) {
                        $discountAmount = (float)$matches[1];
                    }
                    
                    // Normalize referral source (map common values to form values)
                    $referralSourceRaw = isset($entry['How Did You Hear About Us?']) ? trim($entry['How Did You Hear About Us?'] ?? '') : '';
                    $referralSource = '';
                    if (!empty($referralSourceRaw)) {
                        $referralMap = [
                            'friend' => 'friend-family',
                            'family' => 'friend-family',
                            'friend/family' => 'friend-family',
                            'instagram' => 'instagram',
                            'facebook' => 'facebook',
                            'google' => 'google',
                            'tiktok' => 'tiktok',
                            'youtube' => 'youtube',
                            'other' => 'other'
                        ];
                        $referralLower = strtolower($referralSourceRaw);
                        $referralSource = $referralMap[$referralLower] ?? strtolower(str_replace(' ', '-', $referralSourceRaw));
                    }
                    
                    // Normalize language levels to lowercase
                    $englishLevel = isset($entry['English Level']) ? strtolower(trim($entry['English Level'] ?? '')) : null;
                    $frenchLevel = isset($entry['Current French Level']) ? strtolower(trim($entry['Current French Level'] ?? '')) : null;
                    // Map "Complete Beginner" to "complete-beginner"
                    if ($frenchLevel === 'complete beginner') {
                        $frenchLevel = 'complete-beginner';
                    }
                    
                    // Get Entry Id from Gravity Forms (check for duplicates based on this)
                    $entryId = isset($entry['Entry Id']) ? trim($entry['Entry Id'] ?? '') : '';
                    
                    // Skip if entry_id is duplicate (either in database or already processed in this batch)
                    if (!empty($entryId)) {
                        $entryIdStr = (string)$entryId;
                        if (isset($existingEntryIdsSet[$entryIdStr]) || isset($processedEntryIdsInBatch[$entryIdStr])) {
                            $skipped++;
                            $duplicateEntryIds++;
                            \Log::info('Skipping duplicate entry_id', ['entry_id' => $entryId]);
                            continue;
                        }
                        // Mark as processed in batch
                        $processedEntryIdsInBatch[$entryIdStr] = true;
                    }
                    
                    $formData = [
                        'phone' => isset($entry['Phone Number']) ? (string)($entry['Phone Number'] ?? '') : null,
                        'city' => isset($entry['City']) ? trim($entry['City'] ?? '') : null,
                        'native_language' => isset($entry['Native Language']) ? strtolower(trim($entry['Native Language'] ?? '')) : null,
                        'english_level' => $englishLevel,
                        'french_level' => $frenchLevel,
                        'course_purpose' => isset($entry['Primary Purpose For Taking The Course']) ? trim($entry['Primary Purpose For Taking The Course'] ?? '') : null,
                        'special_request' => isset($entry['Special Request']) ? trim($entry['Special Request'] ?? '') : null,
                        'referral_source' => !empty($referralSource) ? $referralSource : null,
                        'coupon_code' => !empty($couponCode) ? $couponCode : null,
                        'discount_amount' => $discountAmount,
                        'final_amount' => $paymentAmount,
                        'availability' => isset($entry['Availability (for One-on-One Sessions)']) ? trim($entry['Availability (for One-on-One Sessions)'] ?? '') : (isset($entry['Availability']) ? trim($entry['Availability'] ?? '') : null),
                        'timezone' => isset($entry['Time Zone']) ? trim($entry['Time Zone'] ?? '') : null,
                        'entry_id' => !empty($entryId) ? $entryId : null,
                        '_imported' => true, // Flag to identify imported enrollments
                        '_import_source' => 'gravity_forms', // Track import source
                    ];
                    
                    // Remove null/empty values to keep it clean (but keep 0 values for amounts and flags)
                    $formData = array_filter($formData, function($value, $key) {
                        // Keep discount_amount, final_amount, entry_id, and import flags even if 0/false
                        if (in_array($key, ['discount_amount', 'final_amount', 'entry_id', 'availability', 'timezone', '_imported', '_import_source'])) {
                            return $value !== null;
                        }
                        return $value !== null && $value !== '';
                    }, ARRAY_FILTER_USE_BOTH);

                    // Map from Gravity Forms export column names to our fields
                    $first_name = isset($entry['Your Name (First)']) ? trim($entry['Your Name (First)'] ?? '') : '';
                    $last_name = isset($entry['Your Name (Last)']) ? trim($entry['Your Name (Last)'] ?? '') : '';
                    $email = isset($entry['Email Address']) ? trim($entry['Email Address'] ?? '') : '';
                    $username = isset($entry['Create a Username']) ? trim($entry['Create a Username'] ?? '') : '';
                    $phone = isset($entry['Phone Number']) ? (string)($entry['Phone Number'] ?? '') : '';

                    // Generate username if empty (use email prefix + timestamp)
                    if (empty($username) && !empty($email)) {
                        $emailPrefix = preg_replace('/[^a-zA-Z0-9]/', '', explode('@', $email)[0]);
                        $baseUsername = $emailPrefix . '_' . time() . '_' . rand(1000, 9999);
                        
                        // Ensure uniqueness by checking existing usernames
                        $counter = 0;
                        $username = $baseUsername;
                        while (isset($existingUsernamesSet[$username]) || in_array($username, array_column($usersToInsert, 'username'))) {
                            $username = $baseUsername . '_' . $counter;
                            $counter++;
                        }
                    }

                    $classType = '';
                    $classTypeRaw = '';
                    if (isset($entry['Classes Type']) && !empty($entry['Classes Type'])) {
                        $classTypeRaw = $entry['Classes Type'] ?? '';
                        
                        // Handle format: "2Hrs/Week One-on-One (299CAD/Month)|299"
                        // Extract the part before | if it exists
                        if (strpos($classTypeRaw, '|') !== false) {
                            $classType = trim(explode('|', $classTypeRaw)[0]);
                        } else {
                            $classType = trim($classTypeRaw);
                        }
                        
                        // Clean up: Remove any trailing spaces or special characters
                        $classType = trim($classType);
                    }

                    // Validate required fields (username is now auto-generated if empty)
                    if (empty($email) || empty($first_name) || empty($last_name)) {
                        \Log::warning('Skipping entry due to missing required fields', [
                            'email' => $email,
                            'first_name' => $first_name,
                            'last_name' => $last_name
                        ]);
                        $skipped++;
                        continue;
                    }

                    // Check if user already exists - if yes, reuse; if no, create new
                    $userExists = isset($existingUsers[$email]);
                    $userIdToUse = null;
                    
                    if ($userExists) {
                        // User exists - reuse it (don't create duplicate user)
                        $userIdToUse = $existingUsers[$email]->id;
                        \Log::info('Reusing existing user for enrollment', [
                            'email' => $email,
                            'user_id' => $userIdToUse,
                            'entry_id' => $entryId
                        ]);
                    } else {
                        // User doesn't exist - prepare to create new user
                        // Check if username already exists in database
                        $usernameExists = User::where('username', $username)->exists();
                        if ($usernameExists) {
                            // Generate unique username if conflict
                            $baseUsername = $username;
                            $counter = 0;
                            while (User::where('username', $username)->exists() || in_array($username, array_column($usersToInsert, 'username'))) {
                                $username = $baseUsername . '_' . $counter;
                                $counter++;
                            }
                        }
                        
                        // Prepare user data for batch insert (store plain password for now)
                        $usersToInsert[] = [
                            'name' => $first_name . ' ' . $last_name,
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            'email' => $email,
                            'phone' => $phone,
                            'username' => $username,
                            'password' => \Str::random(12),
                            'user_type' => 'student',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        
                        // Track email to prevent duplicate user creation in same batch
                        $existingUsers[$email] = (object)['id' => null, 'email' => $email]; // Placeholder, will be updated after insert
                    }

                    // Store class type for enrollment creation
                    // Track entries without class types
                    if (empty($classType)) {
                        $missingClassTypeCount++;
                        \Log::warning('Entry missing class type - enrollment will not be created', [
                            'email' => $email,
                            'entry_id' => $entryId
                        ]);
                    }
                    
                    if (!empty($classType) && $course) {
                        // Get payment information from JSON
                        $transactionId = isset($entry['Transaction Id']) ? trim($entry['Transaction Id'] ?? '') : '';
                        $paymentStatus = isset($entry['Payment Status']) ? trim($entry['Payment Status'] ?? '') : '';
                        $paymentDate = isset($entry['Payment Date']) ? trim($entry['Payment Date'] ?? '') : '';
                        $entryDate = isset($entry['Entry Date']) ? trim($entry['Entry Date'] ?? '') : '';
                        $dateUpdated = isset($entry['Date Updated']) ? trim($entry['Date Updated'] ?? '') : '';
                        
                        // Use Payment Date if available, otherwise use Entry Date or Date Updated
                        $paidAt = null;
                        if (!empty($paymentDate)) {
                            try {
                                $paidAt = \Carbon\Carbon::parse($paymentDate);
                            } catch (\Exception $e) {
                                \Log::warning('Failed to parse Payment Date: ' . $paymentDate);
                            }
                        } elseif (!empty($entryDate)) {
                            try {
                                $paidAt = \Carbon\Carbon::parse($entryDate);
                            } catch (\Exception $e) {
                                \Log::warning('Failed to parse Entry Date: ' . $entryDate);
                            }
                        } elseif (!empty($dateUpdated)) {
                            try {
                                $paidAt = \Carbon\Carbon::parse($dateUpdated);
                            } catch (\Exception $e) {
                                \Log::warning('Failed to parse Date Updated: ' . $dateUpdated);
                            }
                        }
                        
                        // Map payment status to enrollment status (only if payment status is not empty)
                        $enrollmentStatus = 'pending';
                        $paymentStatusNormalized = '';
                        if (!empty($paymentStatus)) {
                            $paymentStatusNormalized = strtolower($paymentStatus);
                            if (in_array($paymentStatusNormalized, ['active', 'completed', 'paid', 'success'])) {
                                $enrollmentStatus = 'active';
                            } elseif (in_array($paymentStatusNormalized, ['processing', 'pending'])) {
                                $enrollmentStatus = 'pending';
                            } elseif (in_array($paymentStatusNormalized, ['cancelled', 'refunded'])) {
                                $enrollmentStatus = 'cancelled';
                            }
                        } else {
                            // If payment status is empty, keep enrollment as pending and payment status as empty
                            $paymentStatusNormalized = '';
                        }
                        
                        // Store the full class type name for better matching
                        // e.g., "2Hrs/Week One-on-One (299CAD/Month)" or "One-on-One (449CAD/Month)"
                        $enrollmentsToInsert[] = [
                            'email' => $email,
                            'user_id' => $userIdToUse, // Will be null if new user, set after user creation
                            'is_existing_user' => $userExists, // Flag to know if user already exists
                            'class_type' => $classType, // Store full name for matching
                            'form_data' => $formData,
                            'transaction_id' => $transactionId,
                            'payment_status' => $paymentStatusNormalized,
                            'paid_at' => $paidAt,
                            'enrollment_status' => $enrollmentStatus,
                        ];
                    }

                } catch (\Exception $e) {
                    \Log::warning('Failed to process entry: ' . $e->getMessage());
                    $skipped++;
                    continue;
                }
            }

            // Hash passwords after validation loop (batch operation is faster)
            foreach ($usersToInsert as &$user) {
                $user['password'] = Hash::make($user['password']);
            }

            // Insert users (one at a time to handle duplicates gracefully)
            if (!empty($usersToInsert)) {
                $successCount = 0;
                $failureCount = 0;

                foreach ($usersToInsert as $user) {
                    try {
                        User::create($user);
                        $successCount++;
                    } catch (\Exception $e) {
                        // Log but don't fail the entire batch
                        \Log::warning('Failed to import user', ['email' => $user['email'] ?? 'unknown', 'error' => $e->getMessage()]);
                        $failureCount++;
                    }
                }

                // Don't set importedCount here - it should count enrollments, not users
                // importedCount will be set after enrollments are created
            }

            // Log enrollment data before processing
            \Log::info('DEBUG: Enrollments to process', [
                'count' => count($enrollmentsToInsert),
                'sample' => array_slice($enrollmentsToInsert, 0, 3),
            ]);

            // Create enrollments for users with class types (runs regardless of whether new users were inserted)
            if (!empty($enrollmentsToInsert) && $course) {
                // Get all users (both newly inserted and existing) by email
                $emails = array_column($enrollmentsToInsert, 'email');
                $allUsers = User::whereIn('email', $emails)->get()->keyBy('email');

                // Update existingUsers map with newly created users
                foreach ($allUsers as $email => $user) {
                    $existingUsers[$email] = $user;
                }

                // Get unique class type names and look them up
                $classTypeNames = array_unique(array_column($enrollmentsToInsert, 'class_type'));

                    // Get all class types from database for matching
                    $allClassTypes = ClassType::where('is_active', true)->get();
                    
                    // Build a map of imported class type names to database class types
                    $classTypesMap = [];
                    
                    foreach ($classTypeNames as $importedClassName) {
                        $matched = false;
                        
                        // Strategy 1: Exact match
                        foreach ($allClassTypes as $ct) {
                            if ($ct->class_name === $importedClassName) {
                                $classTypesMap[$importedClassName] = $ct;
                                $matched = true;
                                break;
                            }
                        }
                        
                        if ($matched) continue;
                        
                        // Strategy 2: Normalize and compare (remove extra spaces, case insensitive)
                        $normalizedImported = strtolower(trim($importedClassName));
                        foreach ($allClassTypes as $ct) {
                            $normalizedDb = strtolower(trim($ct->class_name));
                            if ($normalizedDb === $normalizedImported) {
                                $classTypesMap[$importedClassName] = $ct;
                                $matched = true;
                                break;
                            }
                        }
                        
                        if ($matched) continue;
                        
                        // Strategy 3: Extract main name part (before first parenthesis) and match
                        $importedMainName = trim(explode('(', $importedClassName)[0]);
                        foreach ($allClassTypes as $ct) {
                            $dbMainName = trim(explode('(', $ct->class_name)[0]);
                            if (strtolower($dbMainName) === strtolower($importedMainName)) {
                                $classTypesMap[$importedClassName] = $ct;
                                $matched = true;
                                break;
                            }
                        }
                        
                        if ($matched) continue;
                        
                        // Strategy 4: Contains match (check if imported name contains DB name or vice versa)
                        foreach ($allClassTypes as $ct) {
                            $importedLower = strtolower($importedClassName);
                            $dbLower = strtolower($ct->class_name);
                            
                            // Check if imported name contains database name
                            if (strpos($importedLower, $dbLower) !== false || strpos($dbLower, $importedLower) !== false) {
                                $classTypesMap[$importedClassName] = $ct;
                                $matched = true;
                                break;
                            }
                            
                            // Check main name parts
                            $importedMain = strtolower(trim(explode('(', $importedClassName)[0]));
                            $dbMain = strtolower(trim(explode('(', $ct->class_name)[0]));
                            if (strpos($importedMain, $dbMain) !== false || strpos($dbMain, $importedMain) !== false) {
                                $classTypesMap[$importedClassName] = $ct;
                                $matched = true;
                                break;
                            }
                        }
                        
                        if ($matched) continue;
                        
                        // Strategy 5: Partial word matching (for cases like "2Hrs/Week One-on-One" vs "One-on-One")
                        $importedWords = preg_split('/[\s\/\-]+/', strtolower($importedMainName));
                        foreach ($allClassTypes as $ct) {
                            $dbMainName = trim(explode('(', $ct->class_name)[0]);
                            $dbWords = preg_split('/[\s\/\-]+/', strtolower($dbMainName));
                            
                            // Check if key words match (like "One-on-One")
                            $commonWords = array_intersect($importedWords, $dbWords);
                            if (count($commonWords) >= 2 || (count($commonWords) === 1 && strlen($commonWords[0]) > 5)) {
                                $classTypesMap[$importedClassName] = $ct;
                                $matched = true;
                                break;
                            }
                        }
                    }
                    

                    \Log::info('DEBUG: ClassType lookup', [
                        'requested_names' => $classTypeNames,
                        'found_count' => count($classTypesMap),
                        'found_mappings' => array_map(function($ct) { return $ct->class_name; }, $classTypesMap),
                        'unmatched' => array_diff($classTypeNames, array_keys($classTypesMap)),
                    ]);

                    $enrollmentsData = [];
                    foreach ($enrollmentsToInsert as $enrollment) {
                        // Get user - either existing or newly created
                        $user = null;
                        if ($enrollment['is_existing_user'] && isset($existingUsers[$enrollment['email']])) {
                            // Use existing user
                            $user = $existingUsers[$enrollment['email']];
                        } elseif (isset($allUsers[$enrollment['email']])) {
                            // Use newly created user
                            $user = $allUsers[$enrollment['email']];
                        }
                        
                        if (!$user || !$user->id) {
                            \Log::warning('User not found or has no ID for enrollment', ['email' => $enrollment['email']]);
                            continue;
                        }
                        
                        // Look up the class type ID from the map
                        $classType = $classTypesMap[$enrollment['class_type']] ?? null;
                        $classTypeId = $classType ? $classType->id : null;

                        // If class type doesn't exist, try to create it
                        if (!$classTypeId && !empty($enrollment['class_type'])) {
                            try {
                                $newClassType = ClassType::create([
                                    'class_name' => $enrollment['class_type'],
                                    'description' => 'Auto-created from import',
                                    'is_active' => true,
                                ]);
                                $classTypeId = $newClassType->id;
                                // Add to map for future lookups in this batch
                                $classTypesMap[$enrollment['class_type']] = $newClassType;
                            } catch (\Exception $e) {
                                \Log::warning('Failed to create ClassType: ' . $e->getMessage());
                                // Continue without class type
                            }
                        }

                        // Create enrollment with or without class type
                        $enrollmentStatus = $enrollment['enrollment_status'] ?? 'pending';
                        $enrollmentDate = $enrollment['paid_at'] ?? now();
                        
                        // Get entry_id from form_data if imported, otherwise generate sequential
                        $entryId = null;
                        if (isset($enrollment['form_data']['entry_id'])) {
                            $entryId = (int)$enrollment['form_data']['entry_id'];
                        }
                        
                        $enrollmentData = [
                            'user_id' => $user->id,
                            'course_id' => $course->id,
                            'status' => $enrollmentStatus,
                            'enrollment_date' => $enrollmentDate,
                            'form_data' => $enrollment['form_data'] ?? null, // Model will auto-cast to JSON
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        if ($classTypeId) {
                            $enrollmentData['class_type_id'] = $classTypeId;
                        }
                        
                        // Set entry_id if available from import, otherwise will be generated in model
                        if ($entryId) {
                            $enrollmentData['entry_id'] = $entryId;
                        }

                        $enrollmentsData[] = [
                            'enrollment_data' => $enrollmentData,
                            'payment_data' => [
                                'transaction_id' => $enrollment['transaction_id'] ?? null,
                                'payment_status' => $enrollment['payment_status'] ?? '', // Keep empty if not set, don't default to 'pending'
                                'paid_at' => $enrollment['paid_at'],
                                'final_amount' => $enrollment['form_data']['final_amount'] ?? 0,
                                'discount_amount' => $enrollment['form_data']['discount_amount'] ?? 0,
                                'coupon_code' => $enrollment['form_data']['coupon_code'] ?? null,
                            ],
                            'user_id' => $user->id,
                        ];
                    }

                    if (!empty($enrollmentsData)) {
                        // Insert enrollments and create payment records
                        foreach ($enrollmentsData as $item) {
                            $enrollment = Enrollment::create($item['enrollment_data']);
                            
                            // Create payment record ONLY if there's actual payment data AND payment status is not empty
                            $paymentData = $item['payment_data'];
                            $hasPaymentData = !empty($paymentData['transaction_id']) || 
                                            (!empty($paymentData['final_amount']) && $paymentData['final_amount'] > 0) ||
                                            !empty($paymentData['paid_at']);
                            
                            // Only create payment if we have payment data AND payment status is not empty
                            // If payment status is empty, don't create payment record - enrollment status will remain as 'pending'
                            $paymentStatus = $paymentData['payment_status'] ?? '';
                            $shouldCreatePayment = $hasPaymentData && !empty($paymentStatus);
                            
                            if ($shouldCreatePayment) {
                                // Map payment status to database status
                                $dbPaymentStatus = 'pending';
                                if (in_array($paymentStatus, ['active', 'completed', 'paid', 'success'])) {
                                    $dbPaymentStatus = 'completed';
                                } elseif ($paymentStatus === 'processing') {
                                    $dbPaymentStatus = 'processing';
                                } elseif (in_array($paymentStatus, ['cancelled', 'refunded'])) {
                                    $dbPaymentStatus = 'cancelled';
                                }
                                
                                try {
                                    Payment::create([
                                        'user_id' => $item['user_id'],
                                        'enrollment_id' => $enrollment->id,
                                        'amount' => $paymentData['final_amount'] ?? 0,
                                        'currency' => 'cad',
                                        'status' => $dbPaymentStatus,
                                        'transaction_id' => $paymentData['transaction_id'],
                                        'coupon_code' => $paymentData['coupon_code'],
                                        'discount_amount' => $paymentData['discount_amount'] ?? 0,
                                        'final_amount' => $paymentData['final_amount'] ?? 0,
                                        'paid_at' => $paymentData['paid_at'],
                                    ]);
                                } catch (\Exception $e) {
                                    \Log::warning('Failed to create payment record', [
                                        'enrollment_id' => $enrollment->id,
                                        'error' => $e->getMessage()
                                    ]);
                                }
                            }
                        }
                        
                        // Count actual enrollments created (this is the real importedCount)
                        $importedCount = count($enrollmentsData);
                        
                    \Log::info('DEBUG: Enrollments and payments created', ['count' => $importedCount]);
                } else {
                    // No enrollments to create (all entries had missing class types or other issues)
                    $importedCount = 0;
                }
            } else {
                // No enrollments to insert
                $importedCount = 0;
            }

            // Determine if there were duplicates (based on entry_id)
            $hasDuplicates = ($duplicateEntryIds > 0);
            
            // Log final results
            \Log::info('Import completed', [
                'imported_enrollments' => $importedCount,
                'skipped' => $skipped,
                'duplicate_entry_ids' => $duplicateEntryIds,
                'missing_class_types' => $missingClassTypeCount,
                'has_duplicates' => $hasDuplicates
            ]);
            
            // If nothing was imported and nothing was skipped, it might indicate a data format issue
            if ($importedCount === 0 && $skipped === 0 && !empty($data)) {
                \Log::warning('No records processed - possible data format issue', [
                    'data_count' => count($data),
                    'sample_keys' => !empty($data[0]) && is_array($data[0]) ? array_keys($data[0]) : []
                ]);
            }
            
            // Build detailed message
            $message = "Successfully imported $importedCount enrollment(s)";
            $skipReasons = [];
            if ($duplicateEntryIds > 0) {
                $skipReasons[] = "$duplicateEntryIds due to duplicate entry IDs";
            }
            if ($missingClassTypeCount > 0) {
                $skipReasons[] = "$missingClassTypeCount due to missing class types";
            }
            if ($skipped > ($duplicateEntryIds + $missingClassTypeCount)) {
                $otherSkipped = $skipped - $duplicateEntryIds - $missingClassTypeCount;
                if ($otherSkipped > 0) {
                    $skipReasons[] = "$otherSkipped due to missing required fields or other errors";
                }
            }
            if (!empty($skipReasons)) {
                $message .= ". " . ($skipped > 0 ? "$skipped entries were skipped: " : "") . implode(", ", $skipReasons) . ".";
            }
            
            return response()->json([
                'success' => true,
                'importedCount' => $importedCount,
                'skipped' => $skipped,
                'duplicateEntryIds' => $duplicateEntryIds,
                'missingClassTypeCount' => $missingClassTypeCount,
                'hasDuplicates' => $hasDuplicates,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            \Log::error('Import failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return more detailed error message for debugging
            $errorMessage = 'Import failed. Your file could not be processed due to missing or invalid data. Please check the JSON structure and try again.';
            
            // In development, include the actual error
            if (config('app.debug')) {
                $errorMessage .= ' Error: ' . $e->getMessage();
            }
            
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 422);
        }
    }

    // USER PREFERENCES MANAGEMENT

    /**
     * Get user preference - GET /api/admin/preferences/{key}
     */
    public function getPreference(Request $request, $key)
    {
        $user = $request->user();
        $preference = UserPreference::where('user_id', $user->id)
            ->where('preference_key', $key)
            ->first();

        $value = $preference ? $preference->preference_value : null;

        return response()->json([
            'success' => true,
            'data' => [
                'key' => $key,
                'value' => $value,
            ],
            'message' => 'Preference retrieved',
        ]);
    }

    /**
     * Set user preference - PUT /api/admin/preferences/{key}
     */
    public function setPreference(Request $request, $key)
    {
        $validated = $request->validate([
            'value' => 'required|string',
        ]);

        $user = $request->user();

        UserPreference::updateOrCreate(
            [
                'user_id' => $user->id,
                'preference_key' => $key,
            ],
            [
                'preference_value' => $validated['value'],
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Preference saved',
        ]);
    }

    /**
     * Get reset import statistics - GET /api/admin/reset-import-stats
     */
    public function getResetImportStats(Request $request)
    {
        try {
            // Count only imported enrollments (those with _imported flag in form_data)
            $enrollmentCount = 0;
            $studentIds = [];
            
            if (config('database.default') === 'mysql') {
                // MySQL/MariaDB - use JSON_EXTRACT
                $importedEnrollments = DB::table('enrollments')
                    ->whereNotNull('form_data')
                    ->get();
                
                foreach ($importedEnrollments as $enrollment) {
                    $formData = json_decode($enrollment->form_data, true);
                    if (isset($formData['_imported']) && ($formData['_imported'] === true || $formData['_imported'] === 'true')) {
                        $enrollmentCount++;
                        $studentIds[] = $enrollment->user_id;
                    }
                }
            } else {
                // Fallback for other databases - use Eloquent
                $importedEnrollments = Enrollment::whereNotNull('form_data')->get();
                foreach ($importedEnrollments as $enrollment) {
                    $formData = $enrollment->form_data;
                    if (isset($formData['_imported']) && ($formData['_imported'] === true || $formData['_imported'] === 'true')) {
                        $enrollmentCount++;
                        $studentIds[] = $enrollment->user_id;
                    }
                }
            }
            
            // Count unique students who have imported enrollments
            $studentCount = User::whereIn('id', array_unique($studentIds))
                ->where('user_type', 'student')
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'studentCount' => $studentCount,
                    'enrollmentCount' => $enrollmentCount,
                ],
                'message' => 'Reset statistics retrieved',
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to retrieve reset statistics', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve reset statistics: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reset import data - DELETE /api/admin/reset-import-data
     */
    public function resetImportData(Request $request)
    {
        try {
            // Get counts before deletion
            $enrollmentCount = Enrollment::count();
            $studentCount = User::where('user_type', 'student')->count();

            // Delete in proper order to handle foreign key constraints:
            // 1. Delete payments (references enrollments)
            // 2. Delete enrollments (references users)
            // 3. Delete student users
            Payment::query()->delete();

            // Delete all enrollments
            Enrollment::query()->delete();

            // Delete all students
            User::where('user_type', 'student')->delete();

            \Log::info('Reset import data', [
                'students_deleted' => $studentCount,
                'enrollments_deleted' => $enrollmentCount,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Successfully deleted $studentCount student(s) and $enrollmentCount enrollment(s)",
                'data' => [
                    'studentCount' => $studentCount,
                    'enrollmentCount' => $enrollmentCount,
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Reset import data failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset data: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Import WordPress passwords - POST /api/admin/import-wordpress-passwords
     */
    public function importWordPressPasswords(Request $request)
    {
        try {
            $data = $request->input('data');

            if (!$data || !is_array($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid data format'
                ], 422);
            }

            $updatedCount = 0;
            $notFoundCount = 0;
            $notFoundEmails = []; // Track emails that weren't found

            foreach ($data as $entry) {
                try {
                    if (!is_array($entry)) {
                        continue;
                    }

                    // Extract email and password from WordPress export format
                    // Format: {"user_email":"email@example.com","user_pass":"$wp$2y$10$..."}
                    $email = isset($entry['user_email']) ? trim($entry['user_email'] ?? '') : '';
                    $wpPassword = isset($entry['user_pass']) ? trim($entry['user_pass'] ?? '') : '';

                    if (empty($email) || empty($wpPassword)) {
                        \Log::warning('Skipping entry due to missing email or password', [
                            'email' => $email,
                            'has_password' => !empty($wpPassword)
                        ]);
                        continue;
                    }

                    // Find user by email
                    $user = User::where('email', $email)->first();

                    if (!$user) {
                        $notFoundCount++;
                        $notFoundEmails[] = $email; // Add to list of not found emails
                        \Log::info('User not found for WordPress password import', ['email' => $email]);
                        continue;
                    }

                    // Handle WordPress password format
                    // Store WordPress password in wordpress_password column to avoid Laravel's 'hashed' cast
                    // The 'hashed' cast on the password column interferes with WordPress password verification
                    $passwordHash = $wpPassword;

                    // Update user password in wordpress_password column (not password column)
                    // This avoids the Laravel 'hashed' cast which would corrupt WordPress password hashes
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update(['wordpress_password' => $passwordHash]);
                    
                    // Refresh the user model to reflect the change
                    $user->refresh();

                    $updatedCount++;
                    \Log::info('WordPress password updated', ['email' => $email, 'user_id' => $user->id]);

                } catch (\Exception $e) {
                    \Log::warning('Failed to process WordPress password entry: ' . $e->getMessage(), [
                        'entry' => $entry
                    ]);
                    continue;
                }
            }

            \Log::info('WordPress password import completed', [
                'updated' => $updatedCount,
                'not_found' => $notFoundCount,
                'not_found_emails' => $notFoundEmails,
                'total_processed' => count($data)
            ]);

            return response()->json([
                'success' => true,
                'updatedCount' => $updatedCount,
                'notFoundCount' => $notFoundCount,
                'notFoundEmails' => $notFoundEmails, // Return list of emails that weren't found
                'message' => "Successfully updated $updatedCount password(s)" . ($notFoundCount > 0 ? ". $notFoundCount user(s) were not found." : '')
            ]);
        } catch (\Exception $e) {
            \Log::error('WordPress password import failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test password verification - POST /api/admin/test-password
     */
    public function testPassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            $user = User::where('email', $validated['email'])->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found with this email address.',
                    'details' => [
                        'userFound' => false,
                    ]
                ]);
            }

            // Get raw password from database to bypass Eloquent 'hashed' cast
            $rawPasswordHash = DB::table('users')
                ->where('id', $user->id)
                ->value('password');

            $isWordPressPassword = \App\Utils\WordPressPassword::isWordPressFormat($rawPasswordHash);
            $passwordFormat = $isWordPressPassword ? 'WordPress Format ($wp$)' : 'Laravel Format ($2y$)';
            $hashPrefix = substr($rawPasswordHash, 0, 30) . '...';
            $hashLength = strlen($rawPasswordHash);
            
            // For debugging: Show full hash (first 60 chars) if WordPress format
            $fullHashPreview = $isWordPressPassword ? substr($rawPasswordHash, 0, 60) . '...' : $hashPrefix;

            // Try verification
            $passwordValid = false;
            $verificationMethod = null;
            $converted = false;

            if (!$isWordPressPassword) {
                // Try Laravel Hash::check
                try {
                    if (Hash::check($validated['password'], $rawPasswordHash)) {
                        $passwordValid = true;
                        $verificationMethod = 'Laravel Hash::check';
                    }
                } catch (\Exception $e) {
                    // Try password_verify as fallback
                    if (password_verify($validated['password'], $rawPasswordHash)) {
                        $passwordValid = true;
                        $verificationMethod = 'PHP password_verify (fallback)';
                    }
                }
            } else {
                // WordPress password verification
                // Try multiple verification methods with detailed logging
                \Log::info('Starting WordPress password verification', [
                    'email' => $validated['email'],
                    'hash_full' => $rawPasswordHash,
                    'hash_length' => strlen($rawPasswordHash),
                    'password' => $validated['password'],
                    'password_length' => strlen($validated['password'])
                ]);
                
                // Method 1: Use WordPressPassword utility
                $wpVerifyResult = \App\Utils\WordPressPassword::verify($validated['password'], $rawPasswordHash);
                
                if ($wpVerifyResult) {
                    $passwordValid = true;
                    $verificationMethod = 'WordPress Password Verifier';
                    
                    // Convert to Laravel format
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update(['password' => Hash::make($validated['password'])]);
                    $converted = true;
                } else {
                    // Method 2: Manual conversion and verification
                    \Log::info('WordPress utility failed, trying manual conversion', [
                        'original_hash' => $rawPasswordHash
                    ]);
                    
                    // Try direct string replacement
                    $manualConverted = str_replace('$wp$', '$', $rawPasswordHash);
                    \Log::info('Manual conversion result', [
                        'converted' => $manualConverted,
                        'converted_length' => strlen($manualConverted),
                        'converted_prefix' => substr($manualConverted, 0, 20)
                    ]);
                    
                    // Validate converted hash format
                    if (preg_match('/^\$2[ayb]\$\d+\$/', $manualConverted) && strlen($manualConverted) >= 60) {
                        $manualResult = @password_verify($validated['password'], $manualConverted);
                        \Log::info('Manual password_verify result', [
                            'result' => $manualResult,
                            'password' => $validated['password'],
                            'converted_hash' => $manualConverted
                        ]);
                        
                        if ($manualResult === true) {
                            $passwordValid = true;
                            $verificationMethod = 'Manual password_verify (fallback)';
                            DB::table('users')
                                ->where('id', $user->id)
                                ->update(['password' => Hash::make($validated['password'])]);
                            $converted = true;
                        }
                    } else {
                        \Log::warning('Manual conversion produced invalid bcrypt format', [
                            'converted' => $manualConverted,
                            'format_check' => preg_match('/^\$2[ayb]\$\d+\$/', $manualConverted)
                        ]);
                    }
                }
            }

            if ($passwordValid) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password verified successfully! ' . ($converted ? 'Password has been converted to Laravel format.' : ''),
                    'details' => [
                        'userFound' => true,
                        'passwordFormat' => $passwordFormat,
                        'hashPrefix' => $hashPrefix,
                        'fullHashPreview' => $fullHashPreview ?? $hashPrefix,
                        'hashLength' => $hashLength,
                        'verificationMethod' => $verificationMethod,
                        'converted' => $converted,
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Password verification failed. The password does not match.',
                    'details' => [
                        'userFound' => true,
                        'passwordFormat' => $passwordFormat,
                        'hashPrefix' => $hashPrefix,
                        'fullHashPreview' => $fullHashPreview ?? $hashPrefix,
                        'hashLength' => $hashLength,
                        'verificationMethod' => 'None (verification failed)',
                        'converted' => false,
                        'debugInfo' => 'Check Laravel logs for detailed verification attempts'
                    ]
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Password test failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error testing password: ' . $e->getMessage(),
                'details' => null
            ], 500);
        }
    }

    // CACHE MANAGEMENT

    /**
     * Get cache status - GET /api/admin/cache/status
     */
    public function cacheStatus()
    {
        $redisConnected = false;

        try {
            if (config('cache.default') === 'redis' || config('database.redis.client')) {
                $redis = app('redis');
                $redis->connection()->ping();
                $redisConnected = true;
            }
        } catch (\Exception $e) {
            $redisConnected = false;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'redis_connected' => $redisConnected,
                'cache_driver' => config('cache.default'),
            ],
            'message' => 'Cache status retrieved',
        ]);
    }

    /**
     * Get cache settings - GET /api/admin/cache/settings
     */
    public function cacheSettings()
    {
        $redisConnected = false;

        try {
            if (config('cache.default') === 'redis' || config('database.redis.client')) {
                $redis = app('redis');
                $redis->connection()->ping();
                $redisConnected = true;
            }
        } catch (\Exception $e) {
            $redisConnected = false;
        }

        // Get stored cache settings from database or use defaults
        $cacheSettingsRecord = Settings::where('key', 'cache_settings')->first();
        $cacheSettings = $cacheSettingsRecord ? json_decode($cacheSettingsRecord->value, true) : null;

        // Default settings if not saved yet
        $defaults = [
            'courses' => ['enabled' => true, 'duration' => 30, 'unit' => 'days'],
            'class_types' => ['enabled' => true, 'duration' => 30, 'unit' => 'days'],
            'tutors' => ['enabled' => true, 'duration' => 30, 'unit' => 'days'],
            'students' => ['enabled' => true, 'duration' => 5, 'unit' => 'minutes'],
            'dashboard' => ['enabled' => true, 'duration' => 10, 'unit' => 'minutes'],
            'settings' => ['enabled' => true, 'duration' => 30, 'unit' => 'days'],
            'pages' => ['enabled' => true, 'duration' => 30, 'unit' => 'days'],
            'enrollments' => ['enabled' => true, 'duration' => 5, 'unit' => 'minutes'],
        ];

        $settings = $cacheSettings ?? $defaults;
        $settings['redis_connected'] = $redisConnected;

        return response()->json([
            'success' => true,
            'data' => $settings,
            'message' => 'Cache settings retrieved',
        ]);
    }

    /**
     * Save cache settings - POST /api/admin/cache/settings
     */
    public function saveCacheSettings(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        Settings::updateOrCreate(
            ['key' => 'cache_settings'],
            ['value' => json_encode($validated['settings'])]
        );

        return response()->json([
            'success' => true,
            'message' => 'Cache settings saved',
        ]);
    }

    /**
     * Clear specific cache - POST /api/admin/cache/clear/{key}
     */
    public function clearCache($key)
    {
        $cacheKeys = [
            'courses' => ['courses_list', 'courses_*', 'course_*'],
            'class_types' => ['class_types', 'class_types_*'],
            'tutors' => ['tutors_list', 'tutors_*', 'tutor_*'],
            'students' => ['students_list', 'students_*', 'student_*'],
            'dashboard' => ['dashboard_stats', 'dashboard_*', 'admin_dashboard_*'],
            'settings' => ['settings_*', 'site_settings', 'public_settings'],
            'pages' => ['pages_*', 'page_*'],
            'enrollments' => ['enrollments_*', 'enrollment_*', 'student_enrollments_*'],
        ];

        if (!isset($cacheKeys[$key])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid cache key',
            ], 400);
        }

        try {
            foreach ($cacheKeys[$key] as $pattern) {
                if (str_contains($pattern, '*')) {
                    // For wildcard patterns, we need to use tags or flush specific keys
                    // With file cache, we can't easily delete by pattern
                    // So we use cache tags if available (Redis) or just forget the exact key
                    $exactKey = str_replace('*', '', $pattern);
                    Cache::forget($exactKey);
                } else {
                    Cache::forget($pattern);
                }
            }

            return response()->json([
                'success' => true,
                'message' => ucfirst($key) . ' cache cleared',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Clear all cache - POST /api/admin/cache/clear-all
     */
    public function clearAllCache()
    {
        try {
            Cache::flush();

            return response()->json([
                'success' => true,
                'message' => 'All cache cleared successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper: Get cache duration in seconds based on settings
     */
    public static function getCacheDuration($key)
    {
        $cacheSettingsRecord = Settings::where('key', 'cache_settings')->first();
        $cacheSettings = $cacheSettingsRecord ? json_decode($cacheSettingsRecord->value, true) : null;

        if (!$cacheSettings || !isset($cacheSettings[$key]) || !$cacheSettings[$key]['enabled']) {
            return null; // Caching disabled for this key
        }

        $setting = $cacheSettings[$key];
        $duration = $setting['duration'];
        $unit = $setting['unit'];

        switch ($unit) {
            case 'minutes':
                return $duration * 60;
            case 'hours':
                return $duration * 60 * 60;
            case 'days':
                return $duration * 60 * 60 * 24;
            default:
                return $duration * 60;
        }
    }

    /**
     * Helper: Clear cache when data is updated
     */
    public static function clearCacheOnUpdate($key)
    {
        $cacheKeys = [
            'courses' => ['courses_list_page_1', 'courses_list_page_2', 'courses_list_page_3', 'courses_list_page_4', 'courses_list_page_5'],
            'class_types' => ['class_types_admin', 'class_types_public'],
            'tutors' => ['tutors_list'],
            'settings' => ['public_settings'],
            'pages' => [],
            'enrollments' => [],
        ];

        if (isset($cacheKeys[$key])) {
            foreach ($cacheKeys[$key] as $cacheKey) {
                Cache::forget($cacheKey);
            }
        }

        // For file cache, we can't easily delete by pattern
        // So we'll clear all cache for certain keys to ensure freshness
        if (in_array($key, ['courses', 'class_types', 'settings'])) {
            // These are critical - clear the first 10 pages of pagination just in case
            for ($i = 1; $i <= 10; $i++) {
                Cache::forget('courses_list_page_' . $i);
            }
        }
    }

    /**
     * Save admin preview progress for a course
     */
    public function savePreviewProgress(Request $request, $courseId)
    {
        $user = $request->user();

        $validated = $request->validate([
            'section_index' => 'required|integer',
            'question_index' => 'required|integer',
            'selected_answer' => 'required|string',
            'is_correct' => 'required|boolean',
        ]);

        // Find or create progress record for this course
        $progress = StudentProgress::firstOrNew([
            'user_id' => $user->id,
            'course_id' => $courseId,
            'activity_type' => 'admin_preview',
        ]);

        // Get existing section_data or initialize empty array
        $sectionData = $progress->section_data ?? [];

        // Add/update this answer
        $key = $validated['section_index'] . '-' . $validated['question_index'];
        $sectionData[$key] = [
            'selectedAnswer' => $validated['selected_answer'],
            'isCorrect' => $validated['is_correct'],
        ];

        $progress->section_data = $sectionData;
        $progress->save();

        return response()->json([
            'success' => true,
            'progress' => $progress
        ]);
    }

    /**
     * Save admin preview section results
     */
    public function savePreviewSectionResults(Request $request, $courseId)
    {
        $user = $request->user();

        $validated = $request->validate([
            'section_results' => 'required|array',
        ]);

        // Find or create progress record for this course
        $progress = StudentProgress::firstOrNew([
            'user_id' => $user->id,
            'course_id' => $courseId,
            'activity_type' => 'admin_preview',
        ]);

        // Get existing section_data or initialize
        $sectionData = $progress->section_data ?? [];

        // Update section_results in section_data
        $sectionData['_sectionResults'] = $validated['section_results'];

        $progress->section_data = $sectionData;
        $progress->save();

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Reset admin preview progress for a course
     */
    public function resetPreviewProgress(Request $request, $courseId)
    {
        $user = $request->user();

        StudentProgress::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->where('activity_type', 'admin_preview')
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Progress reset successfully'
        ]);
    }

    /**
     * Get admin preview progress for a course
     */
    public function getPreviewProgress(Request $request, $courseId)
    {
        $user = $request->user();

        $progress = StudentProgress::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->where('activity_type', 'admin_preview')
            ->first();

        $sectionData = $progress ? ($progress->section_data ?? []) : [];

        // Extract sectionResults from section_data
        $sectionResults = $sectionData['_sectionResults'] ?? [];
        unset($sectionData['_sectionResults']);

        return response()->json([
            'success' => true,
            'answeredQuestions' => $sectionData,
            'sectionResults' => $sectionResults
        ]);
    }

    // GOOGLE MEET ATTENDANCE TRACKER METHODS

    /**
     * Get Google Meet logs from database with filters
     * GET /api/admin/meet-logs
     */
    public function getMeetLogs(Request $request)
    {
        try {
            $service = new GoogleMeetService();
            
            $filters = [
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'organizer_email' => $request->input('organizer_email'),
                'actor_email' => $request->input('actor_email'),
                'search' => $request->input('search'),
            ];

            $logs = $service->getFilteredLogs($filters);

            // Group by conference_id to create sessions
            $sessions = [];
            foreach ($logs as $log) {
                $conferenceId = $log->conference_id ?? 'unknown';
                
                if (!isset($sessions[$conferenceId])) {
                    $sessions[$conferenceId] = [
                        'conference_id' => $conferenceId,
                        'meeting_code' => $log->meeting_code,
                        'organizer_email' => $log->organizer_email,
                        'event_time' => $log->event_time,
                        'participants' => [],
                        'total_duration' => 0,
                    ];
                }

                $sessions[$conferenceId]['participants'][] = [
                    'email' => $log->actor_email,
                    'duration_seconds' => $log->duration_seconds,
                    'formatted_duration' => $log->formatted_duration,
                ];

                $sessions[$conferenceId]['total_duration'] += $log->duration_seconds;
            }

            // Convert to array and sort by event_time
            $sessionsArray = array_values($sessions);
            usort($sessionsArray, function ($a, $b) {
                return strtotime($b['event_time']) - strtotime($a['event_time']);
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'logs' => $logs,
                    'sessions' => $sessionsArray,
                ],
                'message' => 'Meet logs retrieved successfully',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching meet logs: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch meet logs: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sync Google Meet logs from Google API
     * POST /api/admin/meet-logs/sync
     */
    public function syncMeetLogs(Request $request)
    {
        try {
            $service = new GoogleMeetService();
            
            $startTime = $request->input('start_date') 
                ? \Carbon\Carbon::parse($request->input('start_date'))->toIso8601String()
                : null;
            
            $endTime = $request->input('end_date')
                ? \Carbon\Carbon::parse($request->input('end_date'))->endOfDay()->toIso8601String()
                : null;

            $result = $service->syncMeetLogs($startTime, $endTime);

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => sprintf(
                    'Sync completed: %d inserted, %d updated, %d errors',
                    $result['inserted'],
                    $result['updated'],
                    $result['errors']
                ),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error syncing meet logs: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync meet logs: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get unique staff emails from meet logs
     * GET /api/admin/meet-logs/staff-emails
     */
    public function getUniqueStaffEmails(Request $request)
    {
        try {
            $service = new GoogleMeetService();
            $emails = $service->getUniqueStaffEmails();

            return response()->json([
                'success' => true,
                'data' => $emails,
                'message' => 'Staff emails retrieved successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch staff emails: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Calculate total duration for a staff member
     * POST /api/admin/meet-logs/staff-duration
     */
    public function calculateStaffDuration(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        try {
            $service = new GoogleMeetService();
            $totalSeconds = $service->calculateTotalDuration(
                $validated['email'],
                $validated['start_date'] ?? null,
                $validated['end_date'] ?? null
            );

            // Format duration
            $hours = floor($totalSeconds / 3600);
            $minutes = floor(($totalSeconds % 3600) / 60);
            $seconds = $totalSeconds % 60;

            $formatted = '';
            if ($hours > 0) {
                $formatted = sprintf('%dh %dm', $hours, $minutes);
            } elseif ($minutes > 0) {
                $formatted = sprintf('%dm %ds', $minutes, $seconds);
            } else {
                $formatted = sprintf('%ds', $seconds);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'email' => $validated['email'],
                    'total_seconds' => $totalSeconds,
                    'formatted_duration' => $formatted,
                    'hours' => $hours,
                    'minutes' => $minutes,
                ],
                'message' => 'Duration calculated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate duration: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export meet logs to CSV
     * GET /api/admin/meet-logs/export
     */
    public function exportMeetLogs(Request $request)
    {
        try {
            $service = new GoogleMeetService();
            
            $filters = [
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'organizer_email' => $request->input('organizer_email'),
                'actor_email' => $request->input('actor_email'),
                'search' => $request->input('search'),
            ];

            $logs = $service->getFilteredLogs($filters);

            $filename = 'meet_logs_' . date('Y-m-d_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($logs) {
                $file = fopen('php://output', 'w');
                
                // CSV Headers
                fputcsv($file, [
                    'Event ID',
                    'Conference ID',
                    'Meeting Code',
                    'Organizer Email',
                    'Participant Email',
                    'Duration (seconds)',
                    'Duration (formatted)',
                    'Event Time',
                ]);

                // CSV Rows
                foreach ($logs as $log) {
                    fputcsv($file, [
                        $log->event_id,
                        $log->conference_id,
                        $log->meeting_code,
                        $log->organizer_email,
                        $log->actor_email,
                        $log->duration_seconds,
                        $log->formatted_duration,
                        $log->event_time ? $log->event_time->format('Y-m-d H:i:s') : '',
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            \Log::error('Error exporting meet logs: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to export meet logs: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get tutor vacation data - GET /api/admin/tutors/{id}/vacations
     */
    public function getTutorVacations(Request $request, $id)
    {
        $tutor = User::where('user_type', 'tutor')->findOrFail($id);

        $maxDays = UserPreference::where('user_id', $id)
            ->where('preference_key', 'max_vacation_days')
            ->value('preference_value');
        $maxDays = $maxDays !== null ? (int)$maxDays : 2;

        $vacations = TutorVacation::where('tutor_id', $id)
            ->orderBy('start_date', 'asc')
            ->get()
            ->map(function ($v) {
                return [
                    'id' => $v->id,
                    'start_date' => $v->start_date->format('Y-m-d'),
                    'end_date' => $v->end_date->format('Y-m-d'),
                    'total_days' => $v->start_date->diffInDays($v->end_date) + 1,
                    'reason' => $v->reason,
                    'status' => $v->status,
                ];
            });

        $usedCount = TutorVacation::where('tutor_id', $id)
            ->whereIn('status', ['approved', 'pending'])
            ->get()
            ->sum(function($v) {
                return $v->start_date->diffInDays($v->end_date) + 1;
            });

        return response()->json([
            'success' => true,
            'data' => [
                'vacations' => $vacations,
                'used_days' => $usedCount,
                'max_days' => $maxDays,
                'remaining_days' => max(0, $maxDays - (int)$usedCount),
            ],
            'message' => 'Tutor vacations retrieved',
        ]);
    }

    /**
     * Update tutor max vacation days - POST /api/admin/tutors/{id}/vacation-settings
     */
    public function updateTutorVacationSettings(Request $request, $id)
    {
        $tutor = User::where('user_type', 'tutor')->findOrFail($id);

        $validated = $request->validate([
            'max_days' => 'required|integer|min:0|max:365',
        ]);

        UserPreference::updateOrCreate(
            [
                'user_id' => $id,
                'preference_key' => 'max_vacation_days',
            ],
            [
                'preference_value' => (string)$validated['max_days'],
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Vacation settings updated',
        ]);
    }

    /**
     * Delete a tutor vacation date (admin) - DELETE /api/admin/tutors/{id}/vacations/{vacationId}
     */
    public function deleteTutorVacation(Request $request, $id, $vacationId)
    {
        $vacation = TutorVacation::where('id', $vacationId)
            ->where('tutor_id', $id)
            ->first();

        if (!$vacation) {
            return response()->json([
                'success' => false,
                'message' => 'Vacation date not found.',
            ], 404);
        }

        $vacation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vacation date removed',
        ]);
    }

    /**
     * Approve a tutor vacation request - POST /api/admin/tutors/{id}/vacations/{vacationId}/approve
     */
    public function approveVacation(Request $request, $id, $vacationId)
    {
        $vacation = TutorVacation::where('id', $vacationId)
            ->where('tutor_id', $id)
            ->firstOrFail();

        // Admin can modify dates if provided
        if ($request->has('start_date')) {
            $vacation->start_date = $request->start_date;
        }
        if ($request->has('end_date')) {
            $vacation->end_date = $request->end_date;
        }

        $vacation->status = 'approved';
        $vacation->save();

        return response()->json([
            'success' => true,
            'message' => 'Vacation request approved',
        ]);
    }

    /**
     * Reject a tutor vacation request - POST /api/admin/tutors/{id}/vacations/{vacationId}/reject
     */
    public function rejectVacation(Request $request, $id, $vacationId)
    {
        $vacation = TutorVacation::where('id', $vacationId)
            ->where('tutor_id', $id)
            ->firstOrFail();

        $vacation->status = 'rejected';
        $vacation->save();

        return response()->json([
            'success' => true,
            'message' => 'Vacation request rejected',
        ]);
    }

    /**
     * Get timer edit requests for a tutor - GET /api/admin/tutors/{id}/timer-edit-requests
     */
    public function getTimerEditRequests(Request $request, $id)
    {
        $requests = TimerEditRequest::where('tutor_id', $id)
            ->with('student:id,first_name,last_name,email')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($req) {
                return [
                    'id' => $req->id,
                    'student_id' => $req->student_id,
                    'student_name' => $req->student
                        ? trim(($req->student->first_name ?? '') . ' ' . ($req->student->last_name ?? ''))
                        : 'Unknown',
                    'record_id' => $req->record_id,
                    'record_date' => $req->record_date->format('Y-m-d'),
                    'old_timer' => $req->old_timer,
                    'new_timer' => $req->new_timer,
                    'status' => $req->status,
                    'admin_notes' => $req->admin_notes,
                    'created_at' => $req->created_at->toDateTimeString(),
                ];
            });

        $pendingCount = TimerEditRequest::where('tutor_id', $id)
            ->where('status', 'pending')
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'requests' => $requests,
                'pending_count' => $pendingCount,
            ],
        ]);
    }

    /**
     * Approve a timer edit request - POST /api/admin/tutors/{id}/timer-edit-requests/{requestId}/approve
     */
    public function approveTimerEdit(Request $request, $id, $requestId)
    {
        $editRequest = TimerEditRequest::where('id', $requestId)
            ->where('tutor_id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        // Update the actual student record JSON
        $studentRecords = DB::table('student_records')
            ->where('student_id', $editRequest->student_id)
            ->first();

        if ($studentRecords && $studentRecords->records) {
            $records = json_decode($studentRecords->records, true);
            if (is_array($records)) {
                foreach ($records as &$record) {
                    if (isset($record['id']) && $record['id'] === $editRequest->record_id) {
                        $record['timer'] = $editRequest->new_timer;
                        break;
                    }
                }
                unset($record);

                DB::table('student_records')
                    ->where('student_id', $editRequest->student_id)
                    ->update([
                        'records' => json_encode($records),
                        'updated_at' => now(),
                    ]);
            }
        }

        $editRequest->status = 'approved';
        $editRequest->admin_notes = $request->input('admin_notes');
        $editRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Timer edit approved and record updated',
        ]);
    }

    /**
     * Reject a timer edit request - POST /api/admin/tutors/{id}/timer-edit-requests/{requestId}/reject
     */
    public function rejectTimerEdit(Request $request, $id, $requestId)
    {
        $editRequest = TimerEditRequest::where('id', $requestId)
            ->where('tutor_id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        $editRequest->status = 'rejected';
        $editRequest->admin_notes = $request->input('admin_notes');
        $editRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Timer edit request rejected',
        ]);
    }

}
