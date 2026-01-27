<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttachBill extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'citizen_id',
        'title',
        'desc',
        'amount',
        'due_date',
        'paid_date',
        'note',
    ];
    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    public function citizen()
    {
        return $this->belongsTo(User::class, 'citizen_id');
    }
}
