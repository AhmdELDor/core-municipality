<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Explore extends Model
{
    use HasUlids;

    protected $fillable = [
        'title',
        'desc',
        'category',
        'type',
        'citizen_id',
        'images_url',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'images_url' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function citizen()
    {
        return $this->belongsTo(User::class, 'citizen_id');
    }
}
