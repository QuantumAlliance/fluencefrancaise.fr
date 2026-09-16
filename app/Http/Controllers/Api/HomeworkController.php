<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class HomeworkController extends Controller
{
    /**
     * Get all homework for tutor (uploaded by them)
     */
    public function tutorIndex(Request $request)
    {
        $tutor = Auth::user();

        $homework = Homework::where('tutor_id', $tutor->id)
            ->with(['student:id,first_name,last_name,email'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $homework,
        ]);
    }

    /**
     * Get all homework for student (assigned to them)
     */
    public function studentIndex(Request $request)
    {
        $student = Auth::user();

        $homework = Homework::where('student_id', $student->id)
            ->with(['tutor:id,first_name,last_name'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $homework,
        ]);
    }

    /**
     * Get students assigned to tutor
     */
    public function getAssignedStudents()
    {
        $tutor = Auth::user();

        $students = User::where('user_type', 'student')
            ->whereHas('studentAssignments', function ($query) use ($tutor) {
                $query->where('tutor_id', $tutor->id);
            })
            ->select('id', 'first_name', 'last_name', 'email')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $students,
        ]);
    }

    /**
     * Tutor uploads homework
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'student_id' => 'required|exists:users,id',
            'file' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ]);

        $tutor = Auth::user();

        // Store the file
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('homework', $fileName, 'public');

        $homework = Homework::create([
            'tutor_id' => $tutor->id,
            'student_id' => $request->student_id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'uploaded_at' => now(),
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Homework uploaded successfully',
            'data' => $homework->load('student:id,first_name,last_name,email'),
        ]);
    }

    /**
     * Student submits completed homework
     */
    public function submit(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ]);

        $student = Auth::user();
        $homework = Homework::where('id', $id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        // Store the submission file
        $file = $request->file('file');
        $fileName = time() . '_submission_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('homework/submissions', $fileName, 'public');

        // Delete old submission if exists
        if ($homework->submission_path) {
            Storage::disk('public')->delete($homework->submission_path);
        }

        $homework->update([
            'submission_path' => $filePath,
            'submission_name' => $file->getClientOriginalName(),
            'submitted_at' => now(),
            'status' => 'submitted',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Homework submitted successfully',
            'data' => $homework,
        ]);
    }

    /**
     * Download homework file
     */
    public function download($id)
    {
        $user = Auth::user();
        $homework = Homework::findOrFail($id);

        // Check access
        if ($user->user_type === 'tutor' && $homework->tutor_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        if ($user->user_type === 'student' && $homework->student_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $filePath = storage_path('app/public/' . $homework->file_path);

        if (!file_exists($filePath)) {
            return response()->json(['success' => false, 'message' => 'File not found'], 404);
        }

        return response()->download($filePath, $homework->file_name);
    }

    /**
     * Download submission file
     */
    public function downloadSubmission($id)
    {
        $user = Auth::user();
        $homework = Homework::findOrFail($id);

        // Check access
        if ($user->user_type === 'tutor' && $homework->tutor_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        if ($user->user_type === 'student' && $homework->student_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if (!$homework->submission_path) {
            return response()->json(['success' => false, 'message' => 'No submission found'], 404);
        }

        $filePath = storage_path('app/public/' . $homework->submission_path);

        if (!file_exists($filePath)) {
            return response()->json(['success' => false, 'message' => 'File not found'], 404);
        }

        return response()->download($filePath, $homework->submission_name);
    }

    /**
     * Delete homework (tutor only)
     */
    public function destroy($id)
    {
        $tutor = Auth::user();
        $homework = Homework::where('id', $id)
            ->where('tutor_id', $tutor->id)
            ->firstOrFail();

        // Delete files
        if ($homework->file_path) {
            Storage::disk('public')->delete($homework->file_path);
        }
        if ($homework->submission_path) {
            Storage::disk('public')->delete($homework->submission_path);
        }

        $homework->delete();

        return response()->json([
            'success' => true,
            'message' => 'Homework deleted successfully',
        ]);
    }

    /**
     * Get pending homework count for student (unsubmitted)
     */
    public function pendingCount()
    {
        $student = Auth::user();

        $count = Homework::where('student_id', $student->id)
            ->where('status', 'pending')
            ->count();

        return response()->json([
            'success' => true,
            'data' => ['count' => $count],
        ]);
    }

    /**
     * Get submission count for tutor (homework submitted by students, not reviewed)
     */
    public function submissionCount()
    {
        $tutor = Auth::user();

        $count = Homework::where('tutor_id', $tutor->id)
            ->where('status', 'submitted')
            ->count();

        return response()->json([
            'success' => true,
            'data' => ['count' => $count],
        ]);
    }
}
