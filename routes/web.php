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

// 1. الصفحة الرئيسية (توجيه للمستخدم حسب حالة الدخول)
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// 2. مسارات تسجيل الدخول (للضيوف فقط)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('loginUser');
    Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
});

// 3. المسارات المحمية بالكامل (تتطلب تسجيل دخول auth)
Route::middleware(['auth'])->group(function () {
    
    // تسجيل الخروج
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // لوحة التحكم الرئيسية
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // الملف الشخصي
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // الإعدادات (باستخدام SettingsController للتحكم بالعرض وتحديث كلمة المرور والإشعارات)
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');

    // إدارة المشاريع والمهام والعملاء
    Route::resource('projects', ProjectController::class);
    Route::resource('tasks', TaskController::class);
    Route::resource('clients', ClientController::class);

    // مسارات إضافية للمهام والتعليقات
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('/project-tasks/{task}', fn ($task) => view('tasks.project-show', compact('task')))->name('tasks.project-show');
    Route::post('/tasks/{taskId}/comments', [CommentController::class, 'store'])->name('comments.store');
    
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');

    // تفعيل قراءة الإشعارات
    Route::get('/notifications/read', function () {
        if (auth()->check()) {
            auth()->user()->unreadNotifications->markAsRead();
        }
        return response()->json(['status' => 'success']);
    })->name('notifications.read');
});