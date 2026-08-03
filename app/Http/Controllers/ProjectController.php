<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Notifications\SystemActivityNotification;

class ProjectController extends Controller
{
    // عرض جميع المشاريع
    public function index()
    {
        $projects = Project::with('user')->get();
        return view('projects.index', compact('projects'));
    }

    // حفظ مشروع جديد
    public function store(Request $request)
    {
        $request->validate([
            'project_name'        => 'required|string|max:255',
            'company_name'        => 'required|string|max:255',
            'project_description' => 'required|string',
            'start_project'       => 'required|date|after_or_equal:today',
            'end_project'         => 'required|date|after_or_equal:start_project',
            'status'              => 'required|string',
        ]);

        $project = Project::create([
            'project_name'        => $request->project_name,
            'company_name'        => $request->company_name,
            'project_description' => $request->project_description,
            'start_project'       => $request->start_project,
            'end_project'         => $request->end_project,
            'status'              => $request->status,
            'user_id'             => auth()->id(),
        ]);

        // إشعار إضافة المشروع مع اسم المشروع
        if (auth()->check()) {
            auth()->user()->notify(new SystemActivityNotification(
                'إضافة مشروع',
                'تم إضافة مشروع جديد: ' . $project->project_name,
                route('projects.show', $project->project_id)
            ));
        }

        return redirect()->route('projects.index')->with('success', 'تم إضافة المشروع بنجاح');
    }

    // عرض تفاصيل مشروع معين
    public function show($id)
    {
        $project = Project::with(['tasks', 'user'])->findOrFail($id);
        return view('projects.show', compact('project'));
    }

    // تحديث بيانات مشروع
    public function update(Request $request, $id)
    {
        $request->validate([
            'project_name'        => 'required|string|max:255',
            'company_name'        => 'required|string|max:255',
            'project_description' => 'required|string',
            'start_project'       => 'required|date|after_or_equal:today',
            'end_project'         => 'required|date|after_or_equal:start_project',
            'status'              => 'required|string',
        ]);

        $project = Project::findOrFail($id);

        $project->update([
            'project_name'        => $request->project_name,
            'company_name'        => $request->company_name,
            'project_description' => $request->project_description,
            'start_project'       => $request->start_project,
            'end_project'         => $request->end_project,
            'status'              => $request->status,
        ]);

        // إشعار التعديل مع اسم المشروع
        if (auth()->check()) {
            auth()->user()->notify(new SystemActivityNotification(
                'تعديل مشروع',
                'تم تعديل بيانات المشروع: ' . $project->project_name,
                route('projects.show', $project->project_id)
            ));
        }

        return redirect()->route('projects.index')->with('success', 'تم تعديل المشروع بنجاح');
    }

    // حذف مشروع
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $projectName = $project->project_name;

        $project->delete();

        // إشعار الحذف مع اسم المشروع
        if (auth()->check()) {
            auth()->user()->notify(new SystemActivityNotification(
                'حذف مشروع',
                'تم حذف المشروع: ' . $projectName,
                route('projects.index')
            ));
        }

        return redirect()->route('projects.index')->with('success', 'تم حذف المشروع بنجاح!');
    }
}