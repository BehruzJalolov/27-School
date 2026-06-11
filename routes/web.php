<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmpCategoryController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\InfographicController;
use App\Http\Controllers\UsefulResourceController;
use App\Http\Controllers\CategoryTopController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\HomePageImageTagController;
use App\Http\Controllers\ChildrenCategoryController;
use App\Http\Controllers\SmenaTypeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\CKEditorController;
use App\Http\Controllers\Admin\UserController;

// ----------------------------
// Language Switch
// ----------------------------
Route::get('/lang/{locale}', function ($locale) {
    session(['lang' => $locale]);
    app()->setLocale($locale);
    return redirect()->back();
})->name('lang.switch');

// ----------------------------
// Public / Frontend Routes
// ----------------------------
Route::get('/', [FrontController::class, 'index'])->name('index');
Route::get('/school-info', [FrontController::class, 'schoolTack'])->name('schooltack');
Route::get('/leadership', [FrontController::class, 'leaderShep'])->name('leaderShep');
Route::get('/leadership-detail', [FrontController::class, 'LeaderShepDatail'])->name('LeaderShepDatail');
Route::get('/teachers', [FrontController::class, 'teachers'])->name('teachers');
Route::get('/teachers/search', [EmployeeController::class, 'search'])->name('teachers.search');
Route::get('/rekvizit', [FrontController::class, 'rekvizit'])->name('rekvizit');
Route::get('/education', [FrontController::class, 'education'])->name('education');
Route::get('/education/search', [FrontController::class, 'educationSearch'])->name('education.search');
Route::get('/education/connect', [FrontController::class, 'connect'])->name('education.connect');
Route::get('/education/category/{category}', [FrontController::class, 'educationByCategory'])->name('education.category');
Route::get('/education/{id}', [FrontController::class, 'educationDetail'])->name('educationDetail');
Route::get('/news', [FrontController::class, 'schoolNews'])->name('schoolNews');
Route::get('/news/{id}', [FrontController::class, 'newsDetail'])->name('newsDetail');
Route::get('/news/search', [FrontController::class, 'searchPosts'])->name('search.posts');
Route::get('/gallery', [FrontController::class, 'Gallery'])->name('Gallery');
Route::get('/infographic', [FrontController::class, 'infoGrafika'])->name('infoGrafika');
Route::get('/useful-resources', [FrontController::class, 'usefulresurs'])->name('usefulresurs');
Route::get('/useful-resources/{id}', [FrontController::class, 'usefulResourceDetail'])->name('useful-resources.detail');
Route::get('/connect', [FrontController::class, 'connect'])->name('connect');
Route::post('/send-email', [FrontController::class, 'SendEmail'])->name('SendEmail');

// ----------------------------
// Admin Routes
// ----------------------------
Route::prefix('admin')
    ->middleware(['auth', 'active', 'permission:view admin panel'])
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');

        // CKEditor Upload
        Route::post('ckeditor/upload', [CKEditorController::class, 'upload'])
            ->middleware('permission:manage content')
            ->name('ckeditor.upload');

        // User Management
        Route::middleware('permission:manage users')->group(function () {
            Route::resource('users', UserController::class);
            Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])
                ->name('users.toggle-active');
        });

        // Content Management
        Route::middleware('permission:manage content')->group(function () {
            Route::resource('category', CategoryController::class);
            Route::resource('employee', EmployeeController::class);
            Route::resource('position', PositionController::class);
            Route::resource('empCategory', EmpCategoryController::class);
            Route::resource('CategoryTop', CategoryTopController::class);
            Route::resource('posts', PostsController::class);
            Route::resource('statictik', StatisticController::class);
            Route::resource('infografika', InfographicController::class);
            Route::resource('usefulResource', UsefulResourceController::class);
            Route::resource('HomePageImageTag', HomePageImageTagController::class);
            Route::resource('categorychildren', ChildrenCategoryController::class);
        });

        // Gallery Management
        Route::middleware('permission:manage gallery')->group(function () {
            Route::resource('gallery', GalleryController::class);
        });

        // Schedule Management
        Route::middleware('permission:manage schedule')->group(function () {
            Route::resource('smenatype', SmenaTypeController::class);
            Route::resource('schedule', ScheduleController::class);
            Route::resource('lesson', LessonController::class);
        });
    });

// ----------------------------
// Auth Profile Routes
// ----------------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
