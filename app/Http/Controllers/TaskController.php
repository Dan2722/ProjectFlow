<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // عرض جميع المهام
    public function index()
    {
        // جلب المهام مع المشروع التابعة له والتعليقات
        $tasks = Task::with(['project', 'comments'])->get();
        return view('tasks.index', compact('tasks'));
    }

    // حفظ مهمة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'task_title'       => 'required|string|max:255',
            'task_description' => 'nullable|string',
            'start_task'        => 'required|date',
            'end_task'          => 'nullable|date|after_or_equal:start_task',
            'status'            => 'required|string',
            'project_id'        => 'required|exists:projects,id',
        ]);

        Task::create([
            'task_title'       => $request->task_title,
            'task_description' => $request->task_description,
            'start_task'        => $request->start_task,
            'end_task'          => $request->end_task,
            'status'            => $request->status,
            'project_id'        => $request->project_id,
        ]);

        return redirect()->back()->with('success', 'تمت إضافة المهمة بنجاح!');
    }
}