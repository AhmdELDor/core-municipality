<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'user_id',
        'request_form_id',
        'data',
        'attachments',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'data' => 'array',
        'attachments' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requestForm()
    {
        return $this->belongsTo(RequestForm::class);
    }
}
