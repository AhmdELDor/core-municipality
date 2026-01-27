<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;

trait LogsActivity
{
    /**
     * Log user activity to database (critical events only)
     *
     * @param string $description Description of the action
     * @param string $logName Category of the log (e.g., 'authentication', 'payment')
     * @param string|null $event Event type (e.g., 'created', 'updated', 'login')
     * @param mixed $subject The model instance being logged
     * @param array $properties Additional data to store
     * @return void
     */
    public function logActivity(
        string $description,
        string $logName = 'default',
        ?string $event = null,
        $subject = null,
        array $properties = []
    ): void {
        try {
            // Get user from Sanctum guard explicitly
            $user = request()->user('sanctum') ?? auth()->user();

            ActivityLog::create([
                'log_name' => $logName,
                'description' => $description,
                'subject_type' => $subject ? get_class($subject) : null,
                'subject_id' => $subject?->id ?? null,
                'event' => $event,
                'causer_type' => $user ? get_class($user) : null,
                'causer_id' => $user?->id,
                'properties' => $properties,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Fallback to file logging if database fails
            Log::channel('security')->error('Failed to log activity: ' . $e->getMessage(), [
                'description' => $description,
                'log_name' => $logName,
            ]);
        }
    }

    /**
     * Log to file (general logging - non-critical)
     *
     * @param string $message Log message
     * @param string $level Log level (info, warning, error, etc.)
     * @param string $channel Log channel (api, security, etc.)
     * @param array $context Additional context data
     * @return void
     */
    public function logToFile(
        string $message,
        string $level = 'info',
        string $channel = 'api',
        array $context = []
    ): void {
        Log::channel($channel)->{$level}($message, $context);
    }
}
