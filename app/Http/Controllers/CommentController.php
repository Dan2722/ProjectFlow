<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
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

        if (empty($request->comment_text) && !$request->hasFile('attachment')) {
            return redirect()->back()->withErrors(['comment_text' => 'يرجى كتابة تعليق أو إدراج ملفات/صور قبل الإرسال.']);
        }

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
        }

        $userId = auth()->check() ? auth()->id() : null;

        Comment::create([
            'comment_text' => $request->comment_text ?? '', 
            'attachment'   => $path,
            'task_id'      => $taskId,
            'user_id'      => $userId,
        ]);

        // جلب المهمة لدمج اسمها داخل الإشعار
        $task = Task::where('task_id', $taskId)->first();
        $taskTitle = $task ? $task->task_title : 'المهمة';

        if (auth()->check()) {
            auth()->user()->notify(new SystemActivityNotification(
                'تعليق جديد',
                'تم إضافة تعليق جديد على المهمة: ' . $taskTitle,
                route('tasks.show', $taskId)
            ));
        }

        return redirect()->back()->with('success', 'تمت إضافة التعليق بنجاح!');
    }
}