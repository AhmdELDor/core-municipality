<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'title',
        'description',
        'amount',
        'payment_type',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];
}
