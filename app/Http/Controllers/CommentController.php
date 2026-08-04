<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Notifications\SystemActivityNotification;

class CommentController extends Controller
{
    public function store(Request $request, $task_id)
    {
        $request->validate([
            'comment_text' => 'nullable|string',
            'attachment'   => 'nullable|file|mimes:pdf,doc,docx,zip,fig|max:10240',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,gif|max:10240',
        ]);

        $userId = auth()->check() ? auth()->id() : null;

        if (empty($request->comment_text) && !$request->hasFile('attachment') && !$request->hasFile('image')) {
            return back()->withErrors(['comment_text' => 'يجب كتابة تعليق أو إرفاق ملف أو صورة.']);
        }

        // إذا تم إرفاق ملف
        if ($request->hasFile('attachment')) {
            $pathFile = $request->file('attachment')->store('comments', 'public');
            Comment::create([
                'comment_text' => $request->comment_text ?? '',
                'attachment'   => $pathFile,
                'task_id'      => $task_id,
                'user_id'      => $userId,
            ]);
            // نصفر النص للتعليق الثاني لو أرسل ملف وصورة معاً لكي لا يتكرر النص مرتين
            $request->merge(['comment_text' => '']);
        }

        // إذا تم إرفاق صورة
        if ($request->hasFile('image')) {
            $pathImage = $request->file('image')->store('comments', 'public');
            Comment::create([
                'comment_text' => $request->comment_text ?? '',
                'attachment'   => $pathImage,
                'task_id'      => $task_id,
                'user_id'      => $userId,
            ]);
        }

        // إذا لم يتم إرفاق لا ملف ولا صورة بل نص فقط
        if (!$request->hasFile('attachment') && !$request->hasFile('image')) {
            Comment::create([
                'comment_text' => $request->comment_text,
                'attachment'   => null,
                'task_id'      => $task_id,
                'user_id'      => $userId,
            ]);
        }

        if (auth()->check()) {
            try {
                auth()->user()->notify(new SystemActivityNotification(
                    'تعليق جديد',
                    'تم إضافة تعليق جديد على المهمة'
                ));
            } catch (\Exception $e) {}
        }

        return redirect()->back();
    }
}