<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Receives frontend error and debug reports from resources/js/utils/errorReporter.js.
 *
 * Writes to storage/logs/client-errors-YYYY-MM-DD.log, kept separate from the
 * application log so browser noise never buries server-side errors.
 *
 * This endpoint is public and writes to disk, so it is throttled in routes/api.php
 * and every field is length-capped.
 */
class ClientErrorLogController extends Controller
{
    private const RETAIN_DAYS = 14;

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kind' => ['nullable', 'string', 'max:50'],
            'message' => ['required', 'string', 'max:2000'],
            'source' => ['nullable', 'string', 'max:500'],
            'lineno' => ['nullable', 'integer'],
            'colno' => ['nullable', 'integer'],
            'stack' => ['nullable', 'string', 'max:4000'],
            'url' => ['nullable', 'string', 'max:2000'],
            'user_agent' => ['nullable', 'string', 'max:500'],
            'request_url' => ['nullable', 'string', 'max:2000'],
            'request_method' => ['nullable', 'string', 'max:10'],
            'response_status' => ['nullable', 'integer'],
            'response_body' => ['nullable', 'string', 'max:2000'],
        ]);

        $kind = $validated['kind'] ?? 'unknown';

        $this->logger()->error('[client:' . $kind . '] ' . $validated['message'], [
            'source' => $validated['source'] ?? null,
            'lineno' => $validated['lineno'] ?? null,
            'colno' => $validated['colno'] ?? null,
            'stack' => $validated['stack'] ?? null,
            'url' => $validated['url'] ?? null,
            'user_agent' => $validated['user_agent'] ?? null,
            'request_url' => $validated['request_url'] ?? null,
            'request_method' => $validated['request_method'] ?? null,
            'response_status' => $validated['response_status'] ?? null,
            'response_body' => $validated['response_body'] ?? null,
            'ip' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Report received',
        ]);
    }

    private function logger()
    {
        return Log::build([
            'driver' => 'daily',
            'path' => storage_path('logs/client-errors.log'),
            'days' => self::RETAIN_DAYS,
            'level' => 'debug',
        ]);
    }
}
