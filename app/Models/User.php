<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', // Required field from original migration
        'first_name',
        'last_name',
        'username',
        'email',
        'phone',
        'password',
        'wordpress_password', // Store WordPress password hash separately
        'profile_picture',
        'title',
        'biography',
        'user_type',
        'permissions',
        'timezone',
        'gender',
        'date_of_birth',
        'location',
        'payment_confirmed',
        'working_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'permissions' => 'array',
        'payment_confirmed' => 'boolean',
    ];

    // Relationships
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function studentProgress()
    {
        return $this->hasMany(StudentProgress::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function tutorAssignments()
    {
        return $this->hasMany(TutorStudentAssignment::class, 'tutor_id');
    }

    public function studentAssignments()
    {
        return $this->hasMany(TutorStudentAssignment::class, 'student_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function preferences()
    {
        return $this->hasMany(UserPreference::class);
    }
}
