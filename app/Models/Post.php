<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;
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
        'season_id',
        'episode_number',
        'related_project_id',
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

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function relatedProject(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'related_project_id');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_post');
    }
}
