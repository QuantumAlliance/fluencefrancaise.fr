<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tutor_id',
    ];

    /**
     * Get the tutor who owns the group
     */
    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    /**
     * Get the students in this group
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'group_student', 'group_id', 'student_id')
                    ->withTimestamps();
    }
}
