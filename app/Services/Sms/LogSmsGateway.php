<?php

namespace App\Services\Sms;

use App\Contracts\SmsGateway;
use Illuminate\Support\Facades\Log;

class LogSmsGateway implements SmsGateway
{
    public function send(string $phone, string $message): bool
    {
        Log::info('SMS yuborildi (dev rejim)', [
            'phone' => $phone,
            'message' => $message,
        ]);

        return true;
    }
}
