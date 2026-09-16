<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamPrepStudentAccess extends Model
{
    use HasFactory;

    protected $table = 'exam_prep_student_access';

    protected $fillable = [
        'student_id',
        'exam_prep_id',
        'granted_by_tutor_id',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function examPrep()
    {
        return $this->belongsTo(ExamPrep::class);
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'granted_by_tutor_id');
    }
}
