<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Permission va rollarni yaratish (RolePermissionsSeeder ga o'xshab)
        $this->seedPermissionsAndRoles();

        // Admin foydalanuvchi yaratish va role berish
        $this->admin = User::factory()->create([
            'phone' => '998901111111',
            'is_active' => true,
        ]);
        $this->admin->assignRole(UserRole::Developer->value);
    }

    private function seedPermissionsAndRoles(): void
    {
        // Permissionlarni yaratish
        $permissions = [
            'view admin panel',
            'manage users',
            'manage content',
            'manage gallery',
            'manage schedule',
            'view own profile',
        ];

        foreach ($permissions as $perm) {
            Permission::create(['name' => $perm, 'guard_name' => 'web']);
        }

        // Rollarni yaratish
        $developerRole = Role::create(['name' => UserRole::Developer->value, 'guard_name' => 'web']);
        $adminRole = Role::create(['name' => UserRole::Admin->value, 'guard_name' => 'web']);
        $teacherRole = Role::create(['name' => UserRole::Teacher->value, 'guard_name' => 'web']);
        $studentRole = Role::create(['name' => UserRole::Student->value, 'guard_name' => 'web']);

        // Permissionlarni rollarga biriktirish
        $developerRole->givePermissionTo(Permission::all());
        $adminRole->givePermissionTo(['view admin panel', 'manage users', 'manage content', 'manage gallery', 'manage schedule']);
        $teacherRole->givePermissionTo(['view admin panel', 'manage content']);
        $studentRole->givePermissionTo(['view own profile']);
    }

    /** @test */
    public function index_userlarni_korsatadi(): void
    {
        User::factory()->count(3)->create();

        $response = $this
            ->actingAs($this->admin)
            ->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertViewHas('users');
    }

    /** @test */
    public function index_auth_qililmagan_foydalanuvchi_kirmaydi(): void
    {
        $response = $this->get(route('admin.users.index'));

        $response->assertStatus(302); // redirect to login
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function create_yangi_user_sahifasini_korsatadi(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->get(route('admin.users.create'));

        $response->assertStatus(200);
        $response->assertViewHas('roles');
    }

    /** @test */
    public function store_yangi_user_yaratadi(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->post(route('admin.users.store'), [
                'name' => 'Yangi User',
                'phone' => '998901234567',
                'email' => 'yangi@test.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => UserRole::Teacher->value,
                'is_active' => true,
            ]);

        $response->assertSessionHas('success');
        $response->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'Yangi User',
            'phone' => '998901234567',
            'email' => 'yangi@test.com',
        ]);
    }

    /** @test */
    public function store_telefon_raqamni_normalize_qiladi(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->post(route('admin.users.store'), [
                'name' => 'User 2',
                'phone' => '+998 90 123 45 67',
                'email' => 'user2@test.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => UserRole::Teacher->value,
                'is_active' => true,
            ]);

        $response->assertSessionHas('success');

        // Phone "998901234567" ga normalize qilinishi kerak
        $this->assertDatabaseHas('users', [
            'phone' => '998901234567',
        ]);
    }

    /** @test */
    public function store_validatsiyadan_otmaganda_422(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->post(route('admin.users.store'), [
                'name' => '',
                'phone' => '',
                'email' => 'invalid-email',
                'role' => 'not-a-role',
            ]);

        $response->assertSessionHasErrors(['name', 'phone', 'email']);
    }

    /** @test */
    public function edit_user_sahifasini_korsatadi(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($this->admin)
            ->get(route('admin.users.edit', $user));

        $response->assertStatus(200);
        $response->assertViewHas('user');
        $response->assertViewHas('roles');
    }

    /** @test */
    public function update_user_ozgartiradi(): void
    {
        $user = User::factory()->create([
            'name' => 'Eski Ism',
        ]);

        $response = $this
            ->actingAs($this->admin)
            ->patch(route('admin.users.update', $user), [
                'name' => 'Yangi Ism',
                'role' => UserRole::Teacher->value,
            ]);

        $response->assertSessionHas('success');
        $response->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Yangi Ism',
        ]);
    }

    /** @test */
    public function update_user_nofaqat_admini_ozgartira_oladi(): void
    {
        $user = User::factory()->create([
            'is_active' => true,
        ]);

        $response = $this
            ->actingAs($this->admin)
            ->patch(route('admin.users.update', $user), [
                'name' => 'Yangilangan',
                'is_active' => false,
                'role' => UserRole::Teacher->value,
            ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => false,
        ]);
    }

    /** @test */
    public function destroy_user_ochiradi(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($this->admin)
            ->delete(route('admin.users.destroy', $user));

        $response->assertSessionHas('success');
        $response->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function toggleActive_user_holatini_ozgartiradi(): void
    {
        $user = User::factory()->create([
            'is_active' => true,
        ]);

        $response = $this
            ->actingAs($this->admin)
            ->patch(route('admin.users.toggle-active', $user));

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => false,
        ]);

        // Yana bir marta bosganda qayta faollashadi
        $this->actingAs($this->admin)
            ->patch(route('admin.users.toggle-active', $user));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function barcha_crud_sahifalar_auth_talab_qiladi(): void
    {
        $user = User::factory()->create();

        $routes = [
            ['get', route('admin.users.index')],
            ['get', route('admin.users.create')],
            ['post', route('admin.users.store')],
            ['get', route('admin.users.edit', $user)],
            ['patch', route('admin.users.update', $user)],
            ['delete', route('admin.users.destroy', $user)],
        ];

        foreach ($routes as [$method, $url]) {
            $response = $this->{$method}($url);
            $response->assertStatus(302);
            $response->assertRedirect(route('login'));
        }
    }
}