@php($currentUser = $user ?? null)

<div class="mb-3">
    <label class="form-label">Ism familiya</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $currentUser?->name) }}" required>
    @error('name')<div class="text-danger">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Telefon</label>
    <input type="text" name="phone" class="form-control" value="{{ old('phone', $currentUser?->phone) }}" required>
    @error('phone')<div class="text-danger">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $currentUser?->email) }}">
    @error('email')<div class="text-danger">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Parol {{ $currentUser ? '(bo\'sh qoldiring — o\'zgarmaydi)' : '' }}</label>
    <input type="password" name="password" class="form-control" {{ $currentUser ? '' : 'required' }}>
    @error('password')<div class="text-danger">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Parolni tasdiqlash</label>
    <input type="password" name="password_confirmation" class="form-control">
</div>

<div class="mb-3">
    <label class="form-label">Rol</label>
    <select name="role" class="form-select" required>
        @foreach($roles as $role)
            <option value="{{ $role }}" @selected(old('role', $currentUser?->roles->first()?->name) === $role)>{{ $role }}</option>
        @endforeach
    </select>
    @error('role')<div class="text-danger">{{ $message }}</div>@enderror
</div>

<div class="form-check mb-3">
    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
        @checked(old('is_active', $currentUser?->is_active ?? true))>
    <label class="form-check-label" for="is_active">Faol</label>
</div>
