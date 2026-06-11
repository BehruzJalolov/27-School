<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Seeders\RolePermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhoneAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionsSeeder::class);
    }

    public function test_login_sends_otp_for_existing_user(): void
    {
        $user = User::factory()->create([
            'phone' => '998901111111',
            'is_active' => true,
        ]);
        $user->assignRole('student');

        $response = $this->post(route('login.send-code'), [
            'phone' => '901111111',
        ]);

        $response->assertRedirect(route('login.verify.form', ['phone' => '998901111111']));
        $this->assertDatabaseHas('phone_verifications', [
            'phone' => '998901111111',
            'purpose' => 'login',
        ]);
    }

    public function test_verify_login_logs_user_in(): void
    {
        $user = User::factory()->create([
            'phone' => '998901111111',
            'is_active' => true,
        ]);
        $user->assignRole('student');

        $this->post(route('login.send-code'), ['phone' => '901111111']);

        $response = $this->post(route('login.verify'), [
            'phone' => '998901111111',
            'code' => '123456',
        ]);

        $response->assertRedirect(route('index'));
        $this->assertAuthenticatedAs($user);
    }
}
