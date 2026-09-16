<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimerEditRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'tutor_id',
        'record_id',
        'record_date',
        'old_timer',
        'new_timer',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'record_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }
}
