<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    protected $fillable = [
        'platform',
        'label',
        'url',
        'icon',
        'sort_order',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'label' => 'array',
            'active' => 'boolean',
        ];
    }
}
