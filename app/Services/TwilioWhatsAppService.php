<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioWhatsAppService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $this->from = config('services.twilio.whatsapp_from');

        if ($sid && $token) {
            $this->client = new Client($sid, $token);
        }
    }

    public function sendOtp($to, $otp)
    {
        if (!$this->client) {
            Log::error('Twilio credentials not configured.');
            return false;
        }

        // Ensure "whatsapp:" prefix is present
        $to = str_starts_with($to, 'whatsapp:') ? $to : 'whatsapp:' . $to;
        $from = str_starts_with($this->from, 'whatsapp:') ? $this->from : 'whatsapp:' . $this->from;

        $message = "Your verification code is: *{$otp}*";

        try {
            $this->client->messages->create(
                $to,
                [
                    'from' => $from,
                    'body' => $message
                ]
            );
            return true;
        } catch (\Exception $e) {
            Log::error("Twilio WhatsApp Error: " . $e->getMessage());
            return false;
        }
    }
}
