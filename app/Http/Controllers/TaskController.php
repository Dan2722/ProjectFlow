<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Notifications\SystemActivityNotification;

class TaskController extends Controller
{
  public function index()
{
    $tasks = Task::latest()->get();
    $projects = \App\Models\Project::all();
    
    // تمرير جميع المستخدمين لكي يظهروا في قائمة "مسند إلى"
    $employees = \App\Models\User::all(); 
    
    return view('tasks.index', compact('tasks', 'projects', 'employees'));
}
public function store(Request $request)
{
    $validated = $request->validate([
        'task_title'       => 'required|string|max:255',
        'project_id'       => 'required|exists:projects,project_id', // التأكد من مطابقة اسم المفتاح الأساسي لجدول المشاريع
        'assigned_to'      => 'nullable|exists:users,id',
        'task_description' => 'required|string',
        'start_task'       => 'required|date',
        'end_task'         => 'required|date|after_or_equal:start_task',
        'status'           => 'required|string',
    ]);

    Task::create($validated);

    return redirect()->route('tasks.index')->with('success', 'تم إضافة المهمة بنجاح');
}

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'task_title'       => 'required|string|max:255',
        'project_id'       => 'required|exists:projects,project_id',
        'assigned_to'      => 'nullable|exists:users,id',
        'task_description' => 'required|string',
        'start_task'       => 'required|date',
        'end_task'         => 'required|date|after_or_equal:start_task',
        'status'           => 'required|string',
    ]);

    $task = Task::where('task_id', $id)->firstOrFail();
    $task->update($validated);

    if (auth()->check()) {
        auth()->user()->notify(new SystemActivityNotification(
            'تعديل مهمة',
            'تم تحديث المهمة: ' . $task->task_title,
            route('tasks.index')
        ));
    }

    return redirect()->route('tasks.index')->with('success', 'تم تعديل المهمة بنجاح');
}
    public function destroy($id)
    {
        $task = Task::where('task_id', $id)->firstOrFail();
        $taskTitle = $task->task_title;
        $task->delete();

        // إشعار الحذف
        if (auth()->check()) {
            auth()->user()->notify(new SystemActivityNotification(
                'حذف مهمة',
                'تم حذف المهمة: ' . $taskTitle,
                route('tasks.index')
            ));
        }

        return redirect()->back()->with('success', 'تم حذف المهمة بنجاح');
    }
    public function show($id)
{
    $task = Task::with(['project', 'assignedUser', 'comments.user'])->findOrFail($id);
    
    return view('tasks.project-show', compact('task'));
}
}
