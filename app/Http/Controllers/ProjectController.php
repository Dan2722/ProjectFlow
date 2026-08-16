<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Notifications\SystemActivityNotification;

class ProjectController extends Controller
{
    // دوال مساعدة للتحقق من الصلاحيات
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

    // عرض جميع المشاريع (مع الفلترة للعميل)
    public function index()
    {
        $user = auth()->user();
        $client = Client::where('email', $user->email)->first();

        if ($client) {
            // العميل يرى مشروعه المرتبط فقط للاستطلاع
            $projects = Project::where('project_name', $client->project_name)
                        ->with(['employee', 'tasks'])
                        ->get();
        } elseif ($this->isEmployee()) {
            // صلاحيات الموظفة (لارين)
            $projects = Project::with(['employee', 'tasks'])->get();
        } else {
            // صلاحيات الأدمن (كل المشاريع)
            $projects = Project::with(['employee', 'tasks'])->get();
        }

        return view('projects.index', compact('projects'));
    }

    // حفظ مشروع جديد (ممنوع للعميل والموظف)
    public function store(Request $request)
    {
        if ($this->isClient() || $this->isEmployee()) {
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

        // حماية إضافية للعميل لكي لا يستعرض إلا مشروعه الخاص فقط
        if ($this->isClient()) {
            $client = Client::where('email', auth()->user()->email)->first();
            if ($client && $project->project_name !== $client->project_name) {
                abort(403, 'عذراً، ليس لديك صلاحية استعراض هذا المشروع.');
            }
        }

        return view('projects.show', compact('project'));
    }

    // تحديث بيانات مشروع
    public function update(Request $request, $id)
    {
        // حماية صارمة: العميل ممنوع من التعديل تماماً (استطلاع فقط)
        if ($this->isClient()) {
            abort(403, 'عذراً، لا تمتلك صلاحية تعديل المشاريع.');
        }

        $project = Project::findOrFail($id);

        // إذا كان المستخدم هو الموظف، نسمح له بتحديث "الحالة" فقط
        if ($this->isEmployee()) {
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

        // للأدمن: التعديل الكامل لكافة الحقول
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
        // حماية صارمة: العميل والموظف ممنوعان من الحذف تماماً
        if ($this->isClient() || $this->isEmployee()) {
            abort(403, 'عذراً، لا تمتلك صلاحية حذف المشاريع.');
        }

        $project = Project::findOrFail($id);
        $projectName = $project->project_name;

        $project->delete();

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