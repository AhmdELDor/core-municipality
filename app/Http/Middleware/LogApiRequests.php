<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequests
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        // Process the request
        $response = $next($request);

        // Calculate request duration
        $duration = round((microtime(true) - $startTime) * 1000, 2); // milliseconds

        // Get authenticated user safely
        $user = $request->user('sanctum');

        // Log all API requests to file (not database to avoid overhead)
        Log::channel('api')->info('API Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_id' => $user?->id,
            'status' => $response->getStatusCode(),
            'duration_ms' => $duration,
            'user_agent' => $request->userAgent(),
        ]);

        // Log failed requests to database (critical events) - only if authenticated
        if ($response->getStatusCode() >= 400 && $user) {
            try {
                ActivityLog::create([
                    'log_name' => 'api_error',
                    'description' => "API request failed: {$request->method()} {$request->path()}",
                    'event' => 'api_error',
                    'causer_type' => get_class($user),
                    'causer_id' => $user->id,
                    'properties' => [
                        'status_code' => $response->getStatusCode(),
                        'url' => $request->fullUrl(),
                        'method' => $request->method(),
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            } catch (\Exception $e) {
                // Silently fail if logging fails
                Log::error('Failed to log API error to database: ' . $e->getMessage());
            }
        }

        return $response;
    }
}
