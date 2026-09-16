<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExamPrep;
use App\Models\ExamPrepEnrollment;
use App\Models\ExamPrepProgress;
use App\Models\ExamPrepStudentAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class StudentExamPrepController extends Controller
{
    /**
     * Get student exam preps - GET /api/student/exam-preps
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            $allExamPreps = ExamPrep::leftJoin('class_types', function ($join) {
                    $join->on('exam_preps.exam_prep_category', '=', 'class_types.class_name')
                         ->orOn('exam_preps.exam_prep_category', '=', 'class_types.name');
                })
                ->select('exam_preps.*')
                ->where(function ($query) {
                    $query->where('exam_preps.exam_prep_is_active', true)
                          ->orWhereNull('exam_preps.exam_prep_is_active');
                })
                ->orderBy('class_types.display_order', 'asc')
                ->orderBy('exam_preps.id', 'asc')
                ->get();

            $enrollments = ExamPrepEnrollment::where('user_id', $user->id)
                ->whereNotNull('exam_prep_id')
                ->get()
                ->keyBy('exam_prep_id');

            $unlockedIds = ExamPrepStudentAccess::where('student_id', $user->id)
                ->pluck('exam_prep_id')
                ->all();
            $unlockedSet = array_flip($unlockedIds);

            $data = $allExamPreps->map(function ($examPrep) use ($enrollments, $unlockedSet) {
                $enrollment = $enrollments->get($examPrep->id);
                $imageUrl = $examPrep->exam_prep_image ? asset('storage/' . $examPrep->exam_prep_image) : null;
                $isLocked = !isset($unlockedSet[$examPrep->id]);

                return [
                    'id' => $examPrep->id,
                    'enrollment_id' => $enrollment ? $enrollment->id : null,
                    'title' => $examPrep->exam_prep_title ?? 'Untitled Exam Prep',
                    'subtitle' => $examPrep->exam_prep_subtitle ?? '',
                    'description' => $examPrep->exam_prep_description ?? '',
                    'image_url' => $imageUrl,
                    'category' => $examPrep->exam_prep_category ?? '',
                    'exam_prep_category' => $examPrep->exam_prep_category ?? '',
                    'language' => $examPrep->exam_prep_language ?? '',
                    'level' => $examPrep->exam_prep_level ?? 'Beginner',
                    'progress' => 0,
                    'status' => $enrollment ? ($enrollment->status === 'completed' ? 'Completed' : 'In Progress') : 'Not Started',
                    'enrollment_date' => $enrollment ? $enrollment->created_at : null,
                    'enrollment_status' => $enrollment ? $enrollment->status : null,
                    'is_enrolled' => $enrollment ? true : false,
                    'is_locked' => $isLocked,
                    'custom_url' => $examPrep->custom_url ?? null,
                    'custom_url_target' => $examPrep->custom_url_target ?? '_blank',
                ];
            });

            $page = (int) $request->get('page', 1);
            $perPage = 10;
            $total = $data->count();
            $items = $data->slice(($page - 1) * $perPage, $perPage)->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'data' => $items,
                    'current_page' => $page,
                    'last_page' => (int) ceil($total / $perPage),
                    'per_page' => $perPage,
                    'total' => $total,
                ],
                'message' => 'Student exam preps',
            ]);
        } catch (\Exception $e) {
            Log::error('Student exam preps error: ' . $e->getMessage(), [
                'user_id' => $request->user()?->id,
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to load exam preps: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get exam prep detail - GET /api/student/exam-preps/{id}
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        $cacheKey = "exam_prep_detail_student_{$id}";
        $cacheDuration = AdminController::getCacheDuration('exam_preps');

        if ($cacheDuration) {
            $examPrep = Cache::remember($cacheKey, $cacheDuration, fn () => ExamPrep::find($id));
        } else {
            $examPrep = ExamPrep::find($id);
        }

        if (!$examPrep) {
            return response()->json(['success' => false, 'message' => 'Exam prep not found'], 404);
        }

        $enrollment = ExamPrepEnrollment::where('user_id', $user->id)
            ->where('exam_prep_id', $id)
            ->first();

        $hasAccess = ExamPrepStudentAccess::where('student_id', $user->id)
            ->where('exam_prep_id', $id)
            ->exists();

        return response()->json([
            'success' => true,
            'data' => [
                'examPrep' => $examPrep,
                'enrolled' => $enrollment ? true : false,
                'enrollment' => $enrollment,
                'is_locked' => !$hasAccess,
            ],
            'message' => 'Exam prep detail',
        ]);
    }

    /**
     * Browse all exam preps - GET /api/student/browse-exam-preps
     */
    public function browse(Request $request)
    {
        $examPreps = ExamPrep::leftJoin('class_types', function ($join) {
                $join->on('exam_preps.exam_prep_category', '=', 'class_types.class_name')
                     ->orOn('exam_preps.exam_prep_category', '=', 'class_types.name');
            })
            ->select('exam_preps.*')
            ->orderBy('class_types.display_order', 'asc')
            ->orderBy('exam_preps.id', 'asc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $examPreps,
            'message' => 'Browse exam preps',
        ]);
    }

    /**
     * Get exam preps by category - GET /api/student/exam-preps/by-category/{category}
     */
    public function byCategory(Request $request, $category)
    {
        $examPreps = ExamPrep::leftJoin('class_types', function ($join) {
                $join->on('exam_preps.exam_prep_category', '=', 'class_types.class_name')
                     ->orOn('exam_preps.exam_prep_category', '=', 'class_types.name');
            })
            ->select('exam_preps.*')
            ->where('exam_preps.exam_prep_category', $category)
            ->orderBy('class_types.display_order', 'asc')
            ->orderBy('exam_preps.id', 'asc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $examPreps,
            'message' => 'Exam preps by category',
        ]);
    }

    /**
     * Search exam preps - POST /api/student/search-exam-preps
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|min:3',
            'category' => 'nullable|string',
            'level' => 'nullable|string',
        ]);

        $query = ExamPrep::query();

        if (!empty($validated['query'])) {
            $q = $validated['query'];
            $query->where(function ($sub) use ($q) {
                $sub->where('exam_prep_title', 'like', "%{$q}%")
                    ->orWhere('exam_prep_description', 'like', "%{$q}%");
            });
        }

        if (!empty($validated['category'])) {
            $query->where('exam_prep_category', $validated['category']);
        }

        $examPreps = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $examPreps,
            'message' => 'Exam prep search results',
        ]);
    }

    /**
     * Enroll in an exam prep - POST /api/student/exam-prep-enroll
     */
    public function enroll(Request $request)
    {
        $validated = $request->validate([
            'exam_prep_id' => 'required|exists:exam_preps,id',
            'class_type_id' => 'nullable|exists:class_types,id',
        ]);

        $user = $request->user();

        $existing = ExamPrepEnrollment::where('user_id', $user->id)
            ->where('exam_prep_id', $validated['exam_prep_id'])
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Already enrolled in this exam prep',
            ], 400);
        }

        $enrollment = ExamPrepEnrollment::create([
            'user_id' => $user->id,
            'exam_prep_id' => $validated['exam_prep_id'],
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
     * Get student exam prep enrollments - GET /api/student/exam-prep-enrollments
     */
    public function enrollments(Request $request)
    {
        $user = $request->user();
        $enrollments = ExamPrepEnrollment::where('user_id', $user->id)
            ->with('examPrep:id,exam_prep_title,exam_prep_subtitle,exam_prep_image,exam_prep_category,exam_prep_level')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $enrollments,
            'message' => 'Student exam prep enrollments',
        ]);
    }

    /**
     * Drop exam prep - DELETE /api/student/exam-prep-enrollments/{id}
     */
    public function drop(Request $request, $id)
    {
        $user = $request->user();
        $enrollment = ExamPrepEnrollment::where('user_id', $user->id)->findOrFail($id);
        $enrollment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Exam prep dropped successfully',
        ]);
    }

    /**
     * Get current user's progress state for an exam prep.
     * GET /api/student/exam-preps/{id}/progress
     */
    public function getProgress(Request $request, $id)
    {
        $user = $request->user();
        $progress = ExamPrepProgress::where('user_id', $user->id)
            ->where('exam_prep_id', $id)
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'state' => $progress ? $progress->state : null,
                'updated_at' => $progress ? $progress->updated_at : null,
            ],
        ]);
    }

    /**
     * Upsert progress state for the current user's exam prep attempt.
     * POST /api/student/exam-preps/{id}/progress
     */
    public function saveProgress(Request $request, $id)
    {
        $user = $request->user();
        $validated = $request->validate([
            'state' => 'required|array',
        ]);

        ExamPrepProgress::updateOrCreate(
            ['user_id' => $user->id, 'exam_prep_id' => $id],
            ['state' => $validated['state']]
        );

        return response()->json([
            'success' => true,
            'message' => 'Progress saved',
        ]);
    }

    /**
     * Stream an exam prep media asset (audio/image) without exposing the
     * original file extension in the URL. The token is base64url-encoded
     * relative path under storage/app/public/exam-preps/.
     *
     * GET /api/exam-prep-media/{token}
     *
     * Range requests are honored automatically by BinaryFileResponse so
     * audio seeks chunk-by-chunk like a streaming service.
     */
    public function streamMedia(Request $request, $token)
    {
        $padded = strtr($token, '-_', '+/');
        $padded .= str_repeat('=', (4 - strlen($padded) % 4) % 4);
        $relPath = base64_decode($padded, true);

        if ($relPath === false || $relPath === '' || str_contains($relPath, '..') || str_starts_with($relPath, '/')) {
            abort(400, 'Invalid token');
        }

        $fullPath = storage_path('app/public/exam-preps/' . $relPath);
        $real = realpath($fullPath);
        $base = realpath(storage_path('app/public/exam-preps'));

        if (!$real || !$base || !str_starts_with($real, $base)) {
            abort(404);
        }

        $ext = strtolower(pathinfo($real, PATHINFO_EXTENSION));
        $mime = match ($ext) {
            'mp3' => 'audio/mpeg',
            'm4a', 'aac' => 'audio/aac',
            'ogg', 'oga' => 'audio/ogg',
            'wav' => 'audio/wav',
            'webm' => 'audio/webm',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'gif' => 'image/gif',
            default => 'application/octet-stream',
        };

        return response()->file($real, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline',
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'public, max-age=86400',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
