<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamPrepLesson extends Model
{
    use HasFactory;
    protected $fillable = ['exam_prep_section_id', 'title', 'content', 'order'];
    public function section() { return $this->belongsTo(ExamPrepSection::class, 'exam_prep_section_id'); }
    public function activities() { return $this->hasMany(ExamPrepActivity::class); }
}
