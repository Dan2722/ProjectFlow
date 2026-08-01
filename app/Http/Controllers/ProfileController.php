<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * عرض صفحة الملف الشخصي للمستخدم الحالي
     */
    public function show()
    {
        $user = Auth::user();
        
        // لم نعد بحاجة لـ Client هنا لأن كل بيانات البروفايل أصبحت في جدول users
        return view('profile.show', compact('user'));
    }

    /**
     * تحديث بيانات الملف الشخصي
     */
    public function update(Request $request)
{
    $user = Auth::user();

    // 1. التحقق من صحة البيانات (الاسم والإيميل إجباري، الجوال والشركة اختياري)
    $request->validate([
        'username'     => 'required|string|max:255',
        'email'        => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
        'phone'        => ['nullable', 'regex:/^05[0-9]{8}$/'], // صارت اختيارية nullable
        'company_name' => 'nullable|string|max:255',         // صارت اختيارية nullable
    ], [
        'phone.regex'  => 'يجب أن يبدأ رقم الجوال بـ 05 ويتكون من 10 أرقام في حال إدخاله',
        'email.unique' => 'البريد الإلكتروني مُستخدم بالفعل',
    ]);

    // 2. تحديث جدول users
    $user->update([
        'username'     => $request->username,
        'email'        => $request->email,
        'phone'        => $request->phone,
        'company_name' => $request->company_name,
    ]);

    return redirect()->back()->with('success', 'تم حفظ التعديلات بنجاح');
}
}