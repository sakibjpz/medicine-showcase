<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $fillable = [
        'name',
        'institution',
        'email',
        'inquiry_type',
        'message',
        'responded',
    ];

    protected function casts(): array
    {
        return [
            'responded' => 'boolean',
        ];
    }
}
