@extends('layouts.adminLayout')
@section('title', 'Yangi foydalanuvchi')
@section('content')
<div class="main-content">
    <section class="section">
        <div class="col-md-8">
            <style>.main-content { margin-left: 250px; padding: 20px; }</style>
            <h4 class="mb-3">Yangi foydalanuvchi</h4>

            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                @include('admin.users._form')
                <button class="btn btn-primary mt-3">Saqlash</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mt-3">Orqaga</a>
            </form>
        </div>
    </section>
</div>
@endsection
