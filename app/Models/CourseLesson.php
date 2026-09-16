<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLesson extends Model
{
    use HasFactory;
    protected $fillable = ['course_section_id', 'title', 'content', 'order'];
    public function section() { return $this->belongsTo(CourseSection::class, 'course_section_id'); }
    public function activities() { return $this->hasMany(CourseActivity::class); }
}
