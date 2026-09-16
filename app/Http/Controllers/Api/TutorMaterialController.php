<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TutorMaterialController extends Controller
{
    /**
     * Base path for materials
     */
    protected $basePath;

    public function __construct()
    {
        // Store outside public folder - no direct URL access possible
        $this->basePath = storage_path('app/Material');
    }

    /**
     * List folders and files in a directory
     * GET /api/tutor/materials
     * GET /api/tutor/materials?path=A1
     */
    public function index(Request $request)
    {
        // Ensure user is a tutor
        $user = Auth::user();
        if (!$user || $user->user_type !== 'tutor') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Tutor access only.',
            ], 403);
        }

        $relativePath = $request->query('path', '');

        // Sanitize path to prevent directory traversal
        $relativePath = $this->sanitizePath($relativePath);

        $fullPath = $this->basePath;
        if ($relativePath) {
            $fullPath = $this->basePath . DIRECTORY_SEPARATOR . $relativePath;
        }

        // Check if path exists and is within base path
        if (!File::exists($fullPath) || !File::isDirectory($fullPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Directory not found',
            ], 404);
        }

        // Verify path is within base directory (security check)
        $realBasePath = realpath($this->basePath);
        $realFullPath = realpath($fullPath);

        if ($realFullPath === false || strpos($realFullPath, $realBasePath) !== 0) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid path',
            ], 403);
        }

        $items = [];
        $contents = File::files($fullPath);
        $directories = File::directories($fullPath);

        // Add directories first
        foreach ($directories as $dir) {
            $dirName = basename($dir);
            $items[] = [
                'name' => $dirName,
                'type' => 'folder',
                'path' => $relativePath ? $relativePath . '/' . $dirName : $dirName,
                'modified' => date('d-m-Y H:i', File::lastModified($dir)),
            ];
        }

        // Add files
        foreach ($contents as $file) {
            $fileName = $file->getFilename();
            $items[] = [
                'name' => $fileName,
                'type' => 'file',
                'path' => $relativePath ? $relativePath . '/' . $fileName : $fileName,
                'size' => $this->formatFileSize($file->getSize()),
                'extension' => $file->getExtension(),
                'modified' => date('d-m-Y H:i', $file->getMTime()),
            ];
        }

        // Sort: folders first, then files alphabetically
        usort($items, function ($a, $b) {
            if ($a['type'] !== $b['type']) {
                return $a['type'] === 'folder' ? -1 : 1;
            }
            return strcasecmp($a['name'], $b['name']);
        });

        return response()->json([
            'success' => true,
            'data' => [
                'current_path' => $relativePath,
                'items' => $items,
            ],
        ]);
    }

    /**
     * Download a file
     * GET /api/tutor/materials/download?path=A1/file.pdf
     */
    public function download(Request $request)
    {
        // Ensure user is a tutor
        $user = Auth::user();
        if (!$user || $user->user_type !== 'tutor') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Tutor access only.',
            ], 403);
        }

        $relativePath = $request->query('path', '');

        if (empty($relativePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File path is required',
            ], 400);
        }

        // Sanitize path
        $relativePath = $this->sanitizePath($relativePath);
        $fullPath = $this->basePath . DIRECTORY_SEPARATOR . $relativePath;

        // Check if file exists
        if (!File::exists($fullPath) || !File::isFile($fullPath)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found',
            ], 404);
        }

        // Verify path is within base directory (security check)
        $realBasePath = realpath($this->basePath);
        $realFullPath = realpath($fullPath);

        if ($realFullPath === false || strpos($realFullPath, $realBasePath) !== 0) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid path',
            ], 403);
        }

        $fileName = basename($fullPath);

        return response()->download($fullPath, $fileName);
    }

    /**
     * Sanitize path to prevent directory traversal attacks
     */
    protected function sanitizePath($path)
    {
        // Remove any null bytes
        $path = str_replace("\0", '', $path);

        // Normalize slashes
        $path = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);

        // Remove any .. segments
        $parts = explode(DIRECTORY_SEPARATOR, $path);
        $safeParts = [];

        foreach ($parts as $part) {
            if ($part === '..' || $part === '.') {
                continue;
            }
            if (!empty($part)) {
                $safeParts[] = $part;
            }
        }

        return implode(DIRECTORY_SEPARATOR, $safeParts);
    }

    /**
     * Format file size to human readable
     */
    protected function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
