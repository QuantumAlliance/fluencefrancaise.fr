<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'class_type_id',
        'status',
        'enrollment_date',
        'completion_date',
        'form_data',
        'entry_id',
    ];

    protected $casts = [
        'form_data' => 'array',
        'enrollment_date' => 'datetime',
        'completion_date' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($enrollment) {
            // Generate sequential entry_id if not provided
            if (empty($enrollment->entry_id)) {
                // Get max entry_id from column
                $maxEntryIdFromColumn = static::max('entry_id') ?? 0;
                
                // Also check form_data for imported enrollments
                $maxEntryIdFromFormData = \Illuminate\Support\Facades\DB::table('enrollments')
                    ->whereNotNull('form_data')
                    ->get()
                    ->map(function ($e) {
                        $formData = $e->form_data;
                        // Handle both string and already-decoded array formats
                        if (is_string($formData)) {
                            $formData = json_decode($formData, true);
                        }
                        return (is_array($formData) && isset($formData['entry_id'])) ? (int)$formData['entry_id'] : 0;
                    })
                    ->max() ?? 0;
                
                // Use the maximum of both and add 1
                $enrollment->entry_id = max((int)$maxEntryIdFromColumn, (int)$maxEntryIdFromFormData) + 1;
            }
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function classType()
    {
        return $this->belongsTo(ClassType::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
