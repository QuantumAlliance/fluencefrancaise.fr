<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamPrepProgress extends Model
{
    use HasFactory;

    protected $table = 'exam_prep_progress';

    protected $fillable = [
        'user_id',
        'exam_prep_id',
        'state',
    ];

    protected $casts = [
        'state' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function examPrep()
    {
        return $this->belongsTo(ExamPrep::class);
    }
}
