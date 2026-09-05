<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\ExamPrep;
use App\Support\DemoCatalogMapper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Public, read-only catalogue for the logged-out demo dashboard.
 *
 * Every response goes through DemoCatalogMapper. Never return a model directly.
 */
class DemoController extends Controller
{
    private const PER_PAGE = 10;
    private const MAX_PER_PAGE = 100;

    /**
     * Callers may ask for a bigger page so the demo can show the whole catalogue.
     * Clamped so a crafted per_page cannot pull the entire table in one request.
     */
    private function perPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', self::PER_PAGE);

        if ($perPage < 1) {
            return self::PER_PAGE;
        }

        return min($perPage, self::MAX_PER_PAGE);
    }

    public function courses(Request $request): JsonResponse
    {
        $courses = Course::leftJoin('class_types', function ($join) {
                $join->on('courses.course_category', '=', 'class_types.class_name')
                     ->orOn('courses.course_category', '=', 'class_types.name');
            })
            ->select('courses.*')
            ->where('courses.course_is_active', true)
            ->orderBy('class_types.display_order', 'asc')
            ->orderBy('courses.id', 'asc')
            ->paginate($this->perPage($request))
            ->through(fn (Course $course) => DemoCatalogMapper::mapCourse($course));

        return response()->json([
            'success' => true,
            'data' => $courses,
            'message' => 'Demo courses',
        ]);
    }

    public function course(int $id): JsonResponse
    {
        $course = Course::where('id', $id)
            ->where('course_is_active', true)
            ->first();

        if (! $course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => DemoCatalogMapper::mapCourse($course),
            'message' => 'Demo course',
        ]);
    }

    public function examPreps(Request $request): JsonResponse
    {
        $examPreps = ExamPrep::leftJoin('class_types', function ($join) {
                $join->on('exam_preps.exam_prep_category', '=', 'class_types.class_name')
                     ->orOn('exam_preps.exam_prep_category', '=', 'class_types.name');
            })
            ->select('exam_preps.*')
            ->where('exam_preps.exam_prep_is_active', true)
            ->orderBy('class_types.display_order', 'asc')
            ->orderBy('exam_preps.id', 'asc')
            ->paginate($this->perPage($request))
            ->through(fn (ExamPrep $examPrep) => DemoCatalogMapper::mapExamPrep($examPrep));

        return response()->json([
            'success' => true,
            'data' => $examPreps,
            'message' => 'Demo exam preps',
        ]);
    }

    public function examPrep(int $id): JsonResponse
    {
        $examPrep = ExamPrep::where('id', $id)
            ->where('exam_prep_is_active', true)
            ->first();

        if (! $examPrep) {
            return response()->json([
                'success' => false,
                'message' => 'Exam prep not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => DemoCatalogMapper::mapExamPrep($examPrep),
            'message' => 'Demo exam prep',
        ]);
    }
}
