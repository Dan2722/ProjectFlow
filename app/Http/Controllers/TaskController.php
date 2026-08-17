<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Client;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Notifications\SystemActivityNotification;

class TaskController extends Controller
{
    private function isClient()
    {
        $user = auth()->user();
        if (!$user) return false;
        return Client::where('email', $user->email)->exists();
    }

    private function isEmployee()
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->email === 'empLayan@fvs.com.sa';
    }

    public function index()
    {
        $user = auth()->user();

        if ($this->isClient()) {
            $client = Client::where('email', $user->email)->first();
            $tasks = Task::whereHas('project', function ($query) use ($client) {
                $query->where('project_name', $client->project_name);
            })->with('project')->get();
        } else {
            $tasks = Task::with('project')->get();
        }

        $projects = Project::all();
        // جلب جميع الموظفين من قاعدة البيانات لضمان ظهورهم بالكامل في حقل "مسند إلى"
        $employees = Employee::all(); 

        return view('tasks.index', compact('tasks', 'projects', 'employees'));
    }

    public function create()
    {
        if ($this->isClient() || $this->isEmployee()) {
            abort(403, 'عذراً، لا تمتلك صلاحية إضافة مهام.');
        }
        $projects = Project::all();
        $employees = Employee::all();
        return view('tasks.create', compact('projects', 'employees'));
    }

    public function store(Request $request)
    {
        if ($this->isClient() || $this->isEmployee()) {
            abort(403, 'عذراً، لا تمتلك صلاحية إضافة مهام.');
        }

        $request->validate([
            'project_id'       => 'required|exists:projects,project_id',
            'task_title'       => 'required|string|max:255',
            'task_description' => 'required|string',
            'status'           => 'required|string',
            'start_task'       => 'required|date',
            'end_task'         => 'required|date|after_or_equal:start_task',
            'assigned_to'      => 'required',
        ]);

        $task = Task::create($request->all());

        // المهام هي التي تحدد حالة المشروع ونسبته تلقائياً
        if ($task->project && method_exists($task->project, 'syncStatus')) {
            $task->project->syncStatus();
        }

        if (auth()->check()) {
            auth()->user()->notify(new SystemActivityNotification(
                'إضافة مهمة',
                'تم إضافة مهمة جديدة: ' . $task->task_title,
                route('tasks.show', $task->task_id)
            ));
        }

        return redirect()->route('tasks.index')->with('success', 'تم إضافة المهمة بنجاح وتحديث حالة المشروع');
    }

    public function show($id)
    {
        $task = Task::with('project')->findOrFail($id);

        if ($this->isClient()) {
            $client = Client::where('email', auth()->user()->email)->first();
            if ($client && $task->project->project_name !== $client->project_name) {
                abort(403, 'عذراً، ليس لديك صلاحية استعراض هذه المهمة.');
            }
        }
return view('tasks.project-show', compact('task'));
    }

    public function edit($id)
    {
        if ($this->isClient()) {
            abort(403, 'عذراً، لا تمتلك صلاحية تعديل المهام.');
        }

        $task = Task::findOrFail($id);
        $projects = Project::all();
        $employees = Employee::all();
        return view('tasks.edit', compact('task', 'projects', 'employees'));
    }

    public function update(Request $request, $id)
    {
        if ($this->isClient()) {
            abort(403, 'عذراً، لا تمتلك صلاحية تعديل المهام.');
        }

        $task = Task::findOrFail($id);
        $oldProjectId = $task->project_id;

        if ($this->isEmployee()) {
            $request->validate(['status' => 'required|string']);
            $task->update(['status' => $request->status]);
            
            // تحديث حالة المشروع بناءً على المهام بعد تعديل الموظف للحالة
            if ($task->project && method_exists($task->project, 'syncStatus')) {
                $task->project->syncStatus();
            }

            return redirect()->back()->with('success', 'تم تحديث حالة المهمة وتحديث المشروع بنجاح');
        }

        $request->validate([
            'project_id'       => 'required|exists:projects,project_id',
            'task_title'       => 'required|string|max:255',
            'task_description' => 'required|string',
            'status'           => 'required|string',
            'start_task'       => 'required|date',
            'end_task'         => 'required|date|after_or_equal:start_task',
            'assigned_to'      => 'required',
        ]);

        $task->update($request->all());

        // تحديث حالة المشروع الحالي والمشروع القديم في حال تم نقل المهمة بين مشروعين
        if ($task->project && method_exists($task->project, 'syncStatus')) {
            $task->project->syncStatus();
        }
        if ($oldProjectId != $task->project_id) {
            $oldProject = Project::find($oldProjectId);
            if ($oldProject && method_exists($oldProject, 'syncStatus')) {
                $oldProject->syncStatus();
            }
        }

        return redirect()->route('tasks.index')->with('success', 'تم تعديل المهمة وتحديث حالة المشروع بنجاح');
    }

    public function destroy($id)
    {
        if ($this->isClient() || $this->isEmployee()) {
            abort(403, 'عذراً، لا تمتلك صلاحية حذف المهام.');
        }

        $task = Task::findOrFail($id);
        $project = $task->project; 
        
        $task->delete();

        // تحديث حالة المشروع تلقائياً بعد حذف المهمة
        if ($project && method_exists($project, 'syncStatus')) {
            $project->syncStatus();
        }

        return redirect()->route('tasks.index')->with('success', 'تم حذف المهمة وتحديث حالة المشروع بنجاح');
    }
}