<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Notifications\SystemActivityNotification;
use Illuminate\Support\Facades\Storage;

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

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        // التحقق من أن المستخدم هو صاحب التعليق
        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'comment_text' => 'nullable|string',
            'attachment'   => 'nullable|file|mimes:pdf,doc,docx,zip,fig,jpg,jpeg,png,gif|max:10240',
        ]);

        if (empty($request->comment_text) && !$request->hasFile('attachment') && !$comment->attachment && $request->input('remove_attachment') != '1') {
            return back()->withErrors(['comment_text' => 'يجب كتابة تعليق أو إرفاق ملف أو صورة.']);
        }

        // إذا طلب المستخدم إزالة المرفق الحالي
        if ($request->input('remove_attachment') == '1') {
            if ($comment->attachment && Storage::disk('public')->exists($comment->attachment)) {
                Storage::disk('public')->delete($comment->attachment);
            }
            $comment->attachment = null;
        }

        // إذا تم إرفاق ملف أو صورة جديدة للاستبدال
        if ($request->hasFile('attachment')) {
            if ($comment->attachment && Storage::disk('public')->exists($comment->attachment)) {
                Storage::disk('public')->delete($comment->attachment);
            }

            $path = $request->file('attachment')->store('comments', 'public');
            $comment->attachment = $path;
        }

        $comment->comment_text = $request->comment_text ?? '';
        $comment->save();

        return redirect()->back();
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }

        if ($comment->attachment && Storage::disk('public')->exists($comment->attachment)) {
            Storage::disk('public')->delete($comment->attachment);
        }

        $comment->delete();

        return redirect()->back();
    }
}