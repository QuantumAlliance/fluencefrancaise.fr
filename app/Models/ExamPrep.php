<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamPrep extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_prep_title',
        'exam_prep_subtitle',
        'exam_prep_description',
        'exam_prep_description_title',
        'exam_prep_category',
        'exam_prep_oral_layout',
        'exam_prep_language',
        'exam_prep_level',
        'exam_prep_level_custom',
        'exam_prep_total_texts',
        'exam_prep_json_content',
        'exam_prep_image',
        'exam_prep_banner',
        'exam_prep_is_active',
        'display_order',
        'custom_url',
        'custom_url_target',
    ];

    protected $casts = [
        'exam_prep_is_active' => 'boolean',
        'exam_prep_total_texts' => 'integer',
    ];

    public function sections()
    {
        return $this->hasMany(ExamPrepSection::class);
    }

    public function enrollments()
    {
        return $this->hasMany(ExamPrepEnrollment::class);
    }
}
