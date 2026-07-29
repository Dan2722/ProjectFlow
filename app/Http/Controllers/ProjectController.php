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
        // جلب جميع المشاريع مع اسم المستخدم صاحب المشروع
        $projects = Project::with('user')->get();
        
        // إرسال البيانات لصفحة العرض
        return view('projects.index', compact('projects'));
    }

    // حفظ مشروع جديد
    public function store(Request $request)
{
    $request->validate([
        'project_name'        => 'required|string|max:255',
        'company_name'        => 'required|string|max:255', // التحقق من اسم الشركة
        'project_description' => 'required|string',
        'start_project'       => 'required|date',
        'end_project'         => 'required|date|after_or_equal:start_project',
        'status'              => 'required|string',
    ]);

    Project::create([
        'project_name'        => $request->project_name,
        'company_name'        => $request->company_name, // حفظ اسم الشركة
        'project_description' => $request->project_description,
        'start_project'       => $request->start_project,
        'end_project'         => $request->end_project,
        'status'              => $request->status,
        'user_id'             => auth()->id(), // أو اتركيه حسب طريقة حفظ المستخدم لديكِ
    ]);

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
        'company_name'        => 'required|string|max:255', // التحقق من اسم الشركة
        'project_description' => 'required|string',
        'start_project'       => 'required|date',
        'end_project'         => 'required|date|after_or_equal:start_project',
        'status'              => 'required|string',
    ]);

    $project = Project::findOrFail($id);

    $project->update([
        'project_name'        => $request->project_name,
        'company_name'        => $request->company_name, // تحديث اسم الشركة
        'project_description' => $request->project_description,
        'start_project'       => $request->start_project,
        'end_project'         => $request->end_project,
        'status'              => $request->status,
    ]);

    return redirect()->route('projects.index')->with('success', 'تم تعديل المشروع بنجاح');
}
    // حذف مشروع
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $projectName = $project->project_name;

        $project->delete();

        // إرسال إشعار عند حذف المشروع
        if (auth()->check()) {
            auth()->user()->notify(new SystemActivityNotification(
                'حذف مشروع',
                'تم حذف المشروع بنجاح: ' . $projectName,
                route('projects.index')
            ));
        }

        return redirect()->route('projects.index')->with('success', 'تم حذف المشروع بنجاح!');
    }
}