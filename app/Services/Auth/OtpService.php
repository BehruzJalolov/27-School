<?php

namespace App\Services\Auth;

use App\Jobs\SendSmsJob;
use App\Models\PhoneVerification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class OtpService
{
    public function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);

        if (str_starts_with($digits, '998')) {
            return $digits;
        }

        if (strlen($digits) === 9) {
            return '998'.$digits;
        }

        return $digits;
    }

    public function send(string $phone, string $purpose, ?string $ip = null): void
    {
        $phone = $this->normalizePhone($phone);
        $key = 'otp:'.$phone.':'.$purpose;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'phone' => "Juda ko'p urinish. {$seconds} soniyadan keyin qayta urinib ko'ring.",
            ]);
        }

        RateLimiter::hit($key, 60);

        PhoneVerification::query()
            ->where('phone', $phone)
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->delete();

        $code = app()->environment('local', 'testing')
            ? '123456'
            : str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PhoneVerification::query()->create([
            'phone' => $phone,
            'code' => $code,
            'purpose' => $purpose,
            'expires_at' => now()->addMinutes(5),
            'ip_address' => $ip,
        ]);

        $message = "Maktab tizimi: tasdiqlash kodi {$code}. Kod 5 daqiqa amal qiladi.";

        SendSmsJob::dispatch($phone, $message);
    }

    public function verify(string $phone, string $code, string $purpose): PhoneVerification
    {
        $phone = $this->normalizePhone($phone);

        $verification = PhoneVerification::query()
            ->where('phone', $phone)
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (! $verification) {
            throw ValidationException::withMessages([
                'code' => 'Tasdiqlash kodi topilmadi. Qayta yuboring.',
            ]);
        }

        if ($verification->isExpired()) {
            throw ValidationException::withMessages([
                'code' => 'Tasdiqlash kodi muddati tugagan.',
            ]);
        }

        if ($verification->attempts >= 5) {
            throw ValidationException::withMessages([
                'code' => 'Juda ko\'p noto\'g\'ri urinish. Yangi kod so\'rang.',
            ]);
        }

        if ($verification->code !== $code) {
            $verification->increment('attempts');

            throw ValidationException::withMessages([
                'code' => 'Tasdiqlash kodi noto\'g\'ri.',
            ]);
        }

        $verification->update(['verified_at' => now()]);

        return $verification;
    }
}
