<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    use HasUlids;

    protected $fillable = [
        'citizen_id',
        'desc',
    ];

    public function citizen()
    {
        return $this->belongsTo(User::class, 'citizen_id');
    }
}
