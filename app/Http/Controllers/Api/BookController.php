<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BookController extends Controller
{
    /**
     * Get all books - GET /api/books
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $query = Book::query();

        // Filter by active status if requested
        if ($request->has('active_only')) {
            $query->where('is_active', true);
        }

        // Search by title
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }

        $books = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $books,
            'message' => 'Books retrieved successfully',
        ]);
    }

    /**
     * Get a single book - GET /api/books/{id}
     */
    public function show($id)
    {
        $book = Book::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $book,
            'message' => 'Book retrieved successfully',
        ]);
    }

    /**
     * Create a new book - POST /api/books
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'nullable|url|max:500',
            'link_file' => 'nullable|file|mimes:pdf,doc,docx,txt,epub,mobi|max:61440', // 60MB max
            'is_active' => 'nullable',
            'cover_image' => 'nullable|image|max:5120', // 5MB max
        ]);

        // Always set is_active to true (UI removed, all books are active by default)
        $validated['is_active'] = true;

        // Handle link file upload
        if ($request->hasFile('link_file')) {
            $file = $request->file('link_file');
            $fileSize = $file->getSize();

            // Manual validation: check file size (60MB = 62914560 bytes)
            if ($fileSize > 62914560) {
                return response()->json([
                    'success' => false,
                    'message' => 'Link file must not exceed 60MB'
                ], 422);
            }

            try {
                // Create directory if it doesn't exist
                $uploadPath = storage_path('app/public/books/files');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }

                // Use move() instead of store() to avoid MIME type detection
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->move($uploadPath, $fileName);
                $storedPath = 'books/files/' . $fileName;
                
                // Store the file path as the link
                $validated['link'] = $storedPath;
            } catch (\Exception $e) {
                \Log::error('Error storing book link file: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store link file'
                ], 500);
            }
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $fileSize = $file->getSize();

            // Manual validation: check file size (5MB = 5242880 bytes)
            if ($fileSize > 5242880) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cover image must not exceed 5MB'
                ], 422);
            }

            try {
                // Create directory if it doesn't exist
                $uploadPath = storage_path('app/public/books/images');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }

                // Use move() instead of store() to avoid MIME type detection
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->move($uploadPath, $fileName);
                $storedPath = 'books/images/' . $fileName;
                $validated['cover_image'] = $storedPath;
            } catch (\Exception $e) {
                \Log::error('Error storing book cover image: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store cover image'
                ], 500);
            }
        }

        $book = Book::create($validated);

        return response()->json([
            'success' => true,
            'data' => $book,
            'message' => 'Book created successfully',
        ], 201);
    }

    /**
     * Update a book - PUT /api/books/{id}
     */
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:500',
            'link_file' => 'nullable|file|mimes:pdf,doc,docx,txt,epub,mobi|max:61440', // 60MB max
            'cover_image' => 'nullable|image|max:5120', // 5MB max
        ]);

        // Always set is_active to true (UI removed, all books are active by default)
        $validated['is_active'] = true;

        // Handle link file upload
        if ($request->hasFile('link_file')) {
            $file = $request->file('link_file');
            $fileSize = $file->getSize();

            // Manual validation: check file size (60MB = 62914560 bytes)
            if ($fileSize > 62914560) {
                return response()->json([
                    'success' => false,
                    'message' => 'Link file must not exceed 60MB'
                ], 422);
            }

            try {
                // Delete old link file if exists (if it was a file, not URL)
                if ($book->link && !filter_var($book->link, FILTER_VALIDATE_URL)) {
                    $oldFilePath = storage_path('app/public/' . $book->link);
                    if (File::exists($oldFilePath)) {
                        File::delete($oldFilePath);
                    }
                }

                // Create directory if it doesn't exist
                $uploadPath = storage_path('app/public/books/files');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }

                // Use move() instead of store() to avoid MIME type detection
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->move($uploadPath, $fileName);
                $storedPath = 'books/files/' . $fileName;
                
                // Store the file path as the link
                $validated['link'] = $storedPath;
            } catch (\Exception $e) {
                \Log::error('Error storing book link file: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store link file'
                ], 500);
            }
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $fileSize = $file->getSize();

            // Manual validation: check file size (5MB = 5242880 bytes)
            if ($fileSize > 5242880) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cover image must not exceed 5MB'
                ], 422);
            }

            try {
                // Delete old cover image if exists
                if ($book->cover_image) {
                    $oldImagePath = storage_path('app/public/' . $book->cover_image);
                    if (File::exists($oldImagePath)) {
                        File::delete($oldImagePath);
                    }
                }

                // Create directory if it doesn't exist
                $uploadPath = storage_path('app/public/books/images');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }

                // Use move() instead of store() to avoid MIME type detection
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->move($uploadPath, $fileName);
                $storedPath = 'books/images/' . $fileName;
                $validated['cover_image'] = $storedPath;
            } catch (\Exception $e) {
                \Log::error('Error storing book cover image: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store cover image'
                ], 500);
            }
        }

        // Update the book
        $book->update($validated);

        return response()->json([
            'success' => true,
            'data' => $book,
            'message' => 'Book updated successfully',
        ]);
    }

    /**
     * Delete a book - DELETE /api/books/{id}
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        // Delete cover image if exists
        if ($book->cover_image) {
            $imagePath = storage_path('app/public/' . $book->cover_image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        // Delete link file if exists (if it's a file, not URL)
        if ($book->link && !filter_var($book->link, FILTER_VALIDATE_URL)) {
            $filePath = storage_path('app/public/' . $book->link);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Book deleted successfully',
        ]);
    }
}
