<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\EmployeeController;

Route::middleware(['auth'])->group(function () {
    
    // ... مسارات لوحة التحكم والبرفايل والإعدادات الخاصة بك ...
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // مسارات الـ Resources (متاحة للجميع، بينما الحماية ومنع التعديل/الحذف يتم التحكم به عبر الكنترولر والـ Blade)
    Route::resource('projects', ProjectController::class);
    Route::resource('tasks', TaskController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('employees', EmployeeController::class); // <-- تم جعلها عادية ليتمكن الموظف من الاستطلاع

    // ... باقي المسارات التابعة لك ...
    Route::get('/project-tasks/{task}', fn ($task) => view('tasks.project-show', compact('task')))->name('tasks.project-show');
    Route::post('/tasks/{taskId}/comments', [CommentController::class, 'store'])->name('comments.store');

});

Route::resource('employees', EmployeeController::class);
// تحويل أي رابط أدمن قديم إلى لوحة التحكم الرئيسية فوراً لتجنب خطأ 404
Route::redirect('/admin/dashboard', '/dashboard');
Route::redirect('/admin', '/dashboard');

// 1. الصفحة الرئيسية
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// 2. مسارات تسجيل الدخول
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('loginUser');
    Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
});

// 3. المسارات المحمية
Route::middleware(['auth'])->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');

    Route::resource('projects', ProjectController::class);
    Route::resource('tasks', TaskController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('employees', EmployeeController::class);

    Route::get('/project-tasks/{task}', fn ($task) => view('tasks.project-show', compact('task')))->name('tasks.project-show');
    Route::post('/tasks/{taskId}/comments', [CommentController::class, 'store'])->name('comments.store');

    Route::get('/notifications/read', function () {
        if (auth()->check()) {
            auth()->user()->unreadNotifications->markAsRead();
        }
        return response()->json(['status' => 'success']);
    })->name('notifications.read');
});

Route::middleware(['auth'])->group(function () {
    // ... المسارات الأخرى ...

    Route::get('/project-tasks/{task}', fn ($task) => view('tasks.project-show', compact('task')))->name('tasks.project-show');
    Route::post('/tasks/{taskId}/comments', [CommentController::class, 'store'])->name('comments.store');
    
    // أضف هذه السطور:
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});