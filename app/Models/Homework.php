<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    use HasFactory;

    protected $table = 'homework';

    protected $fillable = [
        'tutor_id',
        'student_id',
        'title',
        'description',
        'file_path',
        'file_name',
        'uploaded_at',
        'submission_path',
        'submission_name',
        'submitted_at',
        'status',
        'feedback',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
