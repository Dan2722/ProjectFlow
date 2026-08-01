<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\SystemActivityNotification;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['project', 'assignedUser'])->latest()->get();
        
        // جلب المشاريع والموظفين لتعبئة القوائم المنسدلة في واجهة المهام
        $projects = Project::all();
        $employees = User::where('role', 'employee')->get(); // أو User::all() إذا لم تفعيل الأدوار بعد

        return view('tasks.index', compact('tasks', 'projects', 'employees'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'task_title'     => 'required|string|max:255',
        'project_id'     => 'required|exists:projects,project_id',
        'assigned_to'    => 'nullable|exists:users,id',
        'task_description' => 'required|string',
        'start_task'     => 'required|date',
        'end_task'       => 'required|date|after_or_equal:start_task',
        'status'         => 'required|string',
    ]);

    $task = Task::create($validated);

    // بقية كود الإشعارات لديكِ...
    return redirect()->back()->with('success', 'تم إضافة المهمة بنجاح');
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

    $task = Task::findOrFail($id);
    $task->update($validated);

    return redirect()->back()->with('success', 'تم تحديث المهمة بنجاح');
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
}