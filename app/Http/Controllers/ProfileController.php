<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * عرض صفحة الملف الشخصي للمستخدم الحالي
     */
    public function show()
    {
        $user = Auth::user();
        
        // جلب بيانات العميل المرتبطة بـ user_id الحالي (إن وجدت)
        $client = Client::where('user_id', $user->user_id)->first();

        return view('profile.show', compact('user', 'client'));
    }

    /**
     * تحديث بيانات الملف الشخصي
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. التحقق من صحة البيانات
        $request->validate([
            'username'     => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'phone'        => ['required', 'regex:/^05[0-9]{8}$/'],
            'company_name' => 'required|string|max:255',
        ], [
            'phone.regex' => 'يجب أن يبدأ رقم الجوال بـ 05 ويتكون من 10 أرقام',
            'email.unique' => 'البريد الإلكتروني مُستخدم بالفعل',
        ]);

        // 2. تحديث جدول users (الاسم والبريد)
        $user->update([
            'username' => $request->username,
            'email'    => $request->email,
        ]);

        // 3. تحديث أو إنشاء سجل في جدول clients (الجوال والشركة)
        Client::updateOrCreate(
            ['user_id' => $user->user_id],
            [
                'phone'        => $request->phone,
                'company_name' => $request->company_name,
            ]
        );

        return redirect()->back()->with('success', 'تم حفظ التعديلات بنجاح');
    }

    /**
     * حذف حساب المستخدم الحالي
     */
    public function destroy()
    {
        $user = Auth::user();
        
        Auth::logout();
        $user->delete();

        return redirect()->route('login')->with('success', 'تم حذف الحساب بنجاح');
    }
}