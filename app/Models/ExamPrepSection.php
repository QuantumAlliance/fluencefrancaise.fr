<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamPrepSection extends Model
{
    use HasFactory;
    protected $fillable = ['exam_prep_id', 'name', 'description', 'order'];
    public function examPrep() { return $this->belongsTo(ExamPrep::class); }
    public function lessons() { return $this->hasMany(ExamPrepLesson::class); }
}
