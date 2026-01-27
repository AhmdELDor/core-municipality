<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'title',
        'description',
        'status',
        'category',
        'location',
        'image_urls',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'image_urls' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
