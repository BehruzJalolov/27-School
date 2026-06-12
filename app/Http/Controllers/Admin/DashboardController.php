<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Gallery;
use App\Models\Post;
use App\Models\Schedule;
use App\Models\Statistic;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Real ma'lumotlarni olish
        $totalEmployees = Employee::count();
        $totalPosts = Post::count();
        $totalGallery = Gallery::count();
        $totalSchedules = Schedule::count();
        $totalUsers = User::count();

        // Statistics modelidan eng so'nggi ma'lumotni olish
        $latestStatistic = Statistic::latest()->first();

        // So'nggi 5 ta yangilik
        $latestPosts = Post::latest()->take(5)->get();

        // So'nggi 5 ta foydalanuvchi
        $latestUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalEmployees',
            'totalPosts',
            'totalGallery',
            'totalSchedules',
            'totalUsers',
            'latestStatistic',
            'latestPosts',
            'latestUsers'
        ));
    }
}