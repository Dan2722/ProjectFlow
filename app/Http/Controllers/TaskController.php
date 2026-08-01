<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->get();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_title'       => 'required|string|max:255',
            'project_name'     => 'required|string|max:255',
            'company_name'     => 'required|string|max:255',
            'assigned_to'      => 'required|string|max:255',
            'task_description' => 'required|string',
            'start_task'        => 'required|date',
            'end_task'          => 'required|date',
            'status'           => 'required|string',
        ]);

        Task::create($validated);

        return redirect()->back()->with('success', 'تم إضافة المهمة بنجاح');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'task_title'       => 'required|string|max:255',
            'project_name'     => 'required|string|max:255',
            'company_name'     => 'required|string|max:255',
            'assigned_to'      => 'required|string|max:255',
            'task_description' => 'required|string',
            'start_task'        => 'required|date',
            'end_task'          => 'required|date',
            'status'           => 'required|string',
        ]);

        $task = Task::where('task_id', $id)->firstOrFail();
        $task->update($validated);

        return redirect()->back()->with('success', 'تم تعديل المهمة بنجاح');
    }

    public function destroy($id)
    {
        $task = Task::where('task_id', $id)->firstOrFail();
        $task->delete();

        return redirect()->back()->with('success', 'تم حذف المهمة بنجاح');
    }
}