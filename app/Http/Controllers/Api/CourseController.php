<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Course, CourseSection, CourseLesson, CourseActivity};
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Get all courses
     */
    public function index(Request $request)
    {
        $courses = Course::paginate(15);

        return response()->json([
            'success' => true,
            'data' => $courses,
            'message' => 'All courses',
        ]);
    }

    /**
     * Store a new course
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:class_types,id',
            'title' => 'required|string|max:200',
            'description' => 'required|string',
        ]);

        $course = Course::create($validated);

        return response()->json([
            'success' => true,
            'data' => $course,
            'message' => 'Course created',
        ], 201);
    }

    /**
     * Get course detail
     */
    public function show(string $id)
    {
        try {
            $course = Course::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $course,
                'message' => 'Course detail',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found',
            ], 404);
        }
    }

    /**
     * Update course
     */
    public function update(Request $request, string $id)
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'nullable|exists:class_types,id',
            'title' => 'nullable|string|max:200',
            'description' => 'nullable|string',
        ]);

        $course->update($validated);

        return response()->json([
            'success' => true,
            'data' => $course,
            'message' => 'Course updated',
        ]);
    }

    /**
     * Delete course
     */
    public function destroy(string $id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return response()->json([
            'success' => true,
            'message' => 'Course deleted',
        ]);
    }

    /**
     * Get course sections
     */
    public function sections(string $courseId)
    {
        $sections = CourseSection::where('course_id', $courseId)->get();

        return response()->json([
            'success' => true,
            'data' => $sections,
            'message' => 'Course sections',
        ]);
    }

    /**
     * Get course lessons
     */
    public function lessons(string $courseId)
    {
        $lessons = CourseLesson::whereHas('section', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->get();

        return response()->json([
            'success' => true,
            'data' => $lessons,
            'message' => 'Course lessons',
        ]);
    }

    /**
     * Get course activities
     */
    public function activities(string $courseId)
    {
        $activities = CourseActivity::whereHas('lesson.section', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->get();

        return response()->json([
            'success' => true,
            'data' => $activities,
            'message' => 'Course activities',
        ]);
    }
}
