<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    StudentController,
    AdminController,
    CourseController,
    EnrollmentController,
    PaymentController,
    TutorController,
    TutorMaterialController,
    UtilityController,
    BookController,
    HomeworkController,
    AdminExamPrepController,
    StudentExamPrepController,
    TutorExamPrepController,
    ClientErrorLogController,
    DemoController,
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Auth Routes (PUBLIC)
// Wrapped in 'web' middleware to ensure session/auth persistence for SSR pages (homepage)
Route::middleware(['web'])->prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);

    // Sync logout - clears stale PHP session when frontend token is missing
    // This is called by Blade pages when they detect auth mismatch
    Route::post('sync-logout', function () {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            // Delete any remaining tokens
            if ($user) {
                $user->tokens()->delete();
            }
            Auth::guard('web')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }
        return response()->json(['success' => true, 'message' => 'Session synced']);
    });

    // Protected auth routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

// Public Settings Route
Route::get('settings/public', [AdminController::class, 'publicSettings']);

// Demo Routes (PUBLIC, read-only — powers the logged-out demo dashboard)
Route::middleware('throttle:30,1')->prefix('demo')->group(function () {
    Route::get('courses', [DemoController::class, 'courses']);
    Route::get('courses/{id}', [DemoController::class, 'course'])->whereNumber('id');
    Route::get('exam-preps', [DemoController::class, 'examPreps']);
    Route::get('exam-preps/{id}', [DemoController::class, 'examPrep'])->whereNumber('id');
});

// Student Portal Maintenance Status (Public)
Route::get('student-portal/maintenance-status', [AdminController::class, 'studentPortalMaintenanceStatus']);

// Public Schema Types Route (for admin pages editor)
Route::get('schema-types', function () {
    return response()->json([
        'success' => true,
        'data' => \App\Services\SchemaService::getSchemaTypes(),
        'message' => 'Schema types retrieved',
    ]);
});

