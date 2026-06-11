<?php

namespace App\Console\Commands;

use App\Models\PhoneVerification;
use Illuminate\Console\Command;

class CleanupExpiredOtpCommand extends Command
{
    protected $signature = 'otp:cleanup';

    protected $description = 'Muddati o\'tgan OTP kodlarini tozalash';

    public function handle(): int
    {
        $deleted = PhoneVerification::query()
            ->where('expires_at', '<', now()->subDay())
            ->delete();

        $this->info("{$deleted} ta eskirgan OTP yozuvi o'chirildi.");

        return self::SUCCESS;
    }
}
