<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamPrepEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exam_prep_id',
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($enrollment) {
            if (empty($enrollment->entry_id)) {
                $maxEntryIdFromColumn = static::max('entry_id') ?? 0;

                $maxEntryIdFromFormData = \Illuminate\Support\Facades\DB::table('exam_prep_enrollments')
                    ->whereNotNull('form_data')
                    ->get()
                    ->map(function ($e) {
                        $formData = $e->form_data;
                        if (is_string($formData)) {
                            $formData = json_decode($formData, true);
                        }
                        return (is_array($formData) && isset($formData['entry_id'])) ? (int)$formData['entry_id'] : 0;
                    })
                    ->max() ?? 0;

                $enrollment->entry_id = max((int)$maxEntryIdFromColumn, (int)$maxEntryIdFromFormData) + 1;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function examPrep()
    {
        return $this->belongsTo(ExamPrep::class);
    }

    public function classType()
    {
        return $this->belongsTo(ClassType::class);
    }
}
