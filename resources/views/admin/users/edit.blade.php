@extends('layouts.adminLayout')
@section('title', 'Foydalanuvchini tahrirlash')
@section('content')
<div class="main-content">
    <section class="section">
        <div class="col-md-8">
            <style>.main-content { margin-left: 250px; padding: 20px; }</style>
            <h4 class="mb-3">Foydalanuvchini tahrirlash</h4>

            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf @method('PUT')
                @include('admin.users._form', ['user' => $user])
                <button class="btn btn-primary mt-3">Yangilash</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mt-3">Orqaga</a>
            </form>
        </div>
    </section>
</div>
@endsection
