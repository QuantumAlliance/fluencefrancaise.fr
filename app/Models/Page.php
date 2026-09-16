<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'status',
        'content',
        'seo_title',
        'meta_description',
        'no_index',
        'no_follow',
        'schema_type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'no_index' => 'boolean',
        'no_follow' => 'boolean',
    ];
}
