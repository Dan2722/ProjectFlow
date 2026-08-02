<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Notifications\SystemActivityNotification;

class CommentController extends Controller
{
public function store(Request $request, $taskId)
{
    $request->validate([
        'comment_text' => 'nullable|string',
        'attachment'   => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx,zip,fig|max:2048',
    ]);

    // التأكد من وجود نص أو ملف على الأقل
    if (empty($request->comment_text) && !$request->hasFile('attachment')) {
        return redirect()->back()->withErrors(['comment_text' => 'يرجى كتابة تعليق أو إدراج ملفات/صور قبل الإرسال.']);
    }

    $path = null;
    if ($request->hasFile('attachment')) {
        $path = $request->file('attachment')->store('attachments', 'public');
    }

    $userId = auth()->check() ? auth()->id() : null;

    Comment::create([
        // جعل النص فارغاً '' بدلاً من null لتجنب خطأ قاعدة البيانات إذا تم إرسال ملف بدون نص
        'comment_text' => $request->comment_text ?? '', 
        'attachment'   => $path,
        'task_id'      => $taskId,
        'user_id'      => $userId,
    ]);

    if (auth()->check()) {
        auth()->user()->notify(new SystemActivityNotification(
            'تعليق جديد',
            'تم إضافة تعليق جديد على المهمة',
            route('tasks.index')
        ));
    }

    return redirect()->back()->with('success', 'تمت إضافة التعليق بنجاح!');
}
}