// Protected Routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {

    // Per-user Exam Prep progress (works for ANY authenticated role:
    // student, tutor, admin, superadmin). Each user gets their own row in
    // exam_prep_progress keyed by user_id + exam_prep_id, so an admin
    // previewing won't pollute student data and vice versa.
    Route::get('exam-preps/{id}/my-progress', [StudentExamPrepController::class, 'getProgress']);
    Route::post('exam-preps/{id}/my-progress', [StudentExamPrepController::class, 'saveProgress']);

    // Enrollment Routes
    Route::prefix('enrollments')->group(function () {
        Route::post('/', [EnrollmentController::class, 'store']);
        Route::get('/', [EnrollmentController::class, 'index']);
        Route::get('{id}', [EnrollmentController::class, 'show']);
        Route::put('{id}', [EnrollmentController::class, 'update']);
        Route::delete('{id}', [EnrollmentController::class, 'destroy']);
    });

    // Student Routes
    Route::prefix('student')->group(function () {
        Route::get('dashboard', [StudentController::class, 'dashboard']);
        Route::get('account', [StudentController::class, 'account']);
        Route::put('account', [StudentController::class, 'updateAccount']);
        Route::post('upload-picture', [StudentController::class, 'uploadPicture']);
        Route::put('password', [StudentController::class, 'changePassword']);
        Route::get('subscription', [StudentController::class, 'subscription']);

        // Courses
        Route::get('courses', [StudentController::class, 'courses']);
        Route::get('courses/{id}', [StudentController::class, 'courseDetail']);
        Route::get('browse-courses', [StudentController::class, 'browseCourses']);
        Route::get('courses/by-category/{category}', [StudentController::class, 'coursesByCategory']);
        Route::post('search-courses', [StudentController::class, 'searchCourses']);
        Route::post('enroll', [StudentController::class, 'enroll']);
        Route::get('enrollments', [StudentController::class, 'enrollments']);
        Route::delete('enrollments/{id}', [StudentController::class, 'dropCourse']);

        // Exam Prep
        Route::get('exam-preps', [StudentExamPrepController::class, 'index']);
        Route::get('exam-preps/{id}/progress', [StudentExamPrepController::class, 'getProgress']);
        Route::post('exam-preps/{id}/progress', [StudentExamPrepController::class, 'saveProgress']);
        Route::get('exam-preps/{id}', [StudentExamPrepController::class, 'show']);
        Route::get('browse-exam-preps', [StudentExamPrepController::class, 'browse']);
        Route::get('exam-preps/by-category/{category}', [StudentExamPrepController::class, 'byCategory']);
        Route::post('search-exam-preps', [StudentExamPrepController::class, 'search']);
        Route::post('exam-prep-enroll', [StudentExamPrepController::class, 'enroll']);
        Route::get('exam-prep-enrollments', [StudentExamPrepController::class, 'enrollments']);
        Route::delete('exam-prep-enrollments/{id}', [StudentExamPrepController::class, 'drop']);

        // Learning & Progress
        Route::get('learn/{courseId}', [StudentController::class, 'learn']);
        Route::get('learn/{courseId}/grammar', [StudentController::class, 'learnGrammar']);
        Route::get('learn/{courseId}/reading', [StudentController::class, 'learnReading']);
        Route::get('learn/{courseId}/listening', [StudentController::class, 'learnListening']);
        Route::get('learn/{courseId}/vocabulary', [StudentController::class, 'learnVocabulary']);

        Route::post('progress/{courseId}/reset', [StudentController::class, 'resetProgress']);
        Route::post('progress/{courseId}/{type}', [StudentController::class, 'saveProgress']);
        Route::get('progress/{courseId}', [StudentController::class, 'courseProgress']);
        Route::get('progress/all', [StudentController::class, 'allProgress']);
        Route::get('progress/reading/{id}', [StudentController::class, 'readingProgress']);
        Route::get('progress/listening/{id}', [StudentController::class, 'listeningProgress']);
        Route::get('progress/vocabulary/{id}', [StudentController::class, 'vocabularyProgress']);
        Route::get('progress/grammar/{id}', [StudentController::class, 'grammarProgress']);

        Route::post('mark-complete/{courseId}', [StudentController::class, 'markComplete']);
        Route::get('records', [StudentController::class, 'records']);
        Route::post('records', [StudentController::class, 'addRecord']);

        // Syllabus Progress
        Route::get('syllabus-progress', [StudentController::class, 'syllabusProgress']);

        // Homework Routes
        Route::get('homework', [HomeworkController::class, 'studentIndex']);
        Route::get('homework/pending-count', [HomeworkController::class, 'pendingCount']);
        Route::post('homework/{id}/submit', [HomeworkController::class, 'submit']);
        Route::get('homework/{id}/download', [HomeworkController::class, 'download']);
        Route::get('homework/{id}/download-submission', [HomeworkController::class, 'downloadSubmission']);
        Route::get('homework/attachments/{attachmentId}/download', [HomeworkController::class, 'downloadAttachment']);
        Route::delete('homework/attachments/{attachmentId}', [HomeworkController::class, 'deleteAttachment']);
    });

    // Admin Routes
    Route::prefix('admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard']);
        Route::get('dashboard-all', [AdminController::class, 'dashboardAll']);
        Route::get('insights', [AdminController::class, 'insights']);

        // Courses
        Route::get('courses', [AdminController::class, 'courses']);
        Route::get('courses/{id}', [AdminController::class, 'courseDetail']);
        Route::post('courses', [AdminController::class, 'storeCourse']);
        Route::put('courses/{id}', [AdminController::class, 'updateCourse']);
        Route::delete('courses/{id}', [AdminController::class, 'deleteCourse']);
        Route::post('courses/{id}/upload-image', [AdminController::class, 'uploadImage']);
        Route::post('courses/{id}/upload-content', [AdminController::class, 'uploadContent']);
        Route::post('courses/bulk-delete', [AdminController::class, 'bulkDelete']);
        Route::post('courses/reorder', [AdminController::class, 'reorderCourses']);

        // Exam Prep
        Route::get('exam-preps', [AdminExamPrepController::class, 'index']);
        Route::post('exam-preps/bulk-delete', [AdminExamPrepController::class, 'bulkDelete']);
        Route::post('exam-preps/reorder', [AdminExamPrepController::class, 'reorder']);
        Route::get('exam-preps/{id}', [AdminExamPrepController::class, 'show']);
        Route::post('exam-preps', [AdminExamPrepController::class, 'store']);
        Route::put('exam-preps/{id}', [AdminExamPrepController::class, 'update']);
        Route::delete('exam-preps/{id}', [AdminExamPrepController::class, 'destroy']);
        Route::post('exam-preps/{id}/upload-image', [AdminExamPrepController::class, 'uploadImage']);
        Route::post('exam-preps/{id}/upload-content', [AdminExamPrepController::class, 'uploadContent']);

        // Admin exam prep access management
        Route::get('exam-prep-access-summary', [AdminExamPrepController::class, 'accessSummary']);
        Route::get('students/{studentId}/exam-prep-access', [AdminExamPrepController::class, 'studentAccessList']);
        Route::post('exam-preps/{examPrepId}/access', [AdminExamPrepController::class, 'adminGrantAccess']);
        Route::delete('exam-preps/{examPrepId}/access/{studentId}', [AdminExamPrepController::class, 'adminRevokeAccess']);
        Route::post('students/{studentId}/exam-prep-access/grant-all', [AdminExamPrepController::class, 'grantStudentAllExamPreps']);
        Route::delete('students/{studentId}/exam-prep-access/all', [AdminExamPrepController::class, 'revokeStudentAllExamPreps']);

        // Students
        Route::get('students', [AdminController::class, 'students']);
        Route::get('students/export', [AdminController::class, 'exportStudents']);
        Route::post('students/bulk-delete', [AdminController::class, 'bulkDeleteStudents']);
        Route::post('students', [AdminController::class, 'storeStudent']);
        // Specific routes must come before general {id} routes
        Route::get('students/{id}/records', [AdminController::class, 'getStudentRecords']);
        Route::post('students/{id}/records', [AdminController::class, 'saveStudentRecords']);
        Route::get('students/{id}/syllabus', [AdminController::class, 'getStudentSyllabus']);
        Route::post('students/{id}/syllabus', [AdminController::class, 'saveStudentSyllabus']);
        Route::post('students/{id}/assign-tutor', [AdminController::class, 'assignTutor']);
        Route::post('students/{id}/toggle-payment', [AdminController::class, 'togglePaymentConfirmed']);
        // General student routes
        Route::get('students/{id}', [AdminController::class, 'studentDetail']);
        Route::put('students/{id}', [AdminController::class, 'updateStudent']);
        Route::delete('students/{id}', [AdminController::class, 'deleteStudent']);

        // Tutors
        Route::get('tutors', [AdminController::class, 'tutors']);
        Route::get('tutors/{id}/stats', [AdminController::class, 'tutorStats']);
        Route::post('tutors/{id}/pay-rates', [AdminController::class, 'updateTutorPayRates']);
        Route::post('tutors/{id}/remove-student', [AdminController::class, 'removeStudentFromTutor']);
        Route::post('tutors/{id}/update-status', [AdminController::class, 'updateTutorStatus']);
        Route::get('tutors/{id}/notes', [AdminController::class, 'getTutorNotes']);
        Route::post('tutors/{id}/notes', [AdminController::class, 'saveTutorNote']);
        Route::delete('tutors/{id}/notes/{noteId}', [AdminController::class, 'deleteTutorNote']);
        Route::get('tutors/{id}', [AdminController::class, 'tutorDetail']);
        Route::post('tutors', [AdminController::class, 'storeTutor']);
        Route::put('tutors/{id}', [AdminController::class, 'updateTutor']);
        Route::delete('tutors/{id}', [AdminController::class, 'deleteTutor']);

        // Tutor Vacation Management (Admin)
        Route::get('tutors/{id}/vacations', [AdminController::class, 'getTutorVacations']);
        Route::post('tutors/{id}/vacations/{vacationId}/approve', [AdminController::class, 'approveVacation']);
        Route::post('tutors/{id}/vacations/{vacationId}/reject', [AdminController::class, 'rejectVacation']);
        Route::post('tutors/{id}/vacation-settings', [AdminController::class, 'updateTutorVacationSettings']);
        Route::delete('tutors/{id}/vacations/{vacationId}', [AdminController::class, 'deleteTutorVacation']);

        // Timer Edit Requests (Admin)
        Route::get('tutors/{id}/timer-edit-requests', [AdminController::class, 'getTimerEditRequests']);
        Route::post('tutors/{id}/timer-edit-requests/{requestId}/approve', [AdminController::class, 'approveTimerEdit']);
        Route::post('tutors/{id}/timer-edit-requests/{requestId}/reject', [AdminController::class, 'rejectTimerEdit']);


        // General
        Route::get('users', [AdminController::class, 'users']);
        Route::post('users/bulk-delete', [AdminController::class, 'usersBulkDelete']);

        // Manage Users (Admin/Tutor)
        Route::get('manage-users', [AdminController::class, 'manageUsers']);
        Route::post('manage-users', [AdminController::class, 'storeManageUser']);
        Route::put('manage-users/{id}', [AdminController::class, 'updateManageUser']);
        Route::delete('manage-users/{id}', [AdminController::class, 'deleteManageUser']);

        // Admin Preview Progress
        Route::post('preview-progress/{courseId}/section', [AdminController::class, 'savePreviewProgress']);
        Route::post('preview-progress/{courseId}/results', [AdminController::class, 'savePreviewSectionResults']);
        Route::post('preview-progress/{courseId}/reset', [AdminController::class, 'resetPreviewProgress']);
        Route::get('preview-progress/{courseId}', [AdminController::class, 'getPreviewProgress']);

        // Enrollments
        Route::get('enrollments', [AdminController::class, 'enrollments']);
        Route::get('enrollments/{id}', [AdminController::class, 'enrollmentDetail']);
        Route::put('enrollments/{id}', [AdminController::class, 'updateEnrollment']);
        Route::delete('enrollments/{id}', [AdminController::class, 'cancelEnrollment']);
        Route::get('class-types', [AdminController::class, 'classTypes']);
        Route::post('class-types', [AdminController::class, 'storeClassType']);
        Route::post('class-types/reorder', [AdminController::class, 'reorderClassTypes']);
        Route::put('class-types/{id}', [AdminController::class, 'updateClassType']);
        Route::delete('class-types/{id}', [AdminController::class, 'deleteClassType']);
        Route::get('enrollment-stats', [AdminController::class, 'enrollmentStats']);

        // Payments
        Route::get('payments', [AdminController::class, 'payments']);
        Route::get('payments/{id}', [AdminController::class, 'paymentDetail']);
        Route::get('subscriptions', [AdminController::class, 'subscriptions']);
        Route::put('payments/{id}/status', [AdminController::class, 'updatePaymentStatus']);

        // Coupons
        Route::get('coupons', [AdminController::class, 'coupons']);
        Route::post('coupons', [AdminController::class, 'storeCoupon']);
        Route::put('coupons/{id}', [AdminController::class, 'updateCoupon']);
        Route::delete('coupons/{id}', [AdminController::class, 'deleteCoupon']);

        // Pages Management
        Route::get('pages', [AdminController::class, 'pages']);
        Route::post('pages', [AdminController::class, 'storePage']);
        Route::get('pages/{id}', [AdminController::class, 'pageDetail']);
        Route::put('pages/{id}', [AdminController::class, 'updatePage']);
        Route::delete('pages/{id}', [AdminController::class, 'deletePage']);

        // Settings
        Route::get('settings', [AdminController::class, 'settings']);
        Route::post('settings/upload-logo', [AdminController::class, 'uploadLogo']);
        Route::put('settings/{key}', [AdminController::class, 'updateSetting']);
        Route::get('email-settings', [AdminController::class, 'emailSettings']);
        Route::put('email-settings', [AdminController::class, 'updateEmailSettings']);
        Route::post('test-smtp-connection', [AdminController::class, 'testSmtpConnection']);
        Route::post('test-email', [AdminController::class, 'testEmail']);
        Route::post('google-oauth/authorize', [AdminController::class, 'googleOAuthAuthorize']);
        Route::get('stripe-settings', [AdminController::class, 'stripeSettings']);
        Route::put('stripe-settings', [AdminController::class, 'updateStripeSettings']);

        // Import
        Route::post('import-enrollments', [AdminController::class, 'importEnrollments']);
        Route::post('import-wordpress-passwords', [AdminController::class, 'importWordPressPasswords']);
        Route::get('reset-import-stats', [AdminController::class, 'getResetImportStats']);
        Route::post('reset-import-data', [AdminController::class, 'resetImportData']);

        // Testing
        Route::post('test-password', [AdminController::class, 'testPassword']);

        // Cache Management
        Route::get('cache/status', [AdminController::class, 'cacheStatus']);
        Route::get('cache/settings', [AdminController::class, 'cacheSettings']);
        Route::post('cache/settings', [AdminController::class, 'saveCacheSettings']);
        Route::post('cache/clear/{key}', [AdminController::class, 'clearCache']);
        Route::post('cache/clear-all', [AdminController::class, 'clearAllCache']);

        // Google Meet Attendance Tracker
        Route::get('meet-logs', [AdminController::class, 'getMeetLogs']);
        Route::post('meet-logs/sync', [AdminController::class, 'syncMeetLogs']);
        Route::get('meet-logs/staff-emails', [AdminController::class, 'getUniqueStaffEmails']);
        Route::post('meet-logs/staff-duration', [AdminController::class, 'calculateStaffDuration']);
        Route::get('meet-logs/export', [AdminController::class, 'exportMeetLogs']);
    });

    // Tutor Routes
    Route::prefix('tutor')->group(function () {
        Route::get('dashboard', [TutorController::class, 'dashboard']);
        Route::get('account', [TutorController::class, 'account']);
        Route::put('account', [TutorController::class, 'updateAccount']);
        Route::put('earnings-period', [TutorController::class, 'updateEarningsPeriod']);
        Route::put('password', [TutorController::class, 'changePassword']);
        Route::get('my-courses', [TutorController::class, 'myCourses']);
        Route::get('courses', [TutorController::class, 'courses']);
        Route::get('students', [TutorController::class, 'students']);
        Route::post('add-student', [TutorController::class, 'addStudent']);
        Route::post('assign-student/{courseId}', [TutorController::class, 'assignStudent']);
        Route::delete('remove-student/{studentId}', [TutorController::class, 'removeStudent']);
        Route::get('student/{id}/progress', [TutorController::class, 'studentProgress']);

        // Exam Prep
        Route::get('my-exam-preps', [TutorExamPrepController::class, 'myExamPreps']);
        Route::get('exam-preps', [TutorExamPrepController::class, 'index']);
        Route::post('exam-prep-assign-student/{examPrepId}', [TutorExamPrepController::class, 'assignStudent']);
        Route::get('exam-preps/{examPrepId}/access', [TutorExamPrepController::class, 'accessList']);
        Route::post('exam-preps/{examPrepId}/access', [TutorExamPrepController::class, 'grantAccess']);
        Route::post('exam-preps/{examPrepId}/access/grant-all', [TutorExamPrepController::class, 'grantAllAccess']);
        Route::delete('exam-preps/{examPrepId}/access/all', [TutorExamPrepController::class, 'revokeAllAccess']);
        Route::delete('exam-preps/{examPrepId}/access/{studentId}', [TutorExamPrepController::class, 'revokeAccess']);

        // Per-student exam prep access (grant a single student access to many exam preps)
        Route::get('exam-prep-access-summary', [TutorExamPrepController::class, 'studentAccessSummary']);
        Route::get('students/{studentId}/exam-prep-access', [TutorExamPrepController::class, 'studentAccessList']);
        Route::post('students/{studentId}/exam-prep-access/grant-all', [TutorExamPrepController::class, 'grantStudentAllExamPreps']);
        Route::delete('students/{studentId}/exam-prep-access/all', [TutorExamPrepController::class, 'revokeStudentAllExamPreps']);

        // Homework Routes
        Route::get('homework', [HomeworkController::class, 'tutorIndex']);
        Route::get('homework/students', [HomeworkController::class, 'getAssignedStudents']);
        Route::get('homework/submission-count', [HomeworkController::class, 'submissionCount']);
        Route::post('homework', [HomeworkController::class, 'store']);
        Route::get('homework/{id}/download', [HomeworkController::class, 'download']);
        Route::get('homework/{id}/download-submission', [HomeworkController::class, 'downloadSubmission']);
        Route::get('homework/attachments/{attachmentId}/download', [HomeworkController::class, 'downloadAttachment']);
        Route::delete('homework/attachments/{attachmentId}', [HomeworkController::class, 'deleteAttachment']);
        Route::delete('homework/{id}', [HomeworkController::class, 'destroy']);

        // Material Routes (Tutor only)
        Route::get('materials', [TutorMaterialController::class, 'index']);
        Route::get('materials/download', [TutorMaterialController::class, 'download']);

        // Tutor Quiz Progress Routes
        Route::post('progress/{courseId}/section', [TutorController::class, 'saveQuizProgress']);
        Route::post('progress/{courseId}/reset', [TutorController::class, 'resetQuizProgress']);
        Route::get('progress/{courseId}', [TutorController::class, 'getQuizProgress']);

        // Tutor Groups Routes
        Route::get('groups', [TutorController::class, 'getGroups']);
        Route::get('groups/count', [TutorController::class, 'getGroupCount']);
        Route::post('groups', [TutorController::class, 'createGroup']);
        Route::put('groups/{id}', [TutorController::class, 'updateGroup']);
        Route::delete('groups/{id}', [TutorController::class, 'deleteGroup']);
        Route::post('groups/{id}/bulk-records', [TutorController::class, 'addBulkRecords']);
        Route::get('groups/{id}/syllabus', [TutorController::class, 'getGroupSyllabus']);
        Route::post('groups/{id}/bulk-syllabus', [TutorController::class, 'addBulkSyllabus']);
        Route::get('groups/{id}/attendance', [TutorController::class, 'getGroupAttendance']);
        Route::post('groups/{id}/bulk-attendance', [TutorController::class, 'addBulkAttendance']);

        // Tutor Vacation Routes
        Route::get('vacations', [TutorController::class, 'getVacations']);
        Route::post('vacations', [TutorController::class, 'saveVacation']);
        Route::delete('vacations/{id}', [TutorController::class, 'deleteVacation']);

        // Timer Edit Requests (Tutor)
        Route::post('timer-edit-request', [TutorController::class, 'submitTimerEditRequest']);
        Route::get('timer-edit-requests', [TutorController::class, 'getTimerEditRequests']);

        // Tutor Student Records & Syllabus Routes (for individual students)
        Route::get('students/{id}/records', [TutorController::class, 'getStudentRecords']);
        Route::post('students/{id}/records', [TutorController::class, 'saveStudentRecords']);
        Route::get('students/{id}/syllabus', [TutorController::class, 'getStudentSyllabus']);
        Route::post('students/{id}/syllabus', [TutorController::class, 'saveStudentSyllabus']);


    });

    // User Preferences Routes
    Route::prefix('preferences')->group(function () {
        Route::get('{key}', [AdminController::class, 'getPreference']);
        Route::put('{key}', [AdminController::class, 'setPreference']);
    });
});

