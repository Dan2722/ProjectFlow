<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // عرض صفحة تسجيل الدخول
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // معالجة بيانات تسجيل الدخول
    public function login(Request $request)
    {
        // 1. التحقق من المدخلات
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'يرجى إدخال البريد الإلكتروني',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            'password.required' => 'يرجى إدخال كلمة المرور',
        ]);

        $remember = $request->has('remember');

        // 2. محاولة تسجيل الدخول
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // التوجيه إلى الصفحة الرئيسية/الداشبورد
            return redirect()->intended(route('dashboard'));
        }

        // 3. في حال كانت البيانات غير صحيحة
        return back()->withErrors([
            'email' => 'بيانات الاعتماد هذه لا تتطابق مع سجلاتنا.',
        ])->onlyInput('email');
    }

    // تسجيل الخروج
public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login'); // تم التعديل ليوجه لصفحة تسجيل الدخول
}
}





