<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class PollVote extends Model
{
    use HasUlids;

    protected $fillable = [
        'poll_id',
        'user_id',
        'option',
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