// Payment Routes (some public for webhook)
Route::prefix('payment')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('create-intent', [PaymentController::class, 'createIntent']);
        Route::post('create-checkout-session', [PaymentController::class, 'createCheckoutSession']);
        Route::post('confirm', [PaymentController::class, 'confirmPayment']);
        Route::get('status/{intentId}', [PaymentController::class, 'checkStatus']);
    });
    // Public routes
    Route::get('checkout-success', [PaymentController::class, 'checkoutSuccess']);
    Route::post('webhook', [PaymentController::class, 'webhook']);
});

// Public Course Routes (no authentication required for viewing)
Route::get('courses/{id}', [CourseController::class, 'show']);

// Frontend error reporter (browsers POST JS errors here so we can read them on the server)
Route::post('client-error-log', [ClientErrorLogController::class, 'store'])
    ->middleware('throttle:60,1');

// Public Exam Prep Routes (no authentication required for viewing)
Route::get('exam-preps/{id}', [AdminExamPrepController::class, 'show']);
Route::get('exam-prep-media/{token}', [StudentExamPrepController::class, 'streamMedia'])->where('token', '[A-Za-z0-9_\-]+');

// Protected Books Routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('books', [BookController::class, 'index']);
    Route::get('books/{id}', [BookController::class, 'show']);
    Route::post('books', [BookController::class, 'store']);
    Route::put('books/{id}', [BookController::class, 'update']);
    Route::delete('books/{id}', [BookController::class, 'destroy']);
});

