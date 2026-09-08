<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'slug',
        'heading',
        'lead',
        'banner_image',
        'content',
        'breadcrumbs',
        'is_published',
        'meta_title',
    ];

    protected function casts(): array
    {
        return [
            'breadcrumbs' => 'array',
            'is_published' => 'boolean',
        ];
    }
}
