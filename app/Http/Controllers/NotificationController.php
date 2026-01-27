<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\DeviceToken;
use App\Models\Notification;
use App\Services\FirebaseService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponse;

    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Get all notifications for authenticated user
     * Admins can see all notifications in the system
     */
    public function index(Request $request)
    {
        // Admin sees all notifications, users see only their own
        if (in_array($request->user()->role, ['admin', 'superadmin'])) {
            $query = Notification::with('user');
        } else {
            $query = Notification::where('user_id', $request->user()->id);
        }

        $notifications = $query->latest()->paginate(10);

        return $this->paginatedResponse(
            $notifications,
            NotificationResource::collection($notifications),
            'Notifications retrieved successfully'
        );
    }

    /**
     * Get unread notifications count
     */
    public function unreadCount(Request $request)
    {
        $count = Notification::where('user_id', $request->user()->id)
            ->unread()
            ->count();

        return $this->successResponse(['count' => $count], 'Unread count retrieved successfully');
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, Notification $notification)
    {
        // Ensure user owns the notification
        if ($notification->user_id !== $request->user()->id) {
            return $this->errorResponse('Unauthorized', 403);
        }

        $notification->markAsRead();

        return $this->successResponse(
            new NotificationResource($notification),
            'Notification marked as read'
        );
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        $count = Notification::where('user_id', $request->user()->id)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return $this->successResponse(
            ['count' => $count],
            'All notifications marked as read'
        );
    }

    /**
     * Delete a notification
     */
    public function destroy(Request $request, Notification $notification)
    {
        // Ensure user owns the notification
        if ($notification->user_id !== $request->user()->id) {
            return $this->errorResponse('Unauthorized', 403);
        }

        $notification->delete();

        return $this->successResponse(null, 'Notification deleted successfully');
    }

    /**
     * Register device token for push notifications
     */
    public function registerDevice(Request $request)
    {
        $validated = $request->validate([
            'device_token' => 'required|string',
            'device_type' => 'nullable|string|in:android,ios,web',
            'device_name' => 'nullable|string|max:255',
        ]);

        // Check if token already exists for this user
        $deviceToken = DeviceToken::where('device_token', $validated['device_token'])->first();

        if ($deviceToken) {
            // Update existing token
            $deviceToken->update([
                'user_id' => $request->user()->id,
                'device_type' => $validated['device_type'] ?? $deviceToken->device_type,
                'device_name' => $validated['device_name'] ?? $deviceToken->device_name,
                'is_active' => true,
                'last_used_at' => now(),
            ]);
        } else {
            // Create new token
            $deviceToken = DeviceToken::create([
                'user_id' => $request->user()->id,
                'device_token' => $validated['device_token'],
                'device_type' => $validated['device_type'] ?? null,
                'device_name' => $validated['device_name'] ?? null,
                'last_used_at' => now(),
            ]);
        }

        return $this->successResponse(
            [
                'id' => $deviceToken->id,
                'device_type' => $deviceToken->device_type,
                'device_name' => $deviceToken->device_name,
            ],
            'Device registered successfully'
        );
    }

    /**
     * Unregister device token
     */
    public function unregisterDevice(Request $request)
    {
        $validated = $request->validate([
            'device_token' => 'required|string',
        ]);

        $deviceToken = DeviceToken::where('device_token', $validated['device_token'])
            ->where('user_id', $request->user()->id)
            ->first();

        if ($deviceToken) {
            $deviceToken->update(['is_active' => false]);
            return $this->successResponse(null, 'Device unregistered successfully');
        }

        return $this->errorResponse('Device token not found', 404);
    }

    /**
     * Get user's registered devices
     */
    public function getDevices(Request $request)
    {
        $devices = DeviceToken::where('user_id', $request->user()->id)
            ->where('is_active', true)
            ->get(['id', 'device_type', 'device_name', 'last_used_at', 'created_at']);

        return $this->successResponse($devices, 'Devices retrieved successfully');
    }

    /**
     * Send test notification (Admin only)
     */
    public function sendTest(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'nullable|string',
            'data' => 'nullable|array',
        ]);

        $result = $this->firebaseService->sendToUser(
            $validated['user_id'],
            $validated['title'],
            $validated['body'],
            $validated['data'] ?? [],
            null,
            $validated['type'] ?? 'general'
        );

        if ($result) {
            return $this->successResponse(null, 'Test notification sent successfully');
        }

        return $this->errorResponse('Failed to send notification', 500);
    }

    /**
     * Broadcast notification to all users (Admin only)
     */
    public function broadcast(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'nullable|string',
            'data' => 'nullable|array',
        ]);

        $result = $this->firebaseService->sendToAll(
            $validated['title'],
            $validated['body'],
            $validated['data'] ?? [],
            null,
            $validated['type'] ?? 'general'
        );

        return $this->successResponse(
            $result,
            'Broadcast notification sent successfully'
        );
    }

    /**
     * Send notification to all citizens (Admin only)
     */
    public function sendToCitizens(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'nullable|string',
            'data' => 'nullable|array',
        ]);

        // Get all citizen user IDs
        $citizenIds = \App\Models\User::where('role', 'citizen')->pluck('id')->toArray();

        if (empty($citizenIds)) {
            return $this->errorResponse('No citizens found', 404);
        }

        $result = $this->firebaseService->sendToUsers(
            $citizenIds,
            $validated['title'],
            $validated['body'],
            $validated['data'] ?? [],
            null,
            $validated['type'] ?? 'general'
        );

        return $this->successResponse(
            $result,
            'Notification sent to all citizens successfully'
        );
    }

    /**
     * Send notification to specific citizen IDs (Admin only)
     */
    public function sendToBulkCitizens(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'nullable|string',
            'data' => 'nullable|array',
        ]);

        $result = $this->firebaseService->sendToUsers(
            $validated['user_ids'],
            $validated['title'],
            $validated['body'],
            $validated['data'] ?? [],
            null,
            $validated['type'] ?? 'general'
        );

        return $this->successResponse(
            $result,
            'Notification sent to selected users successfully'
        );
    }

    /**
     * Send notification to all admins and superadmins (Admin only)
     */
    public function sendToAdmins(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'nullable|string',
            'data' => 'nullable|array',
        ]);

        // Get all admin and superadmin user IDs
        $adminIds = \App\Models\User::whereIn('role', ['admin', 'superadmin'])->pluck('id')->toArray();

        if (empty($adminIds)) {
            return $this->errorResponse('No admins found', 404);
        }

        $result = $this->firebaseService->sendToUsers(
            $adminIds,
            $validated['title'],
            $validated['body'],
            $validated['data'] ?? [],
            null,
            $validated['type'] ?? 'general'
        );

        return $this->successResponse(
            $result,
            'Notification sent to all admins successfully'
        );
    }

    /**
     * Send notification to all employees (Admin only)
     */
    public function sendToEmployees(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'nullable|string',
            'data' => 'nullable|array',
        ]);

        // Get all employee user IDs
        $employeeIds = \App\Models\User::where('role', 'employee')->pluck('id')->toArray();

        if (empty($employeeIds)) {
            return $this->errorResponse('No employees found', 404);
        }

        $result = $this->firebaseService->sendToUsers(
            $employeeIds,
            $validated['title'],
            $validated['body'],
            $validated['data'] ?? [],
            null,
            $validated['type'] ?? 'general'
        );

        return $this->successResponse(
            $result,
            'Notification sent to all employees successfully'
        );
    }

    /**
     * Send notification by role (Admin only)
     */
    public function sendByRole(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|string|in:citizen,employee,admin,superadmin',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'nullable|string',
            'data' => 'nullable|array',
        ]);

        // Get all user IDs with the specified role
        $userIds = \App\Models\User::where('role', $validated['role'])->pluck('id')->toArray();

        if (empty($userIds)) {
            return $this->errorResponse('No users found with role: ' . $validated['role'], 404);
        }

        $result = $this->firebaseService->sendToUsers(
            $userIds,
            $validated['title'],
            $validated['body'],
            $validated['data'] ?? [],
            null,
            $validated['type'] ?? 'general'
        );

        return $this->successResponse(
            $result,
            "Notification sent to all {$validated['role']}s successfully"
        );
    }
}
