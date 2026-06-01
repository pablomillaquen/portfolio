<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'type',
        'slug',
        'status',
        'featured',
        'cover_image_url',
        'external_url',
        'share_enabled',
        'title',
        'excerpt',
        'content',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'share_enabled' => 'boolean',
            'title' => 'array',
            'excerpt' => 'array',
            'content' => 'array',
            'published_at' => 'datetime',
        ];
    }
}
