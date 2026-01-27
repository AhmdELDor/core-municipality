<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public $timestamps = false; // Only created_at

    protected $fillable = [
        'log_name',
        'description',
        'subject_type',
        'subject_id',
        'event',
        'causer_type',
        'causer_id',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the subject (the model that was logged)
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Get the causer (the user who performed the action)
     */
    public function causer()
    {
        return $this->morphTo();
    }

    /**
     * Boot the model
     */
    protected static function booted()
    {
        static::creating(function ($log) {
            $log->created_at = now();

            // Auto-cleanup old logs (run occasionally, not every time)
            // 1% chance to cleanup logs older than 90 days
            if (rand(1, 100) === 1) {
                static::where('created_at', '<', now()->subDays(90))->delete();
            }
        });
    }
}