// Public Page Routes (no authentication required)
Route::get('pages/by-slug', [AdminController::class, 'getPageBySlug']);

// Utility Routes (public)
Route::prefix('utility')->group(function () {
    Route::get('search', [UtilityController::class, 'search']);
    Route::post('validate-coupon', [UtilityController::class, 'validateCoupon']);
    Route::get('system-status', [UtilityController::class, 'systemStatus']);
});

// Public Routes (no authentication required)
Route::get('class-types', [AdminController::class, 'classTypes']);

// Public Stripe publishable key (safe to expose)
Route::get('stripe/publishable-key', function () {
    $publishableKey = env('STRIPE_PUBLISHABLE_KEY', '');
    return response()->json([
        'success' => true,
        'data' => [
            'publishable_key' => $publishableKey,
        ],
    ]);
});

// Google OAuth Callback (public route - Google redirects here)
Route::get('admin/google-oauth/callback', [AdminController::class, 'googleOAuthCallback']);

// Auth Routes (for session persistence, excluded from CSRF for SPA compatibility)
Route::middleware('web')->group(function () {
    Route::post('auth/login', [AuthController::class, 'login'])->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    Route::post('auth/register', [AuthController::class, 'register'])->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    Route::post('auth/logout', [AuthController::class, 'logout'])->middleware('auth:web');
    Route::get('auth/user', [AuthController::class, 'user'])->middleware('auth:web');
});
