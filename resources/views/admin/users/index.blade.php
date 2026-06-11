@extends('layouts.adminLayout')
@section('title', 'Foydalanuvchilar')
@section('content')
<div class="main-content">
    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <style>.main-content { margin-left: 250px; padding: 20px; }</style>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Foydalanuvchilar</h4>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Yangi foydalanuvchi</a>
                </div>

                <form method="GET" class="row g-2 mb-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Qidirish..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="role" class="form-select">
                            <option value="">Barcha rollar</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" @selected(request('role') === $role->name)>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-secondary w-100">Filter</button>
                    </div>
                </form>

                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Ism</th>
                                    <th>Telefon</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Holat</th>
                                    <th>Amallar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->phone ?? '-' }}</td>
                                        <td>{{ $user->email ?? '-' }}</td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <span class="badge bg-primary">{{ $role->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($user->is_active)
                                                <span class="badge bg-success">Faol</span>
                                            @else
                                                <span class="badge bg-danger">Nofaol</span>
                                            @endif
                                        </td>
                                        <td class="d-flex gap-1">
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">Tahrirlash</a>
                                            <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-sm btn-secondary">{{ $user->is_active ? 'O\'chirish' : 'Faollashtirish' }}</button>
                                            </form>
                                            @can('delete', $user)
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('O\'chirishni tasdiqlaysizmi?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center">Foydalanuvchi topilmadi</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
