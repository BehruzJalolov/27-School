<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function __construct(
        private readonly OtpService $otpService,
    ) {}

    public function sendOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'min:9', 'max:20'],
            'purpose' => ['required', 'in:login,register'],
        ]);

        $phone = $this->otpService->normalizePhone($validated['phone']);

        if ($validated['purpose'] === 'login') {
            $user = User::query()->where('phone', $phone)->first();

            if (! $user || ! $user->is_active) {
                return response()->json(['message' => 'Foydalanuvchi topilmadi yoki faol emas.'], 422);
            }
        }

        if ($validated['purpose'] === 'register' && User::query()->where('phone', $phone)->exists()) {
            return response()->json(['message' => 'Bu telefon allaqachon ro\'yxatdan o\'tgan.'], 422);
        }

        $this->otpService->send($phone, $validated['purpose'], $request->ip());

        return response()->json([
            'message' => 'Tasdiqlash kodi yuborildi.',
            'phone' => $phone,
        ]);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string'],
            'code' => ['required', 'string', 'size:6'],
            'purpose' => ['required', 'in:login,register'],
            'name' => ['required_if:purpose,register', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $phone = $this->otpService->normalizePhone($validated['phone']);
        $this->otpService->verify($phone, $validated['code'], $validated['purpose']);

        if ($validated['purpose'] === 'register') {
            $user = User::query()->create([
                'name' => $validated['name'],
                'phone' => $phone,
                'phone_verified_at' => now(),
                'email' => $validated['email'] ?? $phone.'@maktab.local',
                'password' => isset($validated['password'])
                    ? Hash::make($validated['password'])
                    : Hash::make(Str::random(32)),
                'auth_provider' => 'phone',
                'is_active' => true,
            ]);

            $user->assignRole(UserRole::Student->value);
        } else {
            $user = User::query()->where('phone', $phone)->firstOrFail();

            if (! $user->phone_verified_at) {
                $user->update(['phone_verified_at' => now()]);
            }
        }

        $token = $user->createToken('mobile', ['*'], now()->addDays(30))->plainTextToken;

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $this->userPayload($user),
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $this->userPayload($request->user()),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(['message' => 'Chiqildi.']);
    }

    private function userPayload(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'phone' => $user->phone,
            'email' => $user->email,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
            'is_active' => $user->is_active,
        ];
    }
}
