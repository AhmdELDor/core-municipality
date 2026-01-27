<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestForm extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'title',
        'description',
        'fields',
        'version',
        'status',
        'instructions',
        'attachments_required',
        'fee_amount',
        'allowed_file_types',
    ];

    protected $casts = [
        'fields' => 'array',
        'attachments_required' => 'array',
        'allowed_file_types' => 'array',
        'fee_amount' => 'decimal:2',
    ];
}
