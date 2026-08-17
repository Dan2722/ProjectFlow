<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Notifications\SystemActivityNotification;

class ProjectController extends Controller
{
    private function isClient() { $user = auth()->user(); if (!$user) return false; return Client::where('email', $user->email)->exists(); }
    private function isEmployee() { $user = auth()->user(); if (!$user) return false; return $user->email === 'empLayan@fvs.com.sa'; }

    public function index()
    {
        $user = auth()->user();
        $client = Client::where('email', $user->email)->first();

        if ($client) {
            $projects = Project::where('project_name', $client->project_name)
                    ->with(['employee', 'tasks'])
                    ->get();
        } else {
            $projects = Project::with(['employee', 'tasks'])->get();
        }

        return view('projects.index', compact('projects'));
    }

    public function store(Request $request)
    {
        if ($this->isClient() || $this->isEmployee()) {
            abort(403, 'عذراً، لا تمتلك صلاحية إضافة مشاريع.');
        }

        $request->validate([
            'project_name'        => 'required|string|max:255',
            'company_name'        => 'required|string|max:255',
            'project_description' => 'required|string',
            'start_project'       => 'required|date|after_or_equal:today', // قيد عدم قبول تواريخ ماضية عند الإنشاء
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

        // مزامنة وتحديد الحالة بناءً على المهام
        if (method_exists($project, 'syncStatus')) {
            $project->syncStatus();
        }

        if (auth()->check()) {
            auth()->user()->notify(new SystemActivityNotification(
                'إضافة مشروع',
                'تم إضافة مشروع جديد: ' . $project->project_name,
                route('projects.show', $project->project_id)
            ));
        }

        return redirect()->route('projects.index')->with('success', 'تم إضافة المشروع بنجاح');
    }

    public function show($id)
    {
        $project = Project::with(['tasks', 'user'])->findOrFail($id);

        if ($this->isClient()) {
            $client = Client::where('email', auth()->user()->email)->first();
            if ($client && $project->project_name !== $client->project_name) {
                abort(403, 'عذراً، ليس لديك صلاحية استعراض هذا المشروع.');
            }
        }

        return view('projects.show', compact('project'));
    }

    public function update(Request $request, $id)
    {
        if ($this->isClient()) {
            abort(403, 'عذراً، لا تمتلك صلاحية تعديل المشاريع.');
        }

        $project = Project::findOrFail($id);

        if ($this->isEmployee()) {
            $request->validate(['status' => 'required|string']);
            // الموظف يعدل حالة المشروع بشكل يدوي أو تتم المزامنة
            $project->update(['status' => $request->status]);
            
            if (method_exists($project, 'syncStatus')) {
                $project->syncStatus();
            }

            return redirect()->back()->with('success', 'تم تحديث حالة المشروع بنجاح');
        }

        $request->validate([
            'project_name'        => 'required|string|max:255',
            'company_name'        => 'required|string|max:255',
            'project_description' => 'required|string',
            'start_project'       => ['required', 'date', 'after_or_equal:' . $project->start_project],
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

        // تحديث حالة المشروع ونسبته بناءً على المهام بعد التعديل
        if (method_exists($project, 'syncStatus')) {
            $project->syncStatus();
        }

        return redirect()->route('projects.index')->with('success', 'تم تعديل المشروع بنجاح');
    }
    
    public function destroy($id)
    {
        if ($this->isClient() || $this->isEmployee()) {
            abort(403, 'عذراً، لا تمتلك صلاحية حذف المشاريع.');
        }
        $project = Project::findOrFail($id);
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'تم حذف المشروع بنجاح!');
    }
}