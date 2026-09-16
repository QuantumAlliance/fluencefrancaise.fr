<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{User, Course, TutorStudentAssignment, StudentProgress, Group, TutorVacation, UserPreference, TimerEditRequest};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TutorController extends Controller
{
    /**
     * Tutor dashboard - GET /api/tutor/dashboard
     */
    public function dashboard(Request $request)
    {
        $tutor = $request->user();
        
        // Get assigned students
        $assignedStudentIds = TutorStudentAssignment::where('tutor_id', $tutor->id)
            ->pluck('student_id')
            ->unique();
        $assignedStudentsCount = $assignedStudentIds->count();
        
        // Get courses - check if course_id column exists first
        $myCoursesCount = 0;
        $courses = collect([]);
        if (Schema::hasColumn('tutor_student_assignments', 'course_id')) {
        $courseIds = TutorStudentAssignment::where('tutor_id', $tutor->id)
                ->whereNotNull('course_id')
            ->pluck('course_id')
            ->unique();
        $myCoursesCount = $courseIds->count();
        
        // Get courses with details
        $courses = Course::whereIn('id', $courseIds)->get()->map(function ($course) use ($tutor) {
            $studentCount = TutorStudentAssignment::where('tutor_id', $tutor->id)
                ->where('course_id', $course->id)
                ->count();
            return [
                'id' => $course->id,
                'title' => $course->course_title,
                'level' => $course->course_level ?? 'N/A',
                'student_count' => $studentCount,
                    'active' => $studentCount
            ];
        });
        }
        
        // Get assigned students with progress
        $students = User::whereIn('id', $assignedStudentIds)->get()->map(function ($student) use ($tutor) {
            $assignments = TutorStudentAssignment::where('tutor_id', $tutor->id)
                ->where('student_id', $student->id)
                ->get();
            
            $progresses = StudentProgress::where('user_id', $student->id)->get();
            $avgProgress = $progresses->count() > 0 
                ? round($progresses->avg('progress_percentage') ?? 0)
                : 0;
            
            $lastActivity = $progresses->max('completed_at') ?? $student->updated_at;
            
            $courseTitle = 'N/A';
            if (Schema::hasColumn('tutor_student_assignments', 'course_id')) {
                $firstAssignment = $assignments->first();
                if ($firstAssignment && $firstAssignment->course_id) {
                    $course = Course::find($firstAssignment->course_id);
                    $courseTitle = $course ? $course->course_title : 'N/A';
                }
            }
            
            return [
                'id' => $student->id,
                'name' => ($student->first_name ?? '') . ' ' . ($student->last_name ?? ''),
                'email' => $student->email,
                'avatar' => $student->profile_picture,
                'course' => $courseTitle,
                'progress' => $avgProgress,
                'last_activity' => $lastActivity,
                'status' => $avgProgress > 0 ? 'Active' : 'Inactive'
            ];
        });
        
        // Calculate average progress
        $allProgresses = StudentProgress::whereIn('user_id', $assignedStudentIds)->get();
        $avgProgress = $allProgresses->count() > 0 
            ? round($allProgresses->avg('progress_percentage') ?? 0)
            : 0;
        
        // Active this month
        $activeThisMonth = StudentProgress::whereIn('user_id', $assignedStudentIds)
            ->whereMonth('completed_at', now()->month)
            ->whereYear('completed_at', now()->year)
            ->distinct('user_id')
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'tutor' => $tutor,
                'assigned_students' => $assignedStudentsCount,
                'my_courses' => $myCoursesCount,
                'avg_progress' => $avgProgress,
                'active_this_month' => $activeThisMonth,
                'courses' => $courses,
                'students' => $students
            ],
            'message' => 'Tutor dashboard',
        ]);
    }

    /**
     * Get my courses - GET /api/tutor/my-courses
     */
    public function myCourses(Request $request)
    {
        $tutor = $request->user();

        if (!Schema::hasColumn('tutor_student_assignments', 'course_id')) {
            return response()->json([
                'success' => true,
                'data' => ['data' => [], 'current_page' => 1, 'last_page' => 1],
                'message' => 'My courses',
            ]);
        }

        // Order by category display_order
        $courses = Course::leftJoin('class_types', function ($join) {
                $join->on('courses.course_category', '=', 'class_types.class_name')
                     ->orOn('courses.course_category', '=', 'class_types.name');
            })
            ->select('courses.*')
            ->whereIn('courses.id', function ($query) use ($tutor) {
                $query->select('course_id')
                      ->from('tutor_student_assignments')
                      ->where('tutor_id', $tutor->id)
                      ->whereNotNull('course_id');
            })
            ->distinct()
            ->orderBy('class_types.display_order', 'asc')
            ->orderBy('courses.id', 'asc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $courses,
            'message' => 'My courses',
        ]);
    }

    /**
     * Get courses - GET /api/tutor/courses
     */
    public function courses(Request $request)
    {
        $tutor = $request->user();

        if (!Schema::hasColumn('tutor_student_assignments', 'course_id')) {
            return response()->json([
                'success' => true,
                'data' => ['data' => [], 'current_page' => 1, 'last_page' => 1],
                'message' => 'My courses',
            ]);
        }

        // Order by category display_order
        $courses = Course::leftJoin('class_types', function ($join) {
                $join->on('courses.course_category', '=', 'class_types.class_name')
                     ->orOn('courses.course_category', '=', 'class_types.name');
            })
            ->select('courses.*')
            ->whereIn('courses.id', function ($query) use ($tutor) {
                $query->select('course_id')
                      ->from('tutor_student_assignments')
                      ->where('tutor_id', $tutor->id)
                      ->whereNotNull('course_id');
            })
            ->distinct()
            ->orderBy('class_types.display_order', 'asc')
            ->orderBy('courses.id', 'asc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $courses,
            'message' => 'My courses',
        ]);
    }

    /**
     * Get assigned students - GET /api/tutor/students
     */
    public function students(Request $request)
    {
        $tutor = $request->user();

        $students = User::whereIn('id', function ($query) use ($tutor) {
            $query->select('student_id')
                  ->from('tutor_student_assignments')
                  ->where('tutor_id', $tutor->id);
        })
        ->select('id', 'name', 'first_name', 'last_name', 'email', 'username', 'profile_picture', 'created_at')
        ->paginate(15);

        // Ensure name and email are properly set
        $students->getCollection()->transform(function ($student) {
            // Set name if not present
            if (!$student->name && ($student->first_name || $student->last_name)) {
                $student->name = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
            }
            // Ensure email is present
            if (!$student->email) {
                $student->email = 'N/A';
            }
            return $student;
        });

        return response()->json([
            'success' => true,
            'data' => $students,
            'message' => 'Assigned students',
        ]);
    }

    /**
     * Assign student to course - POST /api/tutor/assign-student/{courseId}
     */
    public function assignStudent(Request $request, $courseId)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $tutor = $request->user();

        $data = [
            'tutor_id' => $tutor->id,
            'student_id' => $validated['student_id'],
        ];

        // Only add course_id if column exists
        if (Schema::hasColumn('tutor_student_assignments', 'course_id')) {
            $data['course_id'] = $courseId;
        }

        TutorStudentAssignment::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Student assigned to course',
        ]);
    }

    /**
     * Add student to tutor - POST /api/tutor/add-student
     */
    public function addStudent(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $tutor = $request->user();

        // Verify student exists and is a student
        $student = User::where('id', $validated['student_id'])
                      ->where('user_type', 'student')
                      ->firstOrFail();

        // Check if student is already assigned to this tutor
        $existing = TutorStudentAssignment::where('tutor_id', $tutor->id)
            ->where('student_id', $validated['student_id'])
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Student is already assigned to you',
            ], 400);
        }

        // Create assignment
        TutorStudentAssignment::create([
            'tutor_id' => $tutor->id,
            'student_id' => $validated['student_id'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Student added successfully',
        ]);
    }

    /**
     * Remove student - DELETE /api/tutor/remove-student/{studentId}
     */
    public function removeStudent(Request $request, $studentId)
    {
        $tutor = $request->user();

        // Remove from tutor-student assignment
        TutorStudentAssignment::where('tutor_id', $tutor->id)
            ->where('student_id', $studentId)
            ->delete();

        // Also remove student from all tutor's groups using the pivot table relationship
        $groups = Group::where('tutor_id', $tutor->id)->get();
        foreach ($groups as $group) {
            $group->students()->detach($studentId);
        }

        return response()->json([
            'success' => true,
            'message' => 'Student removed',
        ]);
    }

    /**
     * Get student progress - GET /api/tutor/student/{id}/progress
     */
    public function studentProgress(Request $request, $studentId)
    {
        $tutor = $request->user();

        // Verify tutor-student assignment
        TutorStudentAssignment::where('tutor_id', $tutor->id)
            ->where('student_id', $studentId)
            ->firstOrFail();

        $progress = StudentProgress::where('user_id', $studentId)->get();

        return response()->json([
            'success' => true,
            'data' => $progress,
            'message' => 'Student progress',
        ]);
    }

    /**
     * Get tutor account info - GET /api/tutor/account
     */
    public function account(Request $request)
    {
        $user = $request->user();
        $id = $user->id;

        // Get assigned students
        $assignedStudentIds = TutorStudentAssignment::where('tutor_id', $id)
            ->pluck('student_id')
            ->unique();

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
                            'student_name' => $student ? ($student->name ?: trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''))) : 'Unknown',
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
        $payRates = DB::table('user_preferences')
            ->where('user_id', $id)
            ->where('preference_key', 'tutor_pay_rates')
            ->value('preference_value');
        
        $payRates = $payRates ? json_decode($payRates, true) : null;

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
            'data' => array_merge($user->toArray(), [
                'records' => $allRecords,
                'groups' => $groups,
                'pay_rates' => $payRates,
            ]),
            'message' => 'Tutor account',
        ]);
    }

    /**
     * Update tutor account - PUT /api/tutor/account
     */
    public function updateAccount(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'first_name' => 'nullable|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'timezone' => 'nullable|string',
            'biography' => 'nullable|string|max:1000',
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Account updated successfully',
        ]);
    }

    /**
     * Change tutor password - PUT /api/tutor/password
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8',
        ]);

        $user = $request->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect',
            ], 400);
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully',
        ]);
    }

    /**
     * Save tutor quiz progress - POST /api/tutor/progress/{courseId}/section
     */
    public function saveQuizProgress(Request $request, $courseId)
    {
        $validated = $request->validate([
            'activity_id' => 'required|integer',
            'progress_percentage' => 'required|integer|min:0|max:100',
            'section_data' => 'nullable|array',
        ]);

        $user = $request->user();

        $updateData = [
            'progress_percentage' => $validated['progress_percentage'],
            'completed_at' => $validated['progress_percentage'] >= 100 ? now() : null,
        ];

        if (isset($validated['section_data'])) {
            $updateData['section_data'] = $validated['section_data'];
        }

        $progress = StudentProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $courseId,
                'activity_type' => 'section',
                'activity_id' => $validated['activity_id'],
            ],
            $updateData
        );

        return response()->json([
            'success' => true,
            'data' => $progress,
            'message' => 'Quiz progress saved',
        ]);
    }

    /**
     * Reset quiz progress - POST /api/tutor/progress/{courseId}/reset
     */
    public function resetQuizProgress(Request $request, $courseId)
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
            'message' => 'Quiz progress reset successfully',
        ]);
    }

    /**
     * Get tutor quiz progress - GET /api/tutor/progress/{courseId}
     */
    public function getQuizProgress(Request $request, $courseId)
    {
        $user = $request->user();

        $progress = StudentProgress::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $progress,
            'message' => 'Quiz progress retrieved',
        ]);
    }

    /**
     * Get all groups for the tutor - GET /api/tutor/groups
     */
    public function getGroups(Request $request)
    {
        $tutor = $request->user();

        $groups = Group::where('tutor_id', $tutor->id)
            ->with(['students' => function ($query) {
                $query->select('users.id', 'users.first_name', 'users.last_name', 'users.email');
            }])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($group) {
                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'students' => $group->students->map(function ($student) {
                        return [
                            'id' => $student->id,
                            'name' => trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: 'Unknown',
                            'email' => $student->email,
                        ];
                    }),
                    'student_count' => $group->students->count(),
                    'created_at' => $group->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $groups,
            'message' => 'Groups retrieved successfully',
        ]);
    }

    /**
     * Create a new group - POST /api/tutor/groups
     */
    public function createGroup(Request $request)
    {
        $tutor = $request->user();

        // Check if tutor already has 5 groups
        $groupCount = Group::where('tutor_id', $tutor->id)->count();
        if ($groupCount >= 5) {
            return response()->json([
                'success' => false,
                'message' => 'You can create a maximum of 5 groups.',
            ], 400);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:users,id',
        ]);

        // Check for duplicate group name for this tutor
        $existingGroup = Group::where('tutor_id', $tutor->id)
            ->where('name', $validated['name'])
            ->first();

        if ($existingGroup) {
            return response()->json([
                'success' => false,
                'message' => 'A group with this name already exists.',
            ], 400);
        }

        // Create the group
        $group = Group::create([
            'name' => $validated['name'],
            'tutor_id' => $tutor->id,
        ]);

        // Attach students to the group
        $group->students()->attach($validated['student_ids']);

        // Load the students relationship
        $group->load(['students' => function ($query) {
            $query->select('users.id', 'users.first_name', 'users.last_name', 'users.email');
        }]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $group->id,
                'name' => $group->name,
                'students' => $group->students->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'name' => trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: 'Unknown',
                        'email' => $student->email,
                    ];
                }),
                'student_count' => $group->students->count(),
                'created_at' => $group->created_at,
            ],
            'message' => 'Group created successfully',
        ], 201);
    }

    /**
     * Update a group - PUT /api/tutor/groups/{id}
     */
    public function updateGroup(Request $request, $id)
    {
        $tutor = $request->user();

        $group = Group::where('id', $id)
            ->where('tutor_id', $tutor->id)
            ->first();

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Group not found.',
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'student_ids' => 'nullable|array|min:1',
            'student_ids.*' => 'exists:users,id',
        ]);

        // Check for duplicate group name if name is being changed
        if (isset($validated['name']) && $validated['name'] !== $group->name) {
            $existingGroup = Group::where('tutor_id', $tutor->id)
                ->where('name', $validated['name'])
                ->where('id', '!=', $id)
                ->first();

            if ($existingGroup) {
                return response()->json([
                    'success' => false,
                    'message' => 'A group with this name already exists.',
                ], 400);
            }

            $group->name = $validated['name'];
            $group->save();
        }

        // Update students if provided
        if (isset($validated['student_ids'])) {
            $group->students()->sync($validated['student_ids']);
        }

        // Load the students relationship
        $group->load(['students' => function ($query) {
            $query->select('users.id', 'users.first_name', 'users.last_name', 'users.email');
        }]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $group->id,
                'name' => $group->name,
                'students' => $group->students->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'name' => trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: 'Unknown',
                        'email' => $student->email,
                    ];
                }),
                'student_count' => $group->students->count(),
                'created_at' => $group->created_at,
            ],
            'message' => 'Group updated successfully',
        ]);
    }

    /**
     * Delete a group - DELETE /api/tutor/groups/{id}
     */
    public function deleteGroup(Request $request, $id)
    {
        $tutor = $request->user();

        $group = Group::where('id', $id)
            ->where('tutor_id', $tutor->id)
            ->first();

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Group not found.',
            ], 404);
        }

        $groupName = $group->name;
        $group->delete();

        return response()->json([
            'success' => true,
            'message' => "Group '{$groupName}' deleted successfully",
        ]);
    }

    /**
     * Get group count for tutor - GET /api/tutor/groups/count
     */
    public function getGroupCount(Request $request)
    {
        $tutor = $request->user();
        $count = Group::where('tutor_id', $tutor->id)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'count' => $count,
                'max' => 5,
                'can_create' => $count < 5,
            ],
            'message' => 'Group count retrieved',
        ]);
    }

    /**
     * Add bulk records for all students in a group - POST /api/tutor/groups/{id}/bulk-records
     */
    public function addBulkRecords(Request $request, $id)
    {
        $tutor = $request->user();

        // Find the group and ensure it belongs to the tutor
        $group = Group::where('id', $id)
            ->where('tutor_id', $tutor->id)
            ->with('students')
            ->first();

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Group not found.',
            ], 404);
        }

        if ($group->students->count() === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No students in this group.',
            ], 400);
        }

        $validated = $request->validate([
            'record.date' => 'required|date',
            'record.attendance' => 'nullable|string',
            'record.reason' => 'nullable|string',
            'record.reschedule' => 'nullable|date',
            'record.homework' => 'nullable|string',
            'record.progress' => 'nullable|string',
            'record.notes' => 'nullable|string',
            'selected_student_ids' => 'nullable|array',
            'selected_student_ids.*' => 'integer',
        ]);

        $newRecord = $validated['record'];
        $selectedStudentIds = $validated['selected_student_ids'] ?? null;

        // Ensure the student_records table exists
        if (!Schema::hasTable('student_records')) {
            return response()->json([
                'success' => false,
                'message' => 'Database table not found. Please run migrations.',
            ], 500);
        }

        $studentsUpdated = 0;

        // Add the record for each selected student (or all if none specified)
        foreach ($group->students as $student) {
            // Skip if selected_student_ids is provided and this student is not in the list
            if ($selectedStudentIds !== null && !in_array($student->id, $selectedStudentIds)) {
                continue;
            }

            // Get existing records
            $existing = DB::table('student_records')
                ->where('student_id', $student->id)
                ->first();

            $records = [];
            if ($existing && $existing->records) {
                $records = json_decode($existing->records, true) ?: [];
            }

            // Add the new record
            $records[] = [
                'date' => $newRecord['date'],
                'attendance' => $newRecord['attendance'] ?? 'Present',
                'reason' => $newRecord['reason'] ?? null,
                'reschedule' => $newRecord['reschedule'] ?? null,
                'homework' => $newRecord['homework'] ?? 'Done',
                'progress' => $newRecord['progress'] ?? null,
                'notes' => $newRecord['notes'] ?? null,
            ];

            // Save the updated records (preserve existing syllabus)
            DB::table('student_records')->updateOrInsert(
                ['student_id' => $student->id],
                [
                    'records' => json_encode($records),
                    'syllabus' => $existing ? ($existing->syllabus ?? null) : null,
                    'updated_at' => now(),
                    'created_at' => $existing ? ($existing->created_at ?? now()) : now(),
                ]
            );

            $studentsUpdated++;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'students_updated' => $studentsUpdated,
                'group_name' => $group->name,
            ],
            'message' => "Records added for {$studentsUpdated} students in group '{$group->name}'",
        ]);
    }

    /**
     * Get common syllabus for all students in a group - GET /api/tutor/groups/{id}/syllabus
     */
    public function getGroupSyllabus(Request $request, $id)
    {
        $tutor = $request->user();

        // Find the group and ensure it belongs to the tutor
        $group = Group::where('id', $id)
            ->where('tutor_id', $tutor->id)
            ->with('students')
            ->first();

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Group not found.',
            ], 404);
        }

        if ($group->students->count() === 0) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'No students in this group.',
            ]);
        }

        // Ensure the student_records table exists
        if (!Schema::hasTable('student_records')) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Student records table not found.',
            ]);
        }

        // Get syllabus from the first student as representative for the group
        $representativeSyllabus = [];
        $firstStudent = $group->students->first();
        
        if ($firstStudent) {
            $syllabus = DB::table('student_records')
                ->where('student_id', $firstStudent->id)
                ->value('syllabus');
            
            if ($syllabus) {
                $representativeSyllabus = json_decode($syllabus, true) ?: [];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $representativeSyllabus,
            'message' => 'Group syllabus retrieved',
        ]);
    }

    /**
     * Add bulk syllabus updates for all students in a group - POST /api/tutor/groups/{id}/bulk-syllabus
     */
    public function addBulkSyllabus(Request $request, $id)
    {
        $tutor = $request->user();

        // Find the group and ensure it belongs to the tutor
        $group = Group::where('id', $id)
            ->where('tutor_id', $tutor->id)
            ->with('students')
            ->first();

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Group not found.',
            ], 404);
        }

        if ($group->students->count() === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No students in this group.',
            ], 400);
        }

        $validated = $request->validate([
            'syllabus' => 'required|array|min:1',
            'syllabus.*.level' => 'required|string',
            'syllabus.*.topic' => 'required|string',
            'syllabus.*.status' => 'required|string|in:Completed,In Progress',
            'syllabus.*.date' => 'nullable|date',
        ]);

        $syllabusUpdates = $validated['syllabus'];

        // Ensure the student_records table exists
        if (!Schema::hasTable('student_records')) {
            return response()->json([
                'success' => false,
                'message' => 'Database table not found. Please run migrations.',
            ], 500);
        }

        $studentsUpdated = 0;

        // Update the syllabus for each student in the group
        foreach ($group->students as $student) {
            // Get existing record (syllabus is stored in student_records.syllabus column)
            $existing = DB::table('student_records')
                ->where('student_id', $student->id)
                ->first();

            $syllabus = [];
            if ($existing && $existing->syllabus) {
                $syllabus = json_decode($existing->syllabus, true) ?: [];
            }

            // Update or add each syllabus topic
            foreach ($syllabusUpdates as $update) {
                $topicKey = $update['level'] . '|' . $update['topic'];
                $found = false;

                // Look for existing topic and update it
                foreach ($syllabus as &$item) {
                    $itemKey = ($item['level'] ?? '') . '|' . ($item['topic'] ?? '');
                    if ($itemKey === $topicKey) {
                        $item['status'] = $update['status'];
                        if ($update['date']) {
                            $item['date'] = $update['date'];
                        }
                        $found = true;
                        break;
                    }
                }

                // If topic not found, add it
                if (!$found) {
                    $syllabus[] = [
                        'level' => $update['level'],
                        'topic' => $update['topic'],
                        'status' => $update['status'],
                        'date' => $update['date'],
                    ];
                }
            }

            // Save the updated syllabus to student_records table (preserve existing records column)
            DB::table('student_records')->updateOrInsert(
                ['student_id' => $student->id],
                [
                    'syllabus' => json_encode($syllabus),
                    'records' => $existing ? ($existing->records ?? null) : null, // Preserve existing records
                    'updated_at' => now(),
                    'created_at' => $existing ? ($existing->created_at ?? now()) : now(),
                ]
            );

            $studentsUpdated++;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'students_updated' => $studentsUpdated,
                'topics_updated' => count($syllabusUpdates),
                'group_name' => $group->name,
            ],
            'message' => "Syllabus updated for {$studentsUpdated} students in group '{$group->name}'",
        ]);
    }

    /**
     * Get attendance for a specific date in a group - GET /api/tutor/groups/{id}/attendance
     */
    public function getGroupAttendance(Request $request, $id)
    {
        $tutor = $request->user();
        $date = $request->query('date');

        if (!$date) {
            return response()->json([
                'success' => false,
                'message' => 'Date is required.',
            ], 400);
        }

        // Find the group and ensure it belongs to the tutor
        $group = Group::where('id', $id)
            ->where('tutor_id', $tutor->id)
            ->with('students')
            ->first();

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Group not found.',
            ], 404);
        }

        // Ensure the student_records table exists
        if (!Schema::hasTable('student_records')) {
            return response()->json([
                'success' => true,
                'data' => ['present_student_ids' => [], 'progress' => ''],
                'message' => 'No records found.',
            ]);
        }

        $studentAttendance = [];
        $studentReasons = [];
        $progress = '';
        $notes = '';

        // Check each student's records for the given date
        foreach ($group->students as $student) {
            $existing = DB::table('student_records')
                ->where('student_id', $student->id)
                ->first();

            if ($existing && $existing->records) {
                $records = json_decode($existing->records, true) ?: [];

                // Find record for the specific date
                foreach ($records as $record) {
                    if (($record['date'] ?? '') === $date) {
                        $attendance = $record['attendance'] ?? '';
                        // Map the attendance value
                        if (in_array($attendance, ['Present', 'Absent (Notice Given)', 'Missed (No Notice)'])) {
                            $studentAttendance[$student->id] = $attendance;
                            $studentReasons[$student->id] = $record['reason'] ?? '';
                        }
                        // Get progress from first present student
                        if ($attendance === 'Present' && empty($progress) && !empty($record['progress'])) {
                            $progress = $record['progress'];
                        }
                        // Get notes from first record that has it
                        if (empty($notes) && !empty($record['notes'])) {
                            $notes = $record['notes'];
                        }
                        break; // Found the record for this date, move to next student
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'student_attendance' => $studentAttendance,
                'student_reasons' => $studentReasons,
                'progress' => $progress,
                'notes' => $notes,
            ],
            'message' => 'Attendance data retrieved.',
        ]);
    }

    /**
     * Track attendance for all students in a group - POST /api/tutor/groups/{id}/bulk-attendance
     * Accepts individual attendance status for each student
     */
    public function addBulkAttendance(Request $request, $id)
    {
        $tutor = $request->user();

        // Find the group and ensure it belongs to the tutor
        $group = Group::where('id', $id)
            ->where('tutor_id', $tutor->id)
            ->with('students')
            ->first();

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Group not found.',
            ], 404);
        }

        if ($group->students->count() === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No students in this group.',
            ], 400);
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'timer' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
            'student_attendance' => 'required|array',
            'student_attendance.*' => 'required|string|in:Present,Absent (Notice Given),Missed (No Notice),--',
            'student_reasons' => 'nullable|array',
        ]);

        $classDate = $validated['date'];
        $timer = $validated['timer'] ?? '';
        $notes = $validated['notes'] ?? '';
        $studentAttendance = $validated['student_attendance'] ?? [];
        $studentReasons = $validated['student_reasons'] ?? [];

        // Ensure the student_records table exists
        if (!Schema::hasTable('student_records')) {
            return response()->json([
                'success' => false,
                'message' => 'Database table not found. Please run migrations.',
            ], 500);
        }

        $presentCount = 0;
        $absentNoticeCount = 0;
        $missedCount = 0;
        $groupSize = $group->students->count();

        // Process each student in the group
        foreach ($group->students as $student) {
            $attendanceStatus = $studentAttendance[$student->id] ?? 'Present';
            $isPresent = $attendanceStatus === 'Present';

            $attendanceReason = $studentReasons[$student->id] ?? '';
            if (empty($attendanceReason)) {
                $attendanceReason = $isPresent ? '' : ($attendanceStatus === 'Absent (Notice Given)' ? 'Notice given' : 'Missed group class');
            }

            // Build the record based on attendance status
            // Store actual timer for all students (including absent) so group sessions
            // can be properly tracked. The 'attendance' field determines display/calculation rules.
            $newRecord = [
                'date' => $classDate,
                'attendance' => $attendanceStatus,
                'reason' => $attendanceReason,
                'reschedule' => '',
                'homework' => $isPresent ? 'Done' : 'N/A',
                'timer' => $timer,
                'notes' => $notes,
                'group_size' => $groupSize,
            ];

            // Get existing records
            $existing = DB::table('student_records')
                ->where('student_id', $student->id)
                ->first();

            $records = [];
            if ($existing && $existing->records) {
                $records = json_decode($existing->records, true) ?: [];
            }

            // Consolidate records by date to prevent duplicates
            $consolidated = [];
            if ($existing && $existing->records) {
                $oldRecords = json_decode($existing->records, true) ?: [];
                foreach ($oldRecords as $rec) {
                    $d = $rec['date'] ?? 'no-date';
                    $consolidated[$d] = $rec;
                }
            }

            // Add or update the new record for this specific date
            $consolidated[$classDate] = $newRecord;

            // Save the updated records (reset keys)
            DB::table('student_records')->updateOrInsert(
                ['student_id' => $student->id],
                [
                    'records' => json_encode(array_values($consolidated)),
                    'syllabus' => $existing ? ($existing->syllabus ?? null) : null, // Preserve existing syllabus
                    'updated_at' => now(),
                    'created_at' => $existing ? ($existing->created_at ?? now()) : now(),
                ]
            );

            // Count by status
            if ($attendanceStatus === 'Present') {
                $presentCount++;
            } elseif ($attendanceStatus === 'Absent (Notice Given)') {
                $absentNoticeCount++;
            } else {
                $missedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'present_count' => $presentCount,
                'absent_notice_count' => $absentNoticeCount,
                'missed_count' => $missedCount,
                'group_name' => $group->name,
                'date' => $classDate,
            ],
            'message' => "Attendance recorded: {$presentCount} present, {$absentNoticeCount} absent (notice), {$missedCount} missed",
        ]);
    }

    /**
     * Get student records - GET /api/tutor/students/{id}/records
     * Only returns records for students assigned to this tutor
     */
    public function getStudentRecords(Request $request, $id)
    {
        $tutor = $request->user();

        // Check if student is assigned to this tutor via tutor_student_assignments table
        $isAssigned = TutorStudentAssignment::where('tutor_id', $tutor->id)
            ->where('student_id', $id)
            ->exists();

        if (!$isAssigned) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found or not assigned to you.',
            ], 404);
        }

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
     * Save student records - POST /api/tutor/students/{id}/records
     * Only saves records for students assigned to this tutor
     */
    public function saveStudentRecords(Request $request, $id)
    {
        $tutor = $request->user();

        // Check if student is assigned to this tutor via tutor_student_assignments table
        $isAssigned = TutorStudentAssignment::where('tutor_id', $tutor->id)
            ->where('student_id', $id)
            ->exists();

        if (!$isAssigned) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found or not assigned to you.',
            ], 404);
        }

        $validated = $request->validate([
            'records' => 'present|array',
            'records.*.id' => 'nullable|string',
            'records.*.saved_at' => 'nullable|string',
            'records.*.date' => 'nullable|date',
            'records.*.attendance' => 'nullable|string',
            'records.*.reason' => 'nullable|string',
            'records.*.reschedule' => 'nullable|date',
            'records.*.homework' => 'nullable|string',
            'records.*.timer' => 'nullable|string',
            'records.*.progress' => 'nullable|string',
            'records.*.notes' => 'nullable|string',
        ]);

        // Ensure the table exists
        if (!Schema::hasTable('student_records')) {
            return response()->json([
                'success' => false,
                'message' => 'Database table not found. Please run migrations.',
            ], 500);
        }

        // Get existing record to preserve created_at and syllabus
        $existing = DB::table('student_records')->where('student_id', $id)->first();

        // Consolidate records by unique ID (allows multiple records per date).
        // Each record gets a stable ID assigned on first save so it can be
        // updated in place on subsequent saves without losing its timestamp.
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

        // Store records as JSON (using array_values to reset keys)
        DB::table('student_records')->updateOrInsert(
            ['student_id' => $id],
            [
                'records' => json_encode(array_values($consolidatedRecords)),
                'syllabus' => $existing ? ($existing->syllabus ?? null) : null, // Preserve existing syllabus
                'updated_at' => now(),
                'created_at' => $existing ? ($existing->created_at ?? now()) : now()
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Records saved successfully',
        ]);
    }

    /**
     * Get student syllabus - GET /api/tutor/students/{id}/syllabus
     * Only returns syllabus for students assigned to this tutor
     */
    public function getStudentSyllabus(Request $request, $id)
    {
        $tutor = $request->user();

        // Check if student is assigned to this tutor via tutor_student_assignments table
        $isAssigned = TutorStudentAssignment::where('tutor_id', $tutor->id)
            ->where('student_id', $id)
            ->exists();

        if (!$isAssigned) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found or not assigned to you.',
            ], 404);
        }

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
     * Save student syllabus - POST /api/tutor/students/{id}/syllabus
     * Only saves syllabus for students assigned to this tutor
     */
    public function saveStudentSyllabus(Request $request, $id)
    {
        $tutor = $request->user();

        // Check if student is assigned to this tutor via tutor_student_assignments table
        $isAssigned = TutorStudentAssignment::where('tutor_id', $tutor->id)
            ->where('student_id', $id)
            ->exists();

        if (!$isAssigned) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found or not assigned to you.',
            ], 404);
        }

        $validated = $request->validate([
            'syllabus' => 'present|array',
            'syllabus.*.level' => 'nullable|string',
            'syllabus.*.topic' => 'required|string',
            'syllabus.*.date' => 'nullable|date',
            'syllabus.*.status' => 'required|string|in:Completed,In Progress',
        ]);

        // Ensure the table exists
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
                'records' => $existing ? ($existing->records ?? null) : null, // Preserve existing records
                'updated_at' => now(),
                'created_at' => $existing ? ($existing->created_at ?? now()) : now()
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Syllabus saved successfully',
        ]);
    }

    /**
     * Get tutor vacation dates - GET /api/tutor/vacations
     */
    public function getVacations(Request $request)
    {
        $tutor = $request->user();

        // Get max allowed days from user_preferences
        $maxDays = UserPreference::where('user_id', $tutor->id)
            ->where('preference_key', 'max_vacation_days')
            ->value('preference_value');
        $maxDays = $maxDays !== null ? (int)$maxDays : 2;

        // Get all vacation ranges for this tutor
        $vacations = TutorVacation::where('tutor_id', $tutor->id)
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

        // Count only approved and pending toward the limit
        $usedCount = TutorVacation::where('tutor_id', $tutor->id)
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
            'message' => 'Vacation requests retrieved',
        ]);
    }

    /**
     * Save a vacation date - POST /api/tutor/vacations
     */
    public function saveVacation(Request $request)
    {
        $tutor = $request->user();

        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:255',
        ]);

        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $requestedDays = $startDate->diffInDays($endDate) + 1;

        // Get max allowed days
        $maxDays = UserPreference::where('user_id', $tutor->id)
            ->where('preference_key', 'max_vacation_days')
            ->value('preference_value');
        $maxDays = $maxDays !== null ? (int)$maxDays : 2;

        // Count current used/pending days
        $usedCount = TutorVacation::where('tutor_id', $tutor->id)
            ->whereIn('status', ['approved', 'pending'])
            ->get()
            ->sum(function($v) {
                return $v->start_date->diffInDays($v->end_date) + 1;
            });

        if (($usedCount + $requestedDays) > $maxDays) {
            $remaining = max(0, $maxDays - (int)$usedCount);
            return response()->json([
                'success' => false,
                'message' => "Request exceeds your vacation quota. Remaining: {$remaining} days.",
            ], 400);
        }

        // Check for overlap (simplified)
        $overlap = TutorVacation::where('tutor_id', $tutor->id)
            ->where(function($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                      ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                      ->orWhere(function($q) use ($validated) {
                          $q->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date']);
                      });
            })
            ->exists();

        if ($overlap) {
            return response()->json([
                'success' => false,
                'message' => 'One or more dates in this range overlap with an existing vacation request.',
            ], 400);
        }

        $vacation = TutorVacation::create([
            'tutor_id' => $tutor->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $vacation->id,
                'start_date' => $vacation->start_date->format('Y-m-d'),
                'end_date' => $vacation->end_date->format('Y-m-d'),
                'total_days' => $requestedDays,
                'status' => $vacation->status,
            ],
            'message' => 'Vacation request submitted for approval',
        ]);
    }

    /**
     * Delete a vacation date - DELETE /api/tutor/vacations/{id}
     */
    public function deleteVacation(Request $request, $id)
    {
        $tutor = $request->user();

        $vacation = TutorVacation::where('id', $id)
            ->where('tutor_id', $tutor->id)
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
     * Submit a timer edit request - POST /api/tutor/timer-edit-request
     */
    public function submitTimerEditRequest(Request $request)
    {
        $tutor = $request->user();

        $validated = $request->validate([
            'student_id' => 'required|integer',
            'record_id' => 'required|string',
            'record_date' => 'required|date',
            'old_timer' => 'nullable|string',
            'new_timer' => 'required|string',
        ]);

        // Check if student is assigned to this tutor
        $isAssigned = TutorStudentAssignment::where('tutor_id', $tutor->id)
            ->where('student_id', $validated['student_id'])
            ->exists();

        if (!$isAssigned) {
            return response()->json([
                'success' => false,
                'message' => 'Student not assigned to you.',
            ], 404);
        }

        // Only 1 edit request allowed per record — once submitted, locked permanently
        $hasRequest = TimerEditRequest::where('tutor_id', $tutor->id)
            ->where('student_id', $validated['student_id'])
            ->where('record_id', $validated['record_id'])
            ->exists();

        if ($hasRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Timer edit already submitted for this record.',
            ], 422);
        }

        $editRequest = TimerEditRequest::create([
            'student_id' => $validated['student_id'],
            'tutor_id' => $tutor->id,
            'record_id' => $validated['record_id'],
            'record_date' => $validated['record_date'],
            'old_timer' => $validated['old_timer'],
            'new_timer' => $validated['new_timer'],
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Timer edit request submitted for admin approval',
            'data' => $editRequest,
        ]);
    }

    /**
     * Get tutor's timer edit requests - GET /api/tutor/timer-edit-requests
     */
    public function getTimerEditRequests(Request $request)
    {
        $tutor = $request->user();

        $requests = TimerEditRequest::where('tutor_id', $tutor->id)
            ->with('student:id,first_name,last_name,email')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $requests,
        ]);
    }

}
