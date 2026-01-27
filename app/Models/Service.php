<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasUlids;

    protected $fillable = [
        'name',
        'logo',
        'phone_number',
        'priority',
    ];

    protected $casts = [
        'priority' => 'boolean',
    ];
}
