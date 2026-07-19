<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'slug',
        'status',
        'featured',
        'sort_order',
        'name',
        'issuer',
        'issued_at',
        'credential_id',
        'url',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'name' => 'array',
            'issued_at' => 'date',
        ];
    }
}
