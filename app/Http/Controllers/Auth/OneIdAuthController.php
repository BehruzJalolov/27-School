<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\OneIdService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class OneIdAuthController extends Controller
{
    public function __construct(
        private readonly OneIdService $oneIdService,
    ) {}

    public function redirect(): RedirectResponse
    {
        if (! config('services.oneid.client_id')) {
            return redirect()->route('login')->withErrors([
                'oneid' => 'OneID hozircha sozlanmagan. Administrator bilan bog\'laning.',
            ]);
        }

        return redirect()->away($this->oneIdService->getAuthorizationUrl());
    }

    public function callback(): RedirectResponse
    {
        $code = request('code');
        $state = request('state');

        if (! $code) {
            return redirect()->route('login')->withErrors([
                'oneid' => 'OneID avtorizatsiyasi bekor qilindi.',
            ]);
        }

        try {
            $this->oneIdService->validateState($state);
            $tokenData = $this->oneIdService->exchangeCode($code);
            $profile = $this->oneIdService->getUserInfo($tokenData['access_token'] ?? '');
        } catch (RuntimeException $exception) {
            report($exception);

            return redirect()->route('login')->withErrors([
                'oneid' => 'OneID orqali kirishda xatolik yuz berdi.',
            ]);
        }

        $pin = $profile['pin'] ?? null;

        if (! $pin) {
            return redirect()->route('login')->withErrors([
                'oneid' => 'OneID dan PINFL olinmadi.',
            ]);
        }

        $fullName = trim(implode(' ', array_filter([
            $profile['first_name'] ?? null,
            $profile['sur_name'] ?? null,
            $profile['mid_name'] ?? null,
        ])));

        $user = User::query()->updateOrCreate(
            ['oneid_pin' => $pin],
            [
                'name' => $fullName !== '' ? $fullName : ($profile['full_name'] ?? 'OneID User'),
                'first_name' => $profile['first_name'] ?? null,
                'last_name' => $profile['sur_name'] ?? null,
                'middle_name' => $profile['mid_name'] ?? null,
                'passport' => $profile['pport_no'] ?? null,
                'birth_date' => $profile['birth_date'] ?? null,
                'phone' => isset($profile['phone']) ? preg_replace('/\D+/', '', $profile['phone']) : null,
                'phone_verified_at' => isset($profile['phone']) ? now() : null,
                'email' => $profile['email'] ?? null,
                'password' => Hash::make(Str::random(32)),
                'auth_provider' => 'oneid',
                'is_active' => true,
            ]
        );

        if ($user->roles()->count() === 0) {
            $user->assignRole(UserRole::Student->value);
        }

        if (! $user->is_active) {
            return redirect()->route('login')->withErrors([
                'oneid' => 'Hisobingiz faol emas. Administrator bilan bog\'laning.',
            ]);
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        if ($user->hasAnyRole([UserRole::Developer->value, UserRole::Admin->value, UserRole::Teacher->value])) {
            return redirect()->intended(route('admin.admin.dashboard', absolute: false));
        }

        return redirect()->intended(route('index', absolute: false));
    }
}
