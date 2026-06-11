<?php

namespace App\Services\Sms;

use App\Contracts\SmsGateway;
use Illuminate\Support\Facades\Http;

class EskizSmsGateway implements SmsGateway
{
    public function send(string $phone, string $message): bool
    {
        $token = cache()->remember('eskiz_token', 60 * 60 * 23, function () {
            $response = Http::post(config('services.eskiz.base_url').'/auth/login', [
                'email' => config('services.eskiz.email'),
                'password' => config('services.eskiz.password'),
            ]);

            return $response->json('data.token');
        });

        if (! $token) {
            return false;
        }

        $response = Http::withToken($token)
            ->post(config('services.eskiz.base_url').'/message/sms/send', [
                'mobile_phone' => $phone,
                'message' => $message,
                'from' => config('services.eskiz.from'),
            ]);

        return $response->successful();
    }
}
