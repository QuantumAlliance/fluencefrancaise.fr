<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_title',
        'course_subtitle',
        'course_description',
        'course_category',
        'course_language',
        'course_level',
        'course_level_custom',
        'course_total_texts',
        'course_json_content',
        'course_image',
        'course_banner',
        'course_is_active',
        'display_order',
        'custom_url',
        'custom_url_target',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'course_is_active' => 'boolean',
        'course_total_texts' => 'integer',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sections()
    {
        return $this->hasMany(CourseSection::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function studentProgress()
    {
        return $this->hasMany(StudentProgress::class);
    }
}
