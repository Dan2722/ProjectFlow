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
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        $user = Auth::user();

        // تحديد الرويل تلقائياً بناءً على الايميل المطلوب
        if ($user->email === 'admDan@fvs.com.sa') {
            $user->role = 'admin';
        } elseif ($user->email === 'empLayan@fvs.com.sa') {
            $user->role = 'employee';
        }
        $user->save();

        return redirect()->intended('dashboard');
    }

    return back()->withErrors([
        'email' => 'البيانات المدخلة غير مطابقة لطبيعة الحساب.',
    ]);
}
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}




