<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client; // تم استدعاء مودل العميل هنا

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

            // تحديد الرويل والصلاحيات تلقائياً بناءً على نوع الحساب
            if ($user->email === 'admDan@fvs.com.sa') {
                $user->role = 'admin';
                $user->save();
                return redirect()->intended('dashboard');
            } 
            elseif ($user->email === 'empLayan@fvs.com.sa') {
                $user->role = 'employee';
                $user->save();
                return redirect()->route('tasks.index');
            } 
            else {
                // التحقق إذا كان المستخدم مسجلاً كعميل في جدول clients
                $client = Client::where('email', $user->email)->first();
                if ($client) {
                    $user->role = 'client';
                    $user->save();
                    // توجيه العميل مباشرة لصفحة المشاريع (التي ستعرض مشروعه فقط)
                    return redirect()->route('projects.index');
                }
            }

            // إذا لم ينطبق أي شرط (احتياطياً)
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