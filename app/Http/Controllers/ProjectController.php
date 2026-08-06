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
        $user = auth()->user();

    // التحقق هل المستخدم عميل (أي مسجل في جدول clients بنفس الإيميل وليس أدمن أو موظف مثل لارين)
    $client = \App\Models\Client::where('email', $user->email)->first();

    if ($client) {
        // إذا كان عميل: نجلب فقط المشروع المرتبط به (بناءً على اسم المشروع أو الـ ID حسب ربطك)
        // ونجلب معه الموظف المسؤول ومهامه فقط
        $projects = \App\Models\Project::where('project_name', $client->project_name)
                    ->with(['employee', 'tasks']) // تأكد أن علاقة employee و tasks معرفة في مودل Project
                    ->get();
    } elseif ($user->email === 'empLayan@fvs.com.sa') {
        // صلاحيات الموظفة (لارين)
        $projects = \App\Models\Project::with(['employee', 'tasks'])->get();
    } else {
        // صلاحيات الأدمن (كل المشاريع)
        $projects = \App\Models\Project::with(['employee', 'tasks'])->get();
    }

    return view('projects.index', compact('projects'));
    }

    // حفظ مشروع جديد
    public function store(Request $request)
    {
        // حماية: الموظف ممنوع من إضافة المشاريع
        if (auth()->user()->email === 'empLayan@fvs.com.sa') {
            abort(403, 'عذراً، لا تمتلك صلاحية إضافة مشاريع.');
        }

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
        $project = Project::findOrFail($id);

        // إذا كان المستخدم هو الموظف، نسمح له بتحديث "الحالة" فقط ونمنع تعديل باقي البيانات
        if (auth()->user()->email === 'empLayan@fvs.com.sa') {
            $request->validate([
                'status' => 'required|string',
            ]);

            $project->update([
                'status' => $request->status,
            ]);

            if (auth()->check()) {
                auth()->user()->notify(new SystemActivityNotification(
                    'تعديل حالة مشروع',
                    'تم تحديث حالة المشروع: ' . $project->project_name,
                    route('projects.show', $project->project_id)
                ));
            }

            return redirect()->back()->with('success', 'تم تحديث حالة المشروع بنجاح');
        }

        // للأدمن: التحقق والتعديل الكامل لكافة الحقول
        $request->validate([
            'project_name'        => 'required|string|max:255',
            'company_name'        => 'required|string|max:255',
            'project_description' => 'required|string',
            'start_project'       => 'required|date|after_or_equal:today',
            'end_project'         => 'required|date|after_or_equal:start_project',
            'status'              => 'required|string',
        ]);

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
        // حماية: الموظف ممنوع من حذف المشاريع
        if (auth()->user()->email === 'empLayan@fvs.com.sa') {
            abort(403, 'عذراً، لا تمتلك صلاحية حذف المشاريع.');
        }

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