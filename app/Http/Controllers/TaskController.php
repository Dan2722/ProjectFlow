<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Notifications\SystemActivityNotification;
use App\Models\Employee;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['project', 'comments'])->get();
        $projects = Project::all();
        $employees = Employee::all(); 

        return view('tasks.index', compact('tasks', 'projects', 'employees'));
    }

    public function store(Request $request)
    {
        // حماية: الموظف ممنوع من إضافة المهام
        if (auth()->user()->email === 'empLayan@fvs.com.sa') {
            abort(403, 'عذراً، لا تمتلك صلاحية إضافة مهام.');
        }

        // جلب المشروع المرتبط للتحقق من نطاق التواريخ
        $project = Project::where('project_id', $request->project_id)->firstOrFail();

        $validated = $request->validate([
            'task_title'       => 'required|string|max:255',
            'project_id'       => 'required|exists:projects,project_id',
            'company_name'     => 'required|string',
            'assigned_to'      => 'required|exists:employees,employee_id',
            'task_description' => 'required|string',
            'start_task'       => ['required', 'date', 'after_or_equal:' . $project->start_project, 'before_or_equal:' . $project->end_project],
            'end_task'         => ['required', 'date', 'after_or_equal:start_task', 'before_or_equal:' . $project->end_project],
            'status'           => 'required|string',
        ]);

        $task = Task::create($validated);

        // إشعار إضافة المهمة مع اسم المهمة
        if (auth()->check()) {
            auth()->user()->notify(new SystemActivityNotification(
                'إضافة مهمة',
                'تم إضافة مهمة جديدة: ' . $task->task_title,
                route('tasks.show', $task->task_id)
            ));
        }

        return redirect()->route('tasks.index')->with('success', 'تم إضافة المهمة بنجاح');
    }

    public function update(Request $request, $id)
    {
        $task = Task::where('task_id', $id)->firstOrFail();

        // إذا كان المستخدم هو الموظف، نسمح له بتحديث "الحالة" فقط ونمنع تعديل باقي البيانات
        if (auth()->user()->email === 'empLayan@fvs.com.sa') {
            $request->validate([
                'status' => 'required|string',
            ]);

            $task->update([
                'status' => $request->status,
            ]);

            if (auth()->check()) {
                auth()->user()->notify(new SystemActivityNotification(
                    'تعديل حالة مهمة',
                    'تم تحديث حالة المهمة: ' . $task->task_title,
                    route('tasks.show', $task->task_id)
                ));
            }

            return redirect()->back()->with('success', 'تم تحديث حالة المهمة بنجاح');
        }

        // للآدمن: التحقق الكامل وتحديث كافة الحقول
        $project = Project::where('project_id', $request->project_id)->firstOrFail();

        $validated = $request->validate([
            'task_title'       => 'required|string|max:255',
            'project_id'       => 'required|exists:projects,project_id',
            'assigned_to'      => 'required|exists:employees,employee_id',
            'company_name'     => 'required|string',
            'task_description' => 'required|string',
            'start_task'       => ['required', 'date', 'after_or_equal:' . $project->start_project, 'before_or_equal:' . $project->end_project],
            'end_task'         => ['required', 'date', 'after_or_equal:start_task', 'before_or_equal:' . $project->end_project],
            'status'           => 'required|string',
            
        ]);

        $task->update($validated);

        // إشعار التعديل مع اسم المهمة
        if (auth()->check()) {
            auth()->user()->notify(new SystemActivityNotification(
                'تعديل مهمة',
                'تم تحديث المهمة: ' . $task->task_title,
                route('tasks.show', $task->task_id)
            ));
        }

        return redirect()->route('tasks.index')->with('success', 'تم تعديل المهمة بنجاح');
    }

    public function destroy($id)
    {
        // حماية: الموظف ممنوع من حذف المهام
        if (auth()->user()->email === 'empLayan@fvs.com.sa') {
            abort(403, 'عذراً، لا تمتلك صلاحية حذف المهام.');
        }

        $task = Task::where('task_id', $id)->firstOrFail();
        $taskTitle = $task->task_title;
        $task->delete();

        // إشعار الحذف مع اسم المهمة
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