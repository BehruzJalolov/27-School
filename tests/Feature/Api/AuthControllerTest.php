<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Services\Auth\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $sendOtpRoute = '/api/auth/otp/send';
    private string $verifyOtpRoute = '/api/auth/otp/verify';

    protected function setUp(): void
    {
        parent::setUp();

        // Local muhitda OTP code always 123456
        $this->app->environment('local');
    }

    /** @test */
    public function sendOtp_telefon_raqamni_talab_qiladi(): void
    {
        $response = $this->postJson($this->sendOtpRoute, [
            'purpose' => 'login',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['phone']);
    }

    /** @test */
    public function sendOtp_purpose_login_uchun_user_topilmasa_422_qaytaradi(): void
    {
        $response = $this->postJson($this->sendOtpRoute, [
            'phone' => '998901234567',
            'purpose' => 'login',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Foydalanuvchi topilmadi yoki faol emas.',
        ]);
    }

    /** @test */
    public function sendOtp_purpose_login_uchun_muvaffaqiyatli(): void
    {
        User::create([
            'name' => 'Test',
            'phone' => '998901234567',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $response = $this->postJson($this->sendOtpRoute, [
            'phone' => '998901234567',
            'purpose' => 'login',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'phone',
        ]);
        $response->assertJson([
            'message' => 'Tasdiqlash kodi yuborildi.',
        ]);
    }

    /** @test */
    public function sendOtp_purpose_register_uchun_muvaffaqiyatli(): void
    {
        $response = $this->postJson($this->sendOtpRoute, [
            'phone' => '998901234567',
            'purpose' => 'register',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Tasdiqlash kodi yuborildi.',
        ]);
    }

    /** @test */
    public function sendOtp_allaqachon_registratsiya_qilingan_telefon(): void
    {
        User::create([
            'name' => 'Existing',
            'phone' => '998901234567',
            'email' => 'exist@test.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $response = $this->postJson($this->sendOtpRoute, [
            'phone' => '998901234567',
            'purpose' => 'register',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Bu telefon allaqachon ro\'yxatdan o\'tgan.',
        ]);
    }

    /** @test */
    public function sendOtp_invalid_purpose(): void
    {
        $response = $this->postJson($this->sendOtpRoute, [
            'phone' => '998901234567',
            'purpose' => 'invalid',
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function verifyOtp_kodni_talab_qiladi(): void
    {
        $response = $this->postJson($this->verifyOtpRoute, [
            'phone' => '998901234567',
            'purpose' => 'login',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['code']);
    }

    /** @test */
    public function verifyOtp_login_muvaffaqiyatli_token_qaytaradi(): void
    {
        $user = User::create([
            'name' => 'Test',
            'phone' => '998901234567',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        // 1. Avval OTP yuborish
        $this->postJson($this->sendOtpRoute, [
            'phone' => '998901234567',
            'purpose' => 'login',
        ]);

        // 2. OTP ni tasdiqlash (local da code = 123456)
        $response = $this->postJson($this->verifyOtpRoute, [
            'phone' => '998901234567',
            'code' => '123456',
            'purpose' => 'login',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
            'token_type',
            'user' => ['id', 'name', 'phone', 'email', 'roles', 'permissions', 'is_active'],
        ]);
        $response->assertJson([
            'token_type' => 'Bearer',
        ]);
    }

    /** @test */
    public function verifyOtp_register_yangi_user_yaratadi(): void
    {
        // 1. OTP yuborish
        $this->postJson($this->sendOtpRoute, [
            'phone' => '998901234567',
            'purpose' => 'register',
        ]);

        // 2. Registratsiyani tasdiqlash
        $response = $this->postJson($this->verifyOtpRoute, [
            'phone' => '998901234567',
            'code' => '123456',
            'purpose' => 'register',
            'name' => 'New User',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token', 'user']);

        // User mavjudligini tekshirish
        $this->assertDatabaseHas('users', [
            'phone' => '998901234567',
            'name' => 'New User',
        ]);
    }

    /** @test */
    public function verifyOtp_notogri_kod_422_qaytaradi(): void
    {
        User::create([
            'name' => 'Test',
            'phone' => '998901234567',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $this->postJson($this->sendOtpRoute, [
            'phone' => '998901234567',
            'purpose' => 'login',
        ]);

        $response = $this->postJson($this->verifyOtpRoute, [
            'phone' => '998901234567',
            'code' => '000000',
            'purpose' => 'login',
        ]);

        $response->assertStatus(422);
    }
}