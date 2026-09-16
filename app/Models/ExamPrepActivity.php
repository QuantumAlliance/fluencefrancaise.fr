<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamPrepActivity extends Model
{
    use HasFactory;
    protected $fillable = ['exam_prep_lesson_id', 'type', 'content_json', 'order'];
    public function lesson() { return $this->belongsTo(ExamPrepLesson::class, 'exam_prep_lesson_id'); }
}
