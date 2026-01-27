<?php

namespace App\Services;

use App\Models\DeviceToken;
use App\Models\Notification;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Exception;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        try {
            $credentialsPath = config('services.firebase.credentials');

            if (!$credentialsPath || !file_exists($credentialsPath)) {
                Log::warning('Firebase credentials file not found');
                $this->messaging = null;
                return;
            }

            $factory = (new Factory)->withServiceAccount($credentialsPath);
            $this->messaging = $factory->createMessaging();
        } catch (Exception $e) {
            Log::error('Failed to initialize Firebase: ' . $e->getMessage());
            $this->messaging = null;
        }
    }

    /**
     * Send notification to a single device token
     */
    public function sendToDevice(string $deviceToken, string $title, string $body, array $data = [])
    {
        if (!$this->messaging) {
            Log::warning('Firebase messaging not initialized');
            return false;
        }

        try {
            $notification = FirebaseNotification::create($title, $body);

            $message = CloudMessage::withTarget('token', $deviceToken)
                ->withNotification($notification)
                ->withData($data);

            // Android-specific configuration
            $message = $message->withAndroidConfig(
                AndroidConfig::fromArray([
                    'priority' => 'high',
                    'notification' => [
                        'sound' => 'default',
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    ],
                ])
            );

            // iOS-specific configuration
            $message = $message->withApnsConfig(
                ApnsConfig::fromArray([
                    'headers' => [
                        'apns-priority' => '10',
                    ],
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                            'badge' => 1,
                        ],
                    ],
                ])
            );

            $this->messaging->send($message);

            return true;
        } catch (Exception $e) {
            Log::error('Failed to send notification to device: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send notification to multiple device tokens
     */
    public function sendToMultipleDevices(array $deviceTokens, string $title, string $body, array $data = [])
    {
        if (!$this->messaging) {
            Log::warning('Firebase messaging not initialized');
            return ['success' => 0, 'failure' => count($deviceTokens)];
        }

        $successCount = 0;
        $failureCount = 0;

        foreach ($deviceTokens as $token) {
            $result = $this->sendToDevice($token, $title, $body, $data);
            if ($result) {
                $successCount++;
            } else {
                $failureCount++;
            }
        }

        return [
            'success' => $successCount,
            'failure' => $failureCount,
        ];
    }

    /**
     * Send notification to a specific user (all their devices)
     */
    public function sendToUser($userId, string $title, string $body, array $data = [], ?string $imageUrl = null, ?string $type = null)
    {
        // Save notification to database
        $notification = Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'body' => $body,
            'type' => $type,
            'data' => $data,
        ]);

        // Get all active device tokens for the user
        $deviceTokens = DeviceToken::where('user_id', $userId)
            ->where('is_active', true)
            ->pluck('device_token')
            ->toArray();

        if (empty($deviceTokens)) {
            Log::info("No active device tokens found for user: {$userId}");
            return false;
        }

        // Send to all devices
        $result = $this->sendToMultipleDevices($deviceTokens, $title, $body, $data);

        // Mark notification as sent if at least one device received it
        if ($result['success'] > 0) {
            $notification->markAsSent();
            return true;
        }

        return false;
    }

    /**
     * Send notification to multiple users
     */
    public function sendToUsers(array $userIds, string $title, string $body, array $data = [], ?string $imageUrl = null, ?string $type = null)
    {
        $successCount = 0;
        $failureCount = 0;

        foreach ($userIds as $userId) {
            $result = $this->sendToUser($userId, $title, $body, $data, null, $type);
            if ($result) {
                $successCount++;
            } else {
                $failureCount++;
            }
        }

        return [
            'success' => $successCount,
            'failure' => $failureCount,
        ];
    }

    /**
     * Send notification to all users (broadcast)
     */
    public function sendToAll(string $title, string $body, array $data = [], ?string $imageUrl = null, ?string $type = null)
    {
        // Get all active device tokens
        $deviceTokens = DeviceToken::where('is_active', true)
            ->pluck('device_token')
            ->toArray();

        if (empty($deviceTokens)) {
            Log::info("No active device tokens found for broadcast");
            return false;
        }

        // Create notification records for all users
        $userIds = DeviceToken::where('is_active', true)
            ->distinct()
            ->pluck('user_id')
            ->toArray();

        foreach ($userIds as $userId) {
            Notification::create([
                'user_id' => $userId,
                'title' => $title,
                'body' => $body,
                'type' => $type,
                'data' => $data,
            ]);
        }

        // Send to all devices
        return $this->sendToMultipleDevices($deviceTokens, $title, $body, $data);
    }

    /**
     * Validate and test a device token
     */
    public function validateToken(string $deviceToken): bool
    {
        try {
            $this->sendToDevice(
                $deviceToken,
                'Test Notification',
                'Your device is successfully connected',
                ['test' => true]
            );
            return true;
        } catch (Exception $e) {
            Log::error('Token validation failed: ' . $e->getMessage());
            return false;
        }
    }
}
