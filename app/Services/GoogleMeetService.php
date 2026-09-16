<?php

namespace App\Services;

use App\Models\MeetLog;
use App\Models\EmailSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GoogleMeetService
{
    private $accessToken;
    private $refreshToken;
    private $clientId;
    private $clientSecret;

    public function __construct()
    {
        $this->loadSettings();
    }

    /**
     * Load credentials and tokens from email_settings table
     */
    private function loadSettings()
    {
        $settings = EmailSettings::first();
        if ($settings) {
            $this->clientId = $settings->google_client_id ?? env('GOOGLE_CLIENT_ID');
            $this->clientSecret = $settings->google_client_secret ?? env('GOOGLE_CLIENT_SECRET');
            $this->accessToken = $settings->google_access_token;
            $this->refreshToken = $settings->google_refresh_token;
        } else {
            $this->clientId = env('GOOGLE_CLIENT_ID');
            $this->clientSecret = env('GOOGLE_CLIENT_SECRET');
        }
    }

    /**
     * Refresh the access token using the refresh token
     */
    private function refreshAccessToken()
    {
        $this->loadSettings(); // Reload settings to ensure we have the latest tokens

        if (!$this->refreshToken) {
            Log::error('Google OAuth Refresh: No refresh token available in EmailSettings');
            return false;
        }

        try {
            Log::info('Attempting to refresh Google access token...');
            
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $this->refreshToken,
                'grant_type' => 'refresh_token',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $this->accessToken = $data['access_token'];

                // Update the access token in database
                $settings = EmailSettings::first();
                if ($settings) {
                    $settings->google_access_token = $this->accessToken;
                    $settings->save();
                    Log::info('Google access token refreshed and saved successfully');
                }

                return true;
            }

            Log::error('Failed to refresh Google access token', [
                'status' => $response->status(),
                'response' => $response->json(),
                'client_id' => substr($this->clientId, 0, 10) . '...',
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Error refreshing access token: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Fetch Google Meet audit logs from Reports API
     * 
     * @param string|null $startTime Start time in ISO 8601 format
     * @param string|null $endTime End time in ISO 8601 format
     * @param int $maxResults Maximum number of results to fetch
     * @return array
     */
    public function fetchMeetLogs($startTime = null, $endTime = null, $maxResults = 1000)
    {
        if (!$this->accessToken) {
            throw new \Exception('No access token available. Please authenticate with Google first.');
        }

        // Default to last 2 months (60 days) if no start time provided
        if (!$startTime) {
            $startTime = Carbon::now()->subDays(60)->toIso8601String();
        }

        $params = [
            'applicationName' => 'meet',
            'eventName' => 'call_ended',
            'maxResults' => $maxResults,
        ];

        if ($startTime) {
            $params['startTime'] = $startTime;
        }

        if ($endTime) {
            $params['endTime'] = $endTime;
        }

        try {
            // Disable SSL verification for local development
            $httpClient = Http::withToken($this->accessToken);
            
            // For local development, disable SSL verification to avoid certificate issues
            if (config('app.env') === 'local') {
                $httpClient = $httpClient->withOptions([
                    'verify' => false
                ]);
            }
            
            $response = $httpClient->get('https://admin.googleapis.com/admin/reports/v1/activity/users/all/applications/meet', $params);

            // If token expired, refresh and retry
            if ($response->status() === 401) {
                Log::info('Access token expired or invalid (401), attempting refresh...');
                if ($this->refreshAccessToken()) {
                    // Retry with new token
                    $httpClient = Http::withToken($this->accessToken);
                    
                    if (config('app.env') === 'local') {
                        $httpClient = $httpClient->withOptions([
                            'verify' => false
                        ]);
                    }
                    
                    $response = $httpClient->get('https://admin.googleapis.com/admin/reports/v1/activity/users/all/applications/meet', $params);
                    
                    if ($response->successful()) {
                        Log::info('Retry successful after token refresh');
                    } else {
                        Log::error('Retry failed after token refresh', [
                            'status' => $response->status(),
                            'body' => $response->body()
                        ]);
                    }
                } else {
                    Log::error('Refresh token attempt failed, cannot retry request');
                }
            }

            if ($response->successful()) {
                $data = $response->json();
                return $data['items'] ?? [];
            }

            Log::error('Failed to fetch Google Meet logs', [
                'status' => $response->status(),
                'response' => $response->json()
            ]);

            throw new \Exception('Failed to fetch Google Meet logs: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Error fetching Google Meet logs: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync Google Meet logs to database (Smart Sync with upsert logic)
     * 
     * @param string|null $startTime
     * @param string|null $endTime
     * @return array Statistics about the sync
     */
    public function syncMeetLogs($startTime = null, $endTime = null)
    {
        $logs = $this->fetchMeetLogs($startTime, $endTime);
        
        $inserted = 0;
        $updated = 0;
        $errors = 0;

        foreach ($logs as $log) {
            try {
                $eventId = $log['id']['uniqueQualifier'] ?? null;
                
                if (!$eventId) {
                    $errors++;
                    continue;
                }

                // Extract event parameters
                $parameters = collect($log['events'][0]['parameters'] ?? []);
                
                $conferenceId = $parameters->firstWhere('name', 'conference_id')['value'] ?? null;
                $meetingCode = $parameters->firstWhere('name', 'meeting_code')['value'] ?? null;
                $organizerEmail = $parameters->firstWhere('name', 'organizer_email')['value'] ?? null;
                $durationSeconds = (int) ($parameters->firstWhere('name', 'duration_seconds')['intValue'] ?? 0);
                
                // Actor email from the log
                $actorEmail = $log['actor']['email'] ?? null;
                
                // Event time
                $eventTime = isset($log['id']['time']) 
                    ? Carbon::parse($log['id']['time']) 
                    : null;

                // Use updateOrCreate to avoid duplicates
                $result = MeetLog::updateOrCreate(
                    ['event_id' => $eventId],
                    [
                        'conference_id' => $conferenceId,
                        'meeting_code' => $meetingCode,
                        'organizer_email' => $organizerEmail,
                        'actor_email' => $actorEmail,
                        'duration_seconds' => $durationSeconds,
                        'event_time' => $eventTime,
                    ]
                );

                if ($result->wasRecentlyCreated) {
                    $inserted++;
                } else {
                    $updated++;
                }

            } catch (\Exception $e) {
                Log::error('Error syncing meet log', [
                    'event_id' => $eventId ?? 'unknown',
                    'error' => $e->getMessage()
                ]);
                $errors++;
            }
        }

        return [
            'total_fetched' => count($logs),
            'inserted' => $inserted,
            'updated' => $updated,
            'errors' => $errors,
            'success' => true,
        ];
    }

    /**
     * Get filtered meet logs from database
     * 
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFilteredLogs(array $filters = [])
    {
        $query = MeetLog::query();

        // Filter by date range
        if (!empty($filters['start_date'])) {
            $query->where('event_time', '>=', Carbon::parse($filters['start_date']));
        } else {
            // Default to last 2 months if not specified
            $query->where('event_time', '>=', Carbon::now()->subMonths(2));
        }

        if (!empty($filters['end_date'])) {
            $query->where('event_time', '<=', Carbon::parse($filters['end_date'])->endOfDay());
        }

        // Filter by organizer email
        if (!empty($filters['organizer_email'])) {
            $query->where('organizer_email', 'like', '%' . $filters['organizer_email'] . '%');
        }

        // Filter by actor email (staff member)
        if (!empty($filters['actor_email'])) {
            $query->where('actor_email', 'like', '%' . $filters['actor_email'] . '%');
        }

        // Search across multiple fields
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('organizer_email', 'like', '%' . $search . '%')
                  ->orWhere('actor_email', 'like', '%' . $search . '%')
                  ->orWhere('meeting_code', 'like', '%' . $search . '%')
                  ->orWhere('conference_id', 'like', '%' . $search . '%');
            });
        }

        return $query->orderBy('event_time', 'desc')->get();
    }

    /**
     * Get unique staff emails from database
     * 
     * @return array
     */
    public function getUniqueStaffEmails()
    {
        return MeetLog::select('actor_email')
            ->whereNotNull('actor_email')
            ->distinct()
            ->orderBy('actor_email')
            ->pluck('actor_email')
            ->toArray();
    }

    /**
     * Calculate total duration for a specific staff member
     * 
     * @param string $email
     * @param string|null $startDate
     * @param string|null $endDate
     * @return int Total duration in seconds
     */
    public function calculateTotalDuration($email, $startDate = null, $endDate = null)
    {
        $query = MeetLog::where('actor_email', $email);

        if ($startDate) {
            $query->where('event_time', '>=', Carbon::parse($startDate));
        }

        if ($endDate) {
            $query->where('event_time', '<=', Carbon::parse($endDate)->endOfDay());
        }

        return $query->sum('duration_seconds');
    }
}
