<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExamPrep;
use App\Models\ExamPrepStudentAccess;
use App\Models\TutorStudentAssignment;
use App\Models\User;
use Illuminate\Http\Request;

class TutorExamPrepController extends Controller
{
    /**
     * Get my exam preps - GET /api/tutor/my-exam-preps
     * Tutors get a read-only list of all active exam preps they can preview.
     */
    public function myExamPreps(Request $request)
    {
        $examPreps = ExamPrep::leftJoin('class_types', function ($join) {
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
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $examPreps,
            'message' => 'My exam preps',
        ]);
    }

    /**
     * Get all exam preps for tutor view - GET /api/tutor/exam-preps
     */
    public function index(Request $request)
    {
        $examPreps = ExamPrep::leftJoin('class_types', function ($join) {
                $join->on('exam_preps.exam_prep_category', '=', 'class_types.class_name')
                     ->orOn('exam_preps.exam_prep_category', '=', 'class_types.name');
            })
            ->select('exam_preps.*')
            ->orderBy('class_types.display_order', 'asc')
            ->orderBy('exam_preps.id', 'asc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $examPreps,
            'message' => 'All exam preps',
        ]);
    }

    /**
     * Assign student to exam prep - POST /api/tutor/exam-prep-assign-student/{examPrepId}
     */
    public function assignStudent(Request $request, $examPrepId)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $tutor = $request->user();

        TutorStudentAssignment::create([
            'tutor_id' => $tutor->id,
            'student_id' => $validated['student_id'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Student assigned to exam prep',
        ]);
    }

    /**
     * List access for an exam prep - GET /api/tutor/exam-preps/{examPrepId}/access
     * Returns the tutor's assigned students with a flag indicating whether they have access.
     */
    public function accessList(Request $request, $examPrepId)
    {
        $tutor = $request->user();
        ExamPrep::findOrFail($examPrepId);

        $studentIds = TutorStudentAssignment::where('tutor_id', $tutor->id)
            ->pluck('student_id')
            ->unique()
            ->all();

        $students = User::whereIn('id', $studentIds)
            ->select('id', 'first_name', 'last_name', 'name', 'email')
            ->orderBy('first_name')
            ->get();

        $grantedIds = ExamPrepStudentAccess::where('exam_prep_id', $examPrepId)
            ->whereIn('student_id', $studentIds)
            ->pluck('student_id')
            ->all();
        $grantedSet = array_flip($grantedIds);

        $data = $students->map(function ($s) use ($grantedSet) {
            $displayName = trim(($s->first_name ?? '') . ' ' . ($s->last_name ?? ''));
            if ($displayName === '') {
                $displayName = $s->name ?: $s->email;
            }
            return [
                'id' => $s->id,
                'name' => $displayName,
                'email' => $s->email,
                'has_access' => isset($grantedSet[$s->id]),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Exam prep access list',
        ]);
    }

    /**
     * Grant access - POST /api/tutor/exam-preps/{examPrepId}/access
     * Body: { student_id }
     */
    public function grantAccess(Request $request, $examPrepId)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $tutor = $request->user();
        ExamPrep::findOrFail($examPrepId);

        $isAssigned = TutorStudentAssignment::where('tutor_id', $tutor->id)
            ->where('student_id', $validated['student_id'])
            ->exists();
        if (!$isAssigned) {
            return response()->json([
                'success' => false,
                'message' => 'Student is not assigned to you',
            ], 403);
        }

        ExamPrepStudentAccess::firstOrCreate(
            [
                'student_id' => $validated['student_id'],
                'exam_prep_id' => $examPrepId,
            ],
            [
                'granted_by_tutor_id' => $tutor->id,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Access granted',
        ]);
    }

    /**
     * Revoke access - DELETE /api/tutor/exam-preps/{examPrepId}/access/{studentId}
     */
    public function revokeAccess(Request $request, $examPrepId, $studentId)
    {
        $tutor = $request->user();

        $isAssigned = TutorStudentAssignment::where('tutor_id', $tutor->id)
            ->where('student_id', $studentId)
            ->exists();
        if (!$isAssigned) {
            return response()->json([
                'success' => false,
                'message' => 'Student is not assigned to you',
            ], 403);
        }

        ExamPrepStudentAccess::where('exam_prep_id', $examPrepId)
            ->where('student_id', $studentId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Access revoked',
        ]);
    }

    /**
     * Per-student access summary for all of a tutor's students.
     * GET /api/tutor/exam-prep-access-summary
     * Returns: { totalExamPreps, students: [{ student_id, granted_count }] }
     */
    public function studentAccessSummary(Request $request)
    {
        $tutor = $request->user();

        $studentIds = TutorStudentAssignment::where('tutor_id', $tutor->id)
            ->pluck('student_id')
            ->unique()
            ->all();

        $totalExamPreps = ExamPrep::count();

        $counts = ExamPrepStudentAccess::whereIn('student_id', $studentIds)
            ->selectRaw('student_id, COUNT(*) as granted_count')
            ->groupBy('student_id')
            ->pluck('granted_count', 'student_id');

        $rows = collect($studentIds)->map(function ($sid) use ($counts) {
            return [
                'student_id' => (int)$sid,
                'granted_count' => (int)($counts[$sid] ?? 0),
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
     * List exam-prep access for a single student - GET /api/tutor/students/{studentId}/exam-prep-access
     */
    public function studentAccessList(Request $request, $studentId)
    {
        $tutor = $request->user();

        $isAssigned = TutorStudentAssignment::where('tutor_id', $tutor->id)
            ->where('student_id', $studentId)
            ->exists();
        if (!$isAssigned) {
            return response()->json([
                'success' => false,
                'message' => 'Student is not assigned to you',
            ], 403);
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
     * Grant a student access to ALL exam preps - POST /api/tutor/students/{studentId}/exam-prep-access/grant-all
     */
    public function grantStudentAllExamPreps(Request $request, $studentId)
    {
        $tutor = $request->user();

        $isAssigned = TutorStudentAssignment::where('tutor_id', $tutor->id)
            ->where('student_id', $studentId)
            ->exists();
        if (!$isAssigned) {
            return response()->json([
                'success' => false,
                'message' => 'Student is not assigned to you',
            ], 403);
        }

        $examPrepIds = ExamPrep::pluck('id')->all();
        $created = 0;
        foreach ($examPrepIds as $epId) {
            $rec = ExamPrepStudentAccess::firstOrCreate(
                ['student_id' => $studentId, 'exam_prep_id' => $epId],
                ['granted_by_tutor_id' => $tutor->id]
            );
            if ($rec->wasRecentlyCreated) {
                $created++;
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'granted_count' => $created,
                'total_exam_preps' => count($examPrepIds),
            ],
            'message' => 'Student granted access to all exam preps',
        ]);
    }

    /**
     * Revoke a student's access to ALL exam preps - DELETE /api/tutor/students/{studentId}/exam-prep-access/all
     */
    public function revokeStudentAllExamPreps(Request $request, $studentId)
    {
        $tutor = $request->user();

        $isAssigned = TutorStudentAssignment::where('tutor_id', $tutor->id)
            ->where('student_id', $studentId)
            ->exists();
        if (!$isAssigned) {
            return response()->json([
                'success' => false,
                'message' => 'Student is not assigned to you',
            ], 403);
        }

        $deleted = ExamPrepStudentAccess::where('student_id', $studentId)->delete();

        return response()->json([
            'success' => true,
            'data' => ['revoked_count' => $deleted],
            'message' => 'Revoked all exam prep access for student',
        ]);
    }

    /**
     * Grant access to all assigned students - POST /api/tutor/exam-preps/{examPrepId}/access/grant-all
     */
    public function grantAllAccess(Request $request, $examPrepId)
    {
        $tutor = $request->user();
        ExamPrep::findOrFail($examPrepId);

        $studentIds = TutorStudentAssignment::where('tutor_id', $tutor->id)
            ->pluck('student_id')
            ->unique()
            ->all();

        $created = 0;
        foreach ($studentIds as $sid) {
            $rec = ExamPrepStudentAccess::firstOrCreate(
                ['student_id' => $sid, 'exam_prep_id' => $examPrepId],
                ['granted_by_tutor_id' => $tutor->id]
            );
            if ($rec->wasRecentlyCreated) {
                $created++;
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'granted_count' => $created,
                'total_students' => count($studentIds),
            ],
            'message' => 'Access granted to all assigned students',
        ]);
    }

    /**
     * Revoke access from all assigned students - DELETE /api/tutor/exam-preps/{examPrepId}/access/all
     */
    public function revokeAllAccess(Request $request, $examPrepId)
    {
        $tutor = $request->user();

        $studentIds = TutorStudentAssignment::where('tutor_id', $tutor->id)
            ->pluck('student_id')
            ->unique()
            ->all();

        $deleted = ExamPrepStudentAccess::where('exam_prep_id', $examPrepId)
            ->whereIn('student_id', $studentIds)
            ->delete();

        return response()->json([
            'success' => true,
            'data' => ['revoked_count' => $deleted],
            'message' => 'Access revoked from all assigned students',
        ]);
    }
}
