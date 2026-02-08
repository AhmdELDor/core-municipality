<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    use ApiResponse;

    public function send(Request $request, \App\Services\TwilioWhatsAppService $twilioService)
    {
        $request->validate([
            'phonenumber' => 'required|string',
        ]);

        $phonenumber = $request->phonenumber;
        $ipAddress = $request->ip();
        $today = Carbon::today();

        // Rate Limiting: Check Phone Number
        $phoneAttempts = Otp::where('phonenumber', $phonenumber)
            ->whereDate('created_at', $today)
            ->count();

        if ($phoneAttempts >= 5) {
            return $this->errorResponse('Daily messages limit reached for this phone number.', 429);
        }

        // Rate Limiting: Check IP Address
        $ipAttempts = Otp::where('ip_address', $ipAddress)
            ->whereDate('created_at', $today)
            ->count();

        if ($ipAttempts >= 5) {
            return $this->errorResponse('Daily messages limit reached for this IP address.', 429);
        }

        // Generate OTP
        $token = (string) rand(100000, 999999);
        $expiresAt = now()->addMinutes(5);

        // Store OTP
        Otp::create([
            'token' => $token,
            'phonenumber' => $phonenumber,
            'ip_address' => $ipAddress,
            'status' => 'pending',
            'expires_at' => $expiresAt,
        ]);

        // Send via Twilio WhatsApp
        $sent = $twilioService->sendOtp($phonenumber, $token);

        if (!$sent) {
             return $this->errorResponse('Failed to send OTP via WhatsApp. Please check logs.', 500);
        }

        return $this->successResponse(null, 'OTP sent successfully to your WhatsApp.');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'phonenumber' => 'required|string',
            'token' => 'required|string',
        ]);

        $otp = Otp::where('phonenumber', $request->phonenumber)
            ->where('token', $request->token)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            return $this->errorResponse('Invalid or expired OTP.', 400);
        }

        $otp->update([
            'status' => 'verified',
            'verified_at' => now(),
        ]);

        return $this->successResponse(null, 'OTP verified successfully.');
    }
}
