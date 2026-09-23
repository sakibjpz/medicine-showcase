<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TickerItem extends Model
{
    protected $fillable = [
        'title',
        'url',
        'color',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** Available text colors for the ticker. */
    public static function colorOptions(): array
    {
        return [
            'text-navy-800' => 'Dark navy',
            'text-med-700' => 'Teal',
            'text-rose-600' => 'Rose',
            'text-amber-600' => 'Amber',
        ];
    }
}
