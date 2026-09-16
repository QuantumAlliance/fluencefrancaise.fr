<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseActivity extends Model
{
    use HasFactory;
    protected $fillable = ['course_lesson_id', 'type', 'content_json', 'order'];
    public function lesson() { return $this->belongsTo(CourseLesson::class, 'course_lesson_id'); }
}
