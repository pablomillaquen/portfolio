<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'slug',
        'status',
        'featured',
        'sort_order',
        'cover_image_url',
        'demo_url',
        'repository_url',
        'title',
        'summary',
        'description',
        'details',
        'stack',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'title' => 'array',
            'summary' => 'array',
            'description' => 'array',
            'details' => 'array',
            'stack' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function media(): HasMany
    {
        return $this->hasMany(ProjectMedia::class)->orderBy('sort_order');
    }
}
