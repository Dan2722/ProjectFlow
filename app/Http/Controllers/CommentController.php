<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // حفظ تعليق جديد على مهمة
    public function store(Request $request, $taskId)
    {
        $request->validate([
            'comment_text' => 'required|string',
            'attachment'   => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048', // الحد الأقصى 2MB
        ]);

        $path = null;
        // في حال رفع ملف مرفق
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
        }

        Comment::create([
            'comment_text' => $request->comment_text,
            'attachment'   => $path,
            'task_id'      => $taskId,
            'user_id'      => auth()->id() ?? 1,
        ]);

        return redirect()->back()->with('success', 'تمت إضافة التعليق بنجاح!');
    }
}