<?php

namespace App\Providers;

use App\Contracts\SmsGateway;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Services\Sms\LogSmsGateway;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // SmsGateway interface'ini LogSmsGateway bilan bog'laymiz
        // Local muhitda SMS log'ga yoziladi, haqiqiy SMS yuborilmaydi
        $this->app->bind(SmsGateway::class, LogSmsGateway::class);
    }

    public function boot(): void
    {
        App::setLocale(Session::get('lang', config('app.locale')));

        Gate::policy(User::class, UserPolicy::class);
    }
}