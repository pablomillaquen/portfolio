<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'problem',
        'approach',
        'contribution',
        'what_it_demonstrates',
        'project_status',
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
            'problem' => 'array',
            'approach' => 'array',
            'contribution' => 'array',
            'what_it_demonstrates' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function media(): HasMany
    {
        return $this->hasMany(ProjectMedia::class)->orderBy('sort_order');
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'project_post');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_project');
    }

    public function capabilities(): BelongsToMany
    {
        return $this->belongsToMany(Capability::class, 'capability_project');
    }
}
