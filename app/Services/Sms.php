<?php
namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class Sms {
    public function send(string $to, string $msg): void {
        $sid  = env('TWILIO_SID');
        $tok  = env('TWILIO_TOKEN');
        $svc  = env('TWILIO_MESSAGING_SERVICE_SID');
        $from = env('TWILIO_FROM'); // fallback

        if (!$sid || !$tok || $sid === 'PUT_YOUR_ACCOUNT_SID_HERE') { // test mode if creds missing
            Log::info("[SMS-TEST] to={$to} msg={$msg}");
            return;
        }

        $client = new Client($sid, $tok);
        $args   = ['body' => $msg] + ($svc ? ['messagingServiceSid' => $svc] : ['from' => $from]);

        $client->messages->create($to, $args);
    }
}
