<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Otp;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse, LogsActivity;

    public function register(StoreUserRequest $request)
    {
        $validated = $request->validated();

        // Check verification status
        $isVerified = Otp::where('phonenumber', $validated['phonenumber'])
            ->where('status', 'verified')
            ->exists();

        if (!$isVerified) {
            return $this->errorResponse('Phone number is not verified. Please verify your phone number first.', 403);
        }

        $validated['password'] = Hash::make($validated['password']);

        $validated['role'] = 'citizen';

        $user = User::create($validated);
        $token = $user->createToken('auth_token')->plainTextToken;

        // Log user registration
        $this->logActivity(
            description: "New user registered: {$user->full_name}",
            logName: 'authentication',
            event: 'register',
            subject: $user,
            properties: ['role' => $user->role]
        );

        $data = [
            'user' => new UserResource($user),
            'token' => $token,
        ];

        return $this->successResponse($data, 'User registered successfully', 201);
    }

    /**
     * Login for mobile app (token-based authentication)
     */
    public function loginMobile(LoginRequest $request)
    {
        $validated = $request->validated();

        // Find user by phone number
        $user = User::where('phonenumber', $validated['phonenumber'])->first();

        // Verify user exists and password is correct
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            // Log failed login attempt
            $this->logToFile(
                "Failed mobile login attempt for phone: {$validated['phonenumber']}",
                'warning',
                'security',
                ['ip' => $request->ip()]
            );
            return $this->errorResponse('Invalid credentials', 401);
        }

        // Revoke all previous tokens for this user (optional - single device login)
        // Uncomment the line below if you want to allow only one active session per user
        // $user->tokens()->delete();

        // Create a new token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Log successful login
        $this->logActivity(
            description: "User logged in via mobile: {$user->full_name}",
            logName: 'authentication',
            event: 'login',
            subject: $user,
            properties: ['login_type' => 'mobile']
        );

        $data = [
            'user' => new UserResource($user),
            'token' => $token,
        ];

        return $this->successResponse($data, 'Login successful');
    }

    /**
     * Login for admin (token-based authentication)
     */
    public function loginAdmin(LoginRequest $request)
    {
        $validated = $request->validated();

        // Find user by phone number
        $user = User::where('phonenumber', $validated['phonenumber'])->first();

        // Verify user exists and password is correct
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            // Log failed admin login attempt
            $this->logToFile(
                "Failed admin login attempt for phone: {$validated['phonenumber']}",
                'warning',
                'security',
                ['ip' => $request->ip()]
            );
            return $this->errorResponse('Invalid credentials', 401);
        }

        // Check if user has admin or superadmin role
        if (!in_array($user->role, ['admin', 'superadmin'])) {
            // Log unauthorized admin access attempt
            $this->logActivity(
                description: "Unauthorized admin access attempt by: {$user->full_name}",
                logName: 'security',
                event: 'unauthorized_access',
                subject: $user,
                properties: ['attempted_role' => 'admin', 'user_role' => $user->role]
            );
            return $this->errorResponse('Unauthorized. Admin access required.', 403);
        }

        // Revoke all previous tokens for this user (optional - single device login)
        // Uncomment the line below if you want to allow only one active session per user
        // $user->tokens()->delete();

        // Create a new token
        $token = $user->createToken('admin_auth_token')->plainTextToken;

        // Log successful admin login
        $this->logActivity(
            description: "Admin logged in: {$user->full_name}",
            logName: 'authentication',
            event: 'admin_login',
            subject: $user,
            properties: ['role' => $user->role]
        );

        $data = [
            'user' => new UserResource($user),
            'token' => $token,
        ];

        return $this->successResponse($data, 'Admin login successful');
    }

    /**
     * Logout (revoke token)
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        // Log user logout
        $this->logActivity(
            description: "User logged out: {$user->full_name}",
            logName: 'authentication',
            event: 'logout',
            subject: $user
        );

        // Revoke the current token
        $user->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logged out successfully');
    }
}
