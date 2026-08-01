<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
   public function index()
{
    // إجمالي المشاريع والمهام
    $totalProjects = Project::count();
    $totalTasks = Task::count();

    // عداد المشاريع والمهام لكل حالة (تأكدي أن أسماء الحالات في قاعدة البيانات تطابق النصوص أدناه مثل 'مكتملة', 'قيد التنفيذ'...)
    // ملاحظة: استبدلي 'completed', 'in_progress' بقيم الحالات المخزنة لديكِ في قاعدة البيانات
    
    // 1. مكتملة
    $projectCompletedCount = Project::where('status', 'مكتملة')->count();
    $taskCompletedCount = Task::where('status', 'مكتملة')->count();

    // 2. قيد المراجعة
    $projectInReviewCount = Project::where('status', 'قيد المراجعة')->count();
    $taskInReviewCount = Task::where('status', 'قيد المراجعة')->count();

    // 3. قيد التنفيذ
    $projectInProgressCount = Project::where('status', 'قيد التنفيذ')->count();
    $taskInProgressCount = Task::where('status', 'قيد التنفيذ')->count();

    // 4. قيد الانتظار
    $projectPendingCount = Project::where('status', 'قيد الانتظار')->count();
    $taskPendingCount = Task::where('status', 'قيد الانتظار')->count();

    // 5. متوقف مؤقتاً
    $projectPausedCount = Project::where('status', 'متوقف مؤقتاً')->count();
    $taskPausedCount = Task::where('status', 'متوقف مؤقتاً')->count();

    // المشاريع الأخيرة التي طلع عليها المستخدم (أو أحدث المشاريع المضافة)
    $recentProjects = Project::with('tasks')->latest()->take(5)->get();

    return view('dashboard.index', compact(
        'totalProjects', 'totalTasks',
        'projectCompletedCount', 'taskCompletedCount',
        'projectInReviewCount', 'taskInReviewCount',
        'projectInProgressCount', 'taskInProgressCount',
        'projectPendingCount', 'taskPendingCount',
        'projectPausedCount', 'taskPausedCount',
        'recentProjects'
    ));
}
}