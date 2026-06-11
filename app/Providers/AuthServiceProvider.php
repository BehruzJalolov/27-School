<?php

namespace App\Providers;

use App\Contracts\SmsGateway;
use App\Services\Sms\EskizSmsGateway;
use App\Services\Sms\LogSmsGateway;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SmsGateway::class, function () {
            return match (config('services.sms.driver')) {
                'eskiz' => new EskizSmsGateway,
                default => new LogSmsGateway,
            };
        });
    }
}
