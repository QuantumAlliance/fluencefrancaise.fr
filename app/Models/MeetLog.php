<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'conference_id',
        'meeting_code',
        'organizer_email',
        'actor_email',
        'duration_seconds',
        'event_time',
    ];

    protected $casts = [
        'event_time' => 'datetime',
        'duration_seconds' => 'integer',
    ];

    protected $appends = ['formatted_duration'];

    /**
     * Format duration from seconds to human-readable format
     */
    public function getFormattedDurationAttribute()
    {
        $seconds = $this->duration_seconds;
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%dh %dm %ds', $hours, $minutes, $remainingSeconds);
        } elseif ($minutes > 0) {
            return sprintf('%dm %ds', $minutes, $remainingSeconds);
        } else {
            return sprintf('%ds', $remainingSeconds);
        }
    }
}
