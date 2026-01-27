<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasUlids;

    protected $fillable = [
        'title',
        'description',
        'options',
        'start_at',
        'end_at',
        'status',
        'votes_count',
    ];

    protected $casts = [
        'options' => 'array',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function votes()
    {
        return $this->hasMany(PollVote::class);
    }

    public function currentUserVote()
    {
        return $this->hasOne(PollVote::class)->where('user_id', auth()->id());
    }
}
