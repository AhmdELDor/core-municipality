<?php

use App\Http\Controllers\AttachBillController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CircularController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RequestFormController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes - Authentication
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'loginMobile']);
    Route::post('/login/admin', [AuthController::class, 'loginAdmin']);
    Route::post('/otp/send', [OtpController::class, 'send']);
    Route::post('/otp/verify', [OtpController::class, 'verify']);

    Route::middleware('auth:sanctum')->group(function () {


        Route::get('/projects', [ProjectController::class, 'index']);
        Route::get('/projects/{project}', [ProjectController::class, 'show']);

        Route::get('/circulars', [CircularController::class, 'index']);
        Route::get('/circulars/{circular}', [CircularController::class, 'show']);

        Route::get('/explores/grouped-by-users', [ExploreController::class, 'groupedByUsers']);
        Route::get('/explores/by-user/{userId}', [ExploreController::class, 'byUserId']);
        Route::get('/explores/{explore}', [ExploreController::class, 'show']);
        Route::get('/explores', [ExploreController::class, 'index']);

        Route::get('/services', [ServiceController::class, 'index']);
        Route::get('/services/{service}', [ServiceController::class, 'show']);


        Route::get('/user', function (Request $request) {return $request->user();});

        // User Profile with all related data
        Route::get('/profile', [UserController::class, 'profile']);

        // Logout route
        Route::post('/logout', [AuthController::class, 'logout']);

        // User accessible routes
        Route::post('/attach-bills/{attachBill}/pay', [AttachBillController::class, 'pay']);
        Route::post('/attach-bills/{attachBill}/unpay', [AttachBillController::class, 'unpay']);

        // Polls - Public/User read access
        Route::get('/polls/active', [PollController::class, 'active']);
        Route::get('/polls/ended', [PollController::class, 'ended']);
        Route::get('/polls', [PollController::class, 'index']);
        Route::get('/polls/{poll}', [PollController::class, 'show']);
        Route::post('/polls/{poll}/vote', [PollController::class, 'vote']);

        Route::apiResource('user-requests', UserRequestController::class);
        Route::apiResource('complaints', ComplaintController::class);
        Route::apiResource('suggestions', SuggestionController::class);
        Route::post('/explores/apply', [ExploreController::class, 'apply']);

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy']);

        // Device Token Management
        Route::post('/devices/register', [NotificationController::class, 'registerDevice']);
        Route::post('/devices/unregister', [NotificationController::class, 'unregisterDevice']);
        Route::get('/devices', [NotificationController::class, 'getDevices']);

        // Admin routes
        Route::middleware('role:admin,superadmin')->group(function () {
            Route::apiResource('users', UserController::class);
            Route::apiResource('settings', SettingController::class)->except(['destroy']);
            Route::apiResource('projects', ProjectController::class)->except(['index', 'show']);
            Route::apiResource('bills', BillController::class);
            Route::post('/bills/create-for-user', [BillController::class, 'createForUser']);
            Route::post('/attach-bills/bulk', [AttachBillController::class, 'bulkAttach']);
            Route::apiResource('attach-bills', AttachBillController::class);
            Route::apiResource('circulars', CircularController::class)->except(['index', 'show']);
            Route::apiResource('explores', ExploreController::class)->except(['index', 'show']);
            Route::post('/explores/{explore}/approve', [ExploreController::class, 'approve']);
            Route::apiResource('services', ServiceController::class)->except(['index', 'show']);

            // Polls Management
            Route::post('/polls', [PollController::class, 'store']);
            Route::put('/polls/{poll}', [PollController::class, 'update']);
            Route::delete('/polls/{poll}', [PollController::class, 'destroy']);

            Route::apiResource('request-forms', RequestFormController::class);

            // Admin Notification Management
            Route::post('/notifications/send-test', [NotificationController::class, 'sendTest']);
            Route::post('/notifications/broadcast', [NotificationController::class, 'broadcast']);
            Route::post('/notifications/send-to-citizens', [NotificationController::class, 'sendToCitizens']);
            Route::post('/notifications/send-to-bulk', [NotificationController::class, 'sendToBulkCitizens']);
            Route::post('/notifications/send-to-admins', [NotificationController::class, 'sendToAdmins']);
            Route::post('/notifications/send-to-employees', [NotificationController::class, 'sendToEmployees']);
            Route::post('/notifications/send-by-role', [NotificationController::class, 'sendByRole']);
        });
    });
