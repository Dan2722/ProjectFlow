<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // تحديد الرويل والصلاحيات وحفظها في قاعدة البيانات تلقائياً
            if (str_contains($user->email, 'adm')) {
                $user->role = 'admin';
                $user->save();
                return redirect()->intended('dashboard');
            } 
            elseif (str_contains($user->email, 'emp')) {
                $user->role = 'employee';
                $user->save();
                return redirect()->route('tasks.index');
            } 
            else {
                // أي إيميل عادي يُعتبر عميلاً ويتم تحديث الحقل في قاعدة البيانات ليكون client
                $user->role = 'client';
                $user->save();
                return redirect()->route('projects.index');
            }
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