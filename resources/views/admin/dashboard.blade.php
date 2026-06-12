@extends('layouts.adminLayout')
@section('title')
    Admin - Dashboard
@endsection
@section('content')

<div class="layout-page">
    <!-- Navbar -->
    <nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
        id="layout-navbar">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                <i class="icon-base bx bx-menu icon-md"></i>
            </a>
        </div>

        <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
            <div class="navbar-nav align-items-center me-auto">
                <div class="nav-item d-flex align-items-center">
                    <span class="w-px-22 h-px-22"><i class="icon-base bx bx-search icon-md"></i></span>
                    <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2 d-md-block d-none"
                        placeholder="Search..." aria-label="Search..." />
                </div>
            </div>

            <ul class="navbar-nav flex-row align-items-center ms-md-auto">
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ auth()->user()->display_name ?? auth()->user()->name }}</h6>
                                        <small class="text-body-secondary">Admin</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider my-1"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="icon-base bx bx-user icon-md me-3"></i><span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider my-1"></div>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item">
                                    <i class="icon-base bx bx-power-off icon-md me-3"></i><span>Log Out</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- / Navbar -->

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            
            <!-- Welcome Card -->
            <div class="row">
                <div class="col-xxl-8 mb-6 order-0">
                    <div class="card">
                        <div class="d-flex align-items-start row">
                            <div class="col-sm-7">
                                <div class="card-body">
                                    <h5 class="card-title text-primary mb-3">Xush kelibsiz, {{ auth()->user()->display_name ?? auth()->user()->name }}! 🎉</h5>
                                    <p class="mb-6">
                                        27-Maktab boshqaruv tizimiga xush kelibsiz.<br />
                                        Bugungi statistik ma'lumotlarni quyida ko'rishingiz mumkin.
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-5 text-center text-sm-left">
                                <div class="card-body pb-0 px-0 px-md-6">
                                    <img src="{{ asset('assets/img/illustrations/man-with-laptop.png') }}" height="175" alt="View Badge User" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics from database -->
                @if($latestStatistic)
                <div class="col-xxl-4 col-lg-12 col-md-4 order-1">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-6 mb-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                        <div class="avatar flex-shrink-0">
                                            <img src="{{ asset('assets/img/icons/unicons/chart-success.png') }}" alt="chart success" class="rounded" />
                                        </div>
                                    </div>
                                    <p class="mb-1">Sinflar Soni</p>
                                    <h4 class="card-title mb-3">{{ $latestStatistic->classesCount }}</h4>
                                    <small class="text-success fw-medium">
                                        <i class="icon-base bx bx-up-arrow-alt"></i> Maktab statistikasi
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-6 mb-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                        <div class="avatar flex-shrink-0">
                                            <img src="{{ asset('assets/img/icons/unicons/wallet-info.png') }}" alt="wallet info" class="rounded" />
                                        </div>
                                    </div>
                                    <p class="mb-1">O'quvchilar Soni</p>
                                    <h4 class="card-title mb-3">{{ $latestStatistic->studentsCount }}</h4>
                                    <small class="text-info fw-medium">
                                        <i class="icon-base bx bx-group"></i> Jami o'quvchilar
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Statistics Cards Row -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-6 mb-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/img/icons/unicons/users.png') }}" alt="Employees" class="rounded" />
                                </div>
                            </div>
                            <p class="mb-1">Xodimlar</p>
                            <h4 class="card-title mb-3">{{ $totalEmployees }}</h4>
                            <small class="text-success fw-medium">Jami xodimlar</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-6 mb-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/img/icons/unicons/file.png') }}" alt="Posts" class="rounded" />
                                </div>
                            </div>
                            <p class="mb-1">Yangiliklar</p>
                            <h4 class="card-title mb-3">{{ $totalPosts }}</h4>
                            <small class="text-info fw-medium">Jami yangiliklar</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-6 mb-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/img/icons/unicons/image.png') }}" alt="Gallery" class="rounded" />
                                </div>
                            </div>
                            <p class="mb-1">Galereya</p>
                            <h4 class="card-title mb-3">{{ $totalGallery }}</h4>
                            <small class="text-warning fw-medium">Jami rasmlar</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-6 mb-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/img/icons/unicons/calendar.png') }}" alt="Schedules" class="rounded" />
                                </div>
                            </div>
                            <p class="mb-1">Dars Jadvali</p>
                            <h4 class="card-title mb-3">{{ $totalSchedules }}</h4>
                            <small class="text-danger fw-medium">Jami darslar</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Latest Posts & Users -->
            <div class="row">
                <!-- So'nggi Yangiliklar -->
                <div class="col-12 col-lg-6 mb-6">
                    <div class="card h-100">
                        <h5 class="card-header">So'nggi Yangiliklar</h5>
                        <div class="card-body">
                            @if($latestPosts->count() > 0)
                            <ul class="list-group">
                                @foreach($latestPosts as $post)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ Str::limit($post['title_' . app()->getLocale()], 40) }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $post->created_at->format('d.m.Y H:i') }}</small>
                                    </div>
                                    <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <p class="text-center text-muted">Hali yangiliklar qo'shilmagan</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- So'nggi Foydalanuvchilar -->
                <div class="col-12 col-lg-6 mb-6">
                    <div class="card h-100">
                        <h5 class="card-header">So'nggi Foydalanuvchilar</h5>
                        <div class="card-body">
                            @if($latestUsers->count() > 0)
                            <ul class="list-group">
                                @foreach($latestUsers as $user)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $user->display_name ?? $user->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                    <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }} rounded-pill">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <p class="text-center text-muted">Hali foydalanuvchilar yo'q</p>
                            @endif
                            <div class="mt-3">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">
                                    Barcha foydalanuvchilar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection