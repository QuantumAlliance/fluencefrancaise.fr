<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'tutor_id',
        'admin_id',
        'note_date',
        'note',
    ];

    protected $casts = [
        'note_date' => 'date',
    ];

    /**
     * Get the tutor that this note belongs to
     */
    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    /**
     * Get the admin who created this note
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
