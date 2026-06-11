<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class OneIdService
{
    public function getAuthorizationUrl(?string $state = null): string
    {
        $state ??= Str::random(40);
        session(['oneid_state' => $state]);

        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => config('services.oneid.client_id'),
            'redirect_uri' => config('services.oneid.redirect_uri'),
            'scope' => config('services.oneid.scope'),
            'state' => $state,
        ]);

        return rtrim(config('services.oneid.base_url'), '/')
            .config('services.oneid.endpoints.authorization')
            .'?'.$query;
    }

    public function exchangeCode(string $code): array
    {
        $response = Http::asForm()
            ->timeout(30)
            ->post(
                rtrim(config('services.oneid.base_url'), '/').config('services.oneid.endpoints.token'),
                [
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'client_id' => config('services.oneid.client_id'),
                    'client_secret' => config('services.oneid.client_secret'),
                    'redirect_uri' => config('services.oneid.redirect_uri'),
                ]
            );

        if (! $response->successful()) {
            throw new RuntimeException('OneID token olishda xatolik: '.$response->body());
        }

        return $response->json();
    }

    public function getUserInfo(string $accessToken): array
    {
        $response = Http::withToken($accessToken)
            ->timeout(30)
            ->get(
                rtrim(config('services.oneid.base_url'), '/').config('services.oneid.endpoints.user_info')
            );

        if (! $response->successful()) {
            throw new RuntimeException('OneID foydalanuvchi ma\'lumotini olishda xatolik.');
        }

        return $response->json();
    }

    public function validateState(?string $state): void
    {
        $expected = session('oneid_state');

        session()->forget('oneid_state');

        if (! $expected || ! $state || ! hash_equals($expected, $state)) {
            throw new RuntimeException('OneID state noto\'g\'ri.');
        }
    }
}
