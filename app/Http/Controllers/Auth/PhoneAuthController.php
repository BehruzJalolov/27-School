<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class PhoneAuthController extends Controller
{
    public function __construct(
        private readonly OtpService $otpService,
    ) {}

    public function showLoginForm(): View
    {
        return view('auth.phone-login');
    }

    public function sendLoginCode(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'min:9', 'max:20'],
        ]);

        $phone = $this->otpService->normalizePhone($validated['phone']);

        $user = User::query()->where('phone', $phone)->first();

        if (! $user) {
            return back()
                ->withInput()
                ->withErrors(['phone' => 'Bu telefon raqam ro\'yxatdan o\'tmagan. Avval ro\'yxatdan o\'ting.']);
        }

        if (! $user->is_active) {
            return back()
                ->withInput()
                ->withErrors(['phone' => 'Hisobingiz faol emas. Administrator bilan bog\'laning.']);
        }

        $this->otpService->send($phone, 'login', $request->ip());

        return redirect()
            ->route('login.verify.form', ['phone' => $phone])
            ->with('status', 'Tasdiqlash kodi telefon raqamingizga yuborildi.');
    }

    public function showVerifyForm(Request $request): View|RedirectResponse
    {
        $phone = $request->query('phone');

        if (! $phone) {
            return redirect()->route('login');
        }

        return view('auth.phone-verify', [
            'phone' => $phone,
            'purpose' => 'login',
            'submitRoute' => route('login.verify'),
            'resendRoute' => route('login.send-code'),
        ]);
    }

    public function verifyLogin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string'],
            'code' => ['required', 'string', 'size:6'],
        ]);

        $phone = $this->otpService->normalizePhone($validated['phone']);
        $this->otpService->verify($phone, $validated['code'], 'login');

        $user = User::query()->where('phone', $phone)->firstOrFail();

        if (! $user->phone_verified_at) {
            $user->update(['phone_verified_at' => now()]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended($this->redirectPathFor($user));
    }

    public function showRegisterForm(): View
    {
        return view('auth.phone-register');
    }

    public function sendRegisterCode(Request $request): RedirectResponse
    {
        $phone = $this->otpService->normalizePhone($request->input('phone', ''));

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'min:9', 'max:20'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        if (User::query()->where('phone', $phone)->exists()) {
            return back()
                ->withInput()
                ->withErrors(['phone' => 'Bu telefon raqam allaqachon ro\'yxatdan o\'tgan.']);
        }

        session([
            'register_data' => [
                'name' => $validated['name'],
                'phone' => $phone,
                'email' => $validated['email'] ?? null,
                'password' => $validated['password'] ?? null,
                'role' => UserRole::Student->value,
            ],
        ]);

        $this->otpService->send($phone, 'register', $request->ip());

        return redirect()
            ->route('register.verify.form', ['phone' => $phone])
            ->with('status', 'Tasdiqlash kodi telefon raqamingizga yuborildi.');
    }

    public function showRegisterVerifyForm(Request $request): View|RedirectResponse
    {
        $phone = $request->query('phone');

        if (! $phone || ! session('register_data')) {
            return redirect()->route('register');
        }

        return view('auth.phone-verify', [
            'phone' => $phone,
            'purpose' => 'register',
            'submitRoute' => route('register.verify'),
            'resendRoute' => route('register.send-code'),
        ]);
    }

    public function verifyRegister(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string'],
            'code' => ['required', 'string', 'size:6'],
        ]);

        $registerData = session('register_data');

        if (! $registerData) {
            return redirect()->route('register')->withErrors([
                'phone' => 'Ro\'yxatdan o\'tish ma\'lumotlari topilmadi. Qayta urinib ko\'ring.',
            ]);
        }

        $phone = $this->otpService->normalizePhone($validated['phone']);
        $this->otpService->verify($phone, $validated['code'], 'register');

        $user = User::query()->create([
            'name' => $registerData['name'],
            'phone' => $phone,
            'phone_verified_at' => now(),
            'email' => $registerData['email'] ?? $phone.'@maktab.local',
            'password' => $registerData['password']
                ? Hash::make($registerData['password'])
                : Hash::make(Str::random(32)),
            'auth_provider' => 'phone',
            'is_active' => true,
        ]);

        $user->assignRole($registerData['role']);

        session()->forget('register_data');

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended($this->redirectPathFor($user));
    }

    private function redirectPathFor(User $user): string
    {
        if ($user->hasAnyRole([UserRole::Developer->value, UserRole::Admin->value, UserRole::Teacher->value])) {
            return route('admin.dashboard', absolute: false);
        }

        return route('index', absolute: false);
    }
}
