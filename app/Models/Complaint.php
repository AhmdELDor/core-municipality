<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Complaint extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'title',
        'desc',
        'user_id',
        'images_url',
        'status',
        'result',
    ];

    protected $casts = [
        'images_url' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
