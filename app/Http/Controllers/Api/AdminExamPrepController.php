<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExamPrep;
use App\Models\ExamPrepStudentAccess;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AdminExamPrepController extends Controller
{
    /**
     * Get all exam preps - GET /api/admin/exam-preps
     */
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $cacheKey = 'exam_preps_list_page_' . $page;
        $cacheDuration = AdminController::getCacheDuration('exam_preps');

        // List view doesn't need the (potentially huge) JSON body — only fetched in show()
        $listColumns = [
            'exam_preps.id',
            'exam_preps.exam_prep_title',
            'exam_preps.exam_prep_subtitle',
            'exam_preps.exam_prep_description',
            'exam_preps.exam_prep_category',
            'exam_preps.exam_prep_language',
            'exam_preps.exam_prep_level',
            'exam_preps.exam_prep_image',
            'exam_preps.exam_prep_banner',
            'exam_preps.exam_prep_is_active',
            'exam_preps.display_order',
            'exam_preps.custom_url',
            'exam_preps.custom_url_target',
            'exam_preps.created_at',
            'exam_preps.updated_at',
        ];

        $query = function () use ($listColumns) {
            return ExamPrep::leftJoin('class_types', function ($join) {
                    $join->on('exam_preps.exam_prep_category', '=', 'class_types.class_name')
                         ->orOn('exam_preps.exam_prep_category', '=', 'class_types.name');
                })
                ->select($listColumns)
                ->orderBy('exam_preps.display_order', 'asc')
                ->orderBy('class_types.display_order', 'asc')
                ->orderBy('exam_preps.id', 'asc')
                ->paginate(15);
        };

        $examPreps = $cacheDuration
            ? Cache::remember($cacheKey, $cacheDuration, $query)
            : $query();

        return response()->json([
            'success' => true,
            'data' => $examPreps,
            'message' => 'All exam preps',
        ]);
    }

    /**
     * Get exam prep detail - GET /api/admin/exam-preps/{id}
     */
    public function show(Request $request, $id)
    {
        $examPrep = ExamPrep::find($id);
        if (!$examPrep) {
            return response()->json([
                'success' => false,
                'message' => 'Exam prep not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $examPrep,
            'message' => 'Exam prep detail',
        ]);
    }

    /**
     * Store exam prep - POST /api/admin/exam-preps
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_prep_title' => 'nullable|string|max:200',
            'exam_prep_subtitle' => 'nullable|string|max:300',
            'exam_prep_description' => 'nullable|string',
            'exam_prep_description_title' => 'nullable|string|max:200',
            'exam_prep_category' => 'nullable|string',
            'exam_prep_oral_layout' => 'nullable|string|in:parties,essais',
            'exam_prep_language' => 'nullable|string',
            'exam_prep_level' => 'nullable|string',
            'exam_prep_level_custom' => 'nullable|string',
            'exam_prep_total_texts' => 'nullable|integer|min:1|max:100',
            'exam_prep_json_content' => 'nullable|string',
            'exam_prep_is_active' => 'nullable|boolean',
            'custom_url' => 'nullable|string|max:500',
            'custom_url_target' => 'nullable|string|in:_blank,_self',
        ]);

        if (empty($validated['exam_prep_total_texts'])) {
            $validated['exam_prep_total_texts'] = 5;
        }
        if (empty($validated['exam_prep_is_active'])) {
            $validated['exam_prep_is_active'] = true;
        }

        $allFiles = $request->allFiles();
        if (isset($allFiles['exam_prep_image']) && $allFiles['exam_prep_image']) {
            $file = $allFiles['exam_prep_image'];
            if ($file->getSize() > 5242880) {
                return response()->json(['success' => false, 'message' => 'Exam prep image must not exceed 5MB'], 422);
            }
            try {
                $targetDir = storage_path('app/public/exam-preps/images');
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0755, true);
                }
                if (!is_writable($targetDir)) {
                    Log::error('Exam prep image dir not writable', ['dir' => $targetDir]);
                    return response()->json(['success' => false, 'message' => 'Upload directory not writable: ' . $targetDir], 500);
                }
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($targetDir, $fileName);
                $validated['exam_prep_image'] = 'exam-preps/images/' . $fileName;
            } catch (\Throwable $e) {
                Log::error('Exam prep image upload failed', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'size' => $file->getSize(),
                    'original_name' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                    'extension' => $file->getClientOriginalExtension(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store exam prep image: ' . $e->getMessage(),
                ], 500);
            }
        }

        if (isset($allFiles['exam_prep_banner']) && $allFiles['exam_prep_banner']) {
            $file = $allFiles['exam_prep_banner'];
            if ($file->getSize() > 5242880) {
                return response()->json(['success' => false, 'message' => 'Exam prep banner must not exceed 5MB'], 422);
            }
            try {
                $targetDir = storage_path('app/public/exam-preps/banners');
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0755, true);
                }
                if (!is_writable($targetDir)) {
                    Log::error('Exam prep banner dir not writable', ['dir' => $targetDir]);
                    return response()->json(['success' => false, 'message' => 'Upload directory not writable: ' . $targetDir], 500);
                }
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($targetDir, $fileName);
                $validated['exam_prep_banner'] = 'exam-preps/banners/' . $fileName;
            } catch (\Throwable $e) {
                Log::error('Exam prep banner upload failed', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'size' => $file->getSize(),
                    'original_name' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                    'extension' => $file->getClientOriginalExtension(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store exam prep banner: ' . $e->getMessage(),
                ], 500);
            }
        }

        $examPrep = ExamPrep::create($validated);

        AdminController::clearCacheOnUpdate('exam_preps');

        return response()->json([
            'success' => true,
            'data' => $examPrep,
            'message' => 'Exam prep created',
        ], 201);
    }

    /**
     * Update exam prep - PUT /api/admin/exam-preps/{id}
     */
    public function update(Request $request, $id)
    {
        $examPrep = ExamPrep::findOrFail($id);

        $validated = $request->validate([
            'exam_prep_title' => 'nullable|string|max:200',
            'exam_prep_subtitle' => 'nullable|string|max:300',
            'exam_prep_description' => 'nullable|string',
            'exam_prep_description_title' => 'nullable|string|max:200',
            'exam_prep_category' => 'nullable|string',
            'exam_prep_oral_layout' => 'nullable|string|in:parties,essais',
            'exam_prep_language' => 'nullable|string',
            'exam_prep_level' => 'nullable|string',
            'exam_prep_level_custom' => 'nullable|string',
            'exam_prep_total_texts' => 'nullable|integer|min:1|max:100',
            'exam_prep_json_content' => 'nullable|string',
            'exam_prep_is_active' => 'nullable|boolean',
            'custom_url' => 'nullable|string|max:500',
            'custom_url_target' => 'nullable|string|in:_blank,_self',
        ]);

        if (isset($validated['exam_prep_json_content']) && is_string($validated['exam_prep_json_content'])) {
            $decoded = json_decode($validated['exam_prep_json_content'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid JSON content: ' . json_last_error_msg(),
                ], 422);
            }
        }

        if (empty($validated['exam_prep_total_texts'])) {
            $validated['exam_prep_total_texts'] = 5;
        }
        if (!isset($validated['exam_prep_is_active'])) {
            $validated['exam_prep_is_active'] = true;
        }

        $allFiles = $request->allFiles();

        if (isset($allFiles['exam_prep_image']) && $allFiles['exam_prep_image']) {
            $file = $allFiles['exam_prep_image'];
            if ($file->getSize() > 52428800) {
                return response()->json(['success' => false, 'message' => 'Exam prep image must not exceed 50MB'], 422);
            }
            try {
                $targetDir = storage_path('app/public/exam-preps/images');
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0755, true);
                }
                if (!is_writable($targetDir)) {
                    Log::error('Exam prep image dir not writable', ['dir' => $targetDir]);
                    return response()->json(['success' => false, 'message' => 'Upload directory not writable: ' . $targetDir], 500);
                }
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($targetDir, $fileName);
                $validated['exam_prep_image'] = 'exam-preps/images/' . $fileName;
            } catch (\Throwable $e) {
                Log::error('Exam prep image upload failed (update)', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'size' => $file->getSize(),
                    'original_name' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                    'extension' => $file->getClientOriginalExtension(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store exam prep image: ' . $e->getMessage(),
                ], 500);
            }
        }

        if (isset($allFiles['exam_prep_banner']) && $allFiles['exam_prep_banner']) {
            $file = $allFiles['exam_prep_banner'];
            if ($file->getSize() > 52428800) {
                return response()->json(['success' => false, 'message' => 'Exam prep banner must not exceed 50MB'], 422);
            }
            try {
                $targetDir = storage_path('app/public/exam-preps/banners');
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0755, true);
                }
                if (!is_writable($targetDir)) {
                    Log::error('Exam prep banner dir not writable', ['dir' => $targetDir]);
                    return response()->json(['success' => false, 'message' => 'Upload directory not writable: ' . $targetDir], 500);
                }
                $uniqueFileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($targetDir, $uniqueFileName);
                $validated['exam_prep_banner'] = 'exam-preps/banners/' . $uniqueFileName;
            } catch (\Throwable $e) {
                Log::error('Exam prep banner upload failed (update)', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'size' => $file->getSize(),
                    'original_name' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                    'extension' => $file->getClientOriginalExtension(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store exam prep banner: ' . $e->getMessage(),
                ], 500);
            }
        }

        $examPrep->update($validated);
        $examPrep->refresh();

        $data = $examPrep->toArray();
        if (isset($data['exam_prep_json_content']) && is_array($data['exam_prep_json_content'])) {
            $data['exam_prep_json_content'] = json_encode($data['exam_prep_json_content'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        AdminController::clearCacheOnUpdate('exam_preps');

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Exam prep updated',
        ]);
    }

    /**
     * Delete exam prep - DELETE /api/admin/exam-preps/{id}
     */
    public function destroy(Request $request, $id)
    {
        $examPrep = ExamPrep::findOrFail($id);
        $examPrep->delete();

        AdminController::clearCacheOnUpdate('exam_preps');

        return response()->json([
            'success' => true,
            'message' => 'Exam prep deleted',
        ]);
    }

    /**
     * Upload exam prep image - POST /api/admin/exam-preps/{id}/upload-image
     */
    public function uploadImage(Request $request, $id)
    {
        $request->validate(['banner_image' => 'required|image|max:5120']);
        $examPrep = ExamPrep::findOrFail($id);

        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('exam-preps', 'public');
            $examPrep->exam_prep_banner = $path;
            $examPrep->save();
        }

        return response()->json([
            'success' => true,
            'data' => ['exam_prep_banner' => $examPrep->exam_prep_banner],
            'message' => 'Exam prep image uploaded',
        ]);
    }

    /**
     * Upload exam prep content - POST /api/admin/exam-preps/{id}/upload-content
     */
    public function uploadContent(Request $request, $id)
    {
        $validated = $request->validate(['content_json' => 'required|string']);

        $examPrep = ExamPrep::findOrFail($id);
        $examPrep->exam_prep_json_content = $validated['content_json'];
        $examPrep->save();

        return response()->json([
            'success' => true,
            'data' => $examPrep,
            'message' => 'Exam prep content uploaded',
        ]);
    }

    /**
     * Bulk delete exam preps - POST /api/admin/exam-preps/bulk-delete
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:exam_preps,id',
        ]);

        ExamPrep::whereIn('id', $validated['ids'])->delete();

        AdminController::clearCacheOnUpdate('exam_preps');

        return response()->json([
            'success' => true,
            'message' => 'Exam preps deleted',
        ]);
    }

    /**
     * Reorder exam preps - POST /api/admin/exam-preps/reorder
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|integer|exists:exam_preps,id',
            'order.*.display_order' => 'required|integer|min:1',
        ]);

        foreach ($validated['order'] as $item) {
            ExamPrep::where('id', $item['id'])
                ->update(['display_order' => $item['display_order']]);
        }

        AdminController::clearCacheOnUpdate('exam_preps');

        return response()->json([
            'success' => true,
            'message' => 'Exam preps reordered successfully',
        ]);
    }

    // -------- Admin access management (no tutor-assignment restriction) --------

    /**
     * Per-student access summary for ALL students.
     * GET /api/admin/exam-prep-access-summary
     */
    public function accessSummary(Request $request)
    {
        $totalExamPreps = ExamPrep::count();

        $counts = ExamPrepStudentAccess::selectRaw('student_id, COUNT(*) as granted_count')
            ->groupBy('student_id')
            ->pluck('granted_count', 'student_id');

        $rows = User::where('user_type', 'student')
            ->select('id')
            ->get()
            ->map(function ($u) use ($counts) {
                return [
                    'student_id' => (int)$u->id,
                    'granted_count' => (int)($counts[$u->id] ?? 0),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'total_exam_preps' => $totalExamPreps,
                'students' => $rows,
            ],
            'message' => 'Per-student exam prep access summary',
        ]);
    }

    /**
     * List exam-prep access for a single student (admin).
     * GET /api/admin/students/{studentId}/exam-prep-access
     */
    public function studentAccessList(Request $request, $studentId)
    {
        $student = User::where('id', $studentId)->where('user_type', 'student')->first();
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found'], 404);
        }

        $examPreps = ExamPrep::orderBy('display_order', 'asc')
            ->orderBy('id', 'asc')
            ->get(['id', 'exam_prep_title', 'exam_prep_category', 'exam_prep_is_active']);

        $grantedIds = ExamPrepStudentAccess::where('student_id', $studentId)
            ->pluck('exam_prep_id')
            ->all();
        $grantedSet = array_flip($grantedIds);

        $data = $examPreps->map(function ($ep) use ($grantedSet) {
            return [
                'id' => $ep->id,
                'title' => $ep->exam_prep_title ?? 'Untitled Exam Prep',
                'category' => $ep->exam_prep_category,
                'is_active' => (bool)$ep->exam_prep_is_active,
                'has_access' => isset($grantedSet[$ep->id]),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Student exam prep access list',
        ]);
    }

    /**
     * Grant a student access to a single exam prep (admin).
     * POST /api/admin/exam-preps/{examPrepId}/access
     * Body: { student_id }
     */
    public function adminGrantAccess(Request $request, $examPrepId)
    {
        $validated = $request->validate(['student_id' => 'required|exists:users,id']);
        ExamPrep::findOrFail($examPrepId);

        $admin = $request->user();
        ExamPrepStudentAccess::firstOrCreate(
            ['student_id' => $validated['student_id'], 'exam_prep_id' => $examPrepId],
            ['granted_by_tutor_id' => $admin?->id]
        );

        return response()->json(['success' => true, 'message' => 'Access granted']);
    }

    /**
     * Revoke a student's access to a single exam prep (admin).
     * DELETE /api/admin/exam-preps/{examPrepId}/access/{studentId}
     */
    public function adminRevokeAccess(Request $request, $examPrepId, $studentId)
    {
        ExamPrepStudentAccess::where('exam_prep_id', $examPrepId)
            ->where('student_id', $studentId)
            ->delete();

        return response()->json(['success' => true, 'message' => 'Access revoked']);
    }

    /**
     * Grant a student access to ALL exam preps (admin).
     * POST /api/admin/students/{studentId}/exam-prep-access/grant-all
     */
    public function grantStudentAllExamPreps(Request $request, $studentId)
    {
        $student = User::where('id', $studentId)->where('user_type', 'student')->first();
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found'], 404);
        }

        $admin = $request->user();
        $examPrepIds = ExamPrep::pluck('id')->all();
        $created = 0;
        foreach ($examPrepIds as $epId) {
            $rec = ExamPrepStudentAccess::firstOrCreate(
                ['student_id' => $studentId, 'exam_prep_id' => $epId],
                ['granted_by_tutor_id' => $admin?->id]
            );
            if ($rec->wasRecentlyCreated) {
                $created++;
            }
        }

        return response()->json([
            'success' => true,
            'data' => ['granted_count' => $created, 'total_exam_preps' => count($examPrepIds)],
            'message' => 'Student granted access to all exam preps',
        ]);
    }

    /**
     * Revoke a student's access to ALL exam preps (admin).
     * DELETE /api/admin/students/{studentId}/exam-prep-access/all
     */
    public function revokeStudentAllExamPreps(Request $request, $studentId)
    {
        $deleted = ExamPrepStudentAccess::where('student_id', $studentId)->delete();

        return response()->json([
            'success' => true,
            'data' => ['revoked_count' => $deleted],
            'message' => 'Revoked all exam prep access for student',
        ]);
    }
}
