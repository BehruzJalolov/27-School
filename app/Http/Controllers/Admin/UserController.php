<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\Auth\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(
        private readonly OtpService $otpService,
    ) {
        // $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $request): View
    {
        $users = User::query()
            ->with('roles')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->role, fn ($q, $role) => $q->role($role))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => Role::query()->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'roles' => $this->assignableRoles(),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $phone = $this->otpService->normalizePhone($request->validated('phone'));

        $user = User::query()->create([
            'name' => $request->validated('name'),
            'phone' => $phone,
            'phone_verified_at' => now(),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
            'auth_provider' => 'admin',
            'is_active' => $request->boolean('is_active', true),
        ]);

        $user->assignRole($request->validated('role'));

        AuditLogger::log('user.created', $user, [
            'role' => $request->validated('role'),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Foydalanuvchi muvaffaqiyatli yaratildi.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user->load('roles'),
            'roles' => $this->assignableRoles(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->safe()->except(['role', 'password', 'phone']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->validated('password'));
        }

        if ($request->filled('phone')) {
            $data['phone'] = $this->otpService->normalizePhone($request->validated('phone'));
        }

        $data['is_active'] = $request->boolean('is_active');

        $user->update($data);

        if ($request->filled('role') && auth()->user()->can('assignRole', [$user, $request->validated('role')])) {
            $user->syncRoles([$request->validated('role')]);
        }

        AuditLogger::log('user.updated', $user, $request->validated());

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Foydalanuvchi yangilandi.');
    }

    public function destroy(User $user): RedirectResponse
    {
        AuditLogger::log('user.deleted', $user);
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Foydalanuvchi o\'chirildi.');
    }

    public function toggleActive(User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $user->update(['is_active' => ! $user->is_active]);

        AuditLogger::log('user.toggled_active', $user, [
            'is_active' => $user->is_active,
        ]);

        return back()->with('success', 'Foydalanuvchi holati yangilandi.');
    }

    private function assignableRoles(): array
    {
        $actor = auth()->user();

        if ($actor->hasRole(UserRole::Developer->value)) {
            return UserRole::all();
        }

        return UserRole::assignableByAdmin();
    }
}
