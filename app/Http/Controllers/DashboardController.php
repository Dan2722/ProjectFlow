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
        $user = auth()->user();
        $userId = $user->user_id ?? $user->id;

        // إجمالي المشاريع للمستخدم الحالي
        $totalProjects = Project::where('user_id', $userId)->count();
        $projectIds = Project::where('user_id', $userId)->pluck('project_id');

       // 1. حساب إحصائيات حالات المشاريع (Project Statuses)
$projectCompletedCount  = Project::where('user_id', $userId)->where('status', 'مكتملة')->count();
$projectInReviewCount   = Project::where('user_id', $userId)->where('status', 'قيد المراجعة')->count();
$projectInProgressCount = Project::where('user_id', $userId)->where('status', 'قيد التنفيذ')->count();
$projectPendingCount    = Project::where('user_id', $userId)->where('status', 'قيد الانتظار')->count();
$projectPausedCount     = Project::where('user_id', $userId)->where('status', 'متوقف مؤقتاً')->count();

        // 2. حساب إحصائيات المهام (Task Statuses)
        $totalTasks = 0;
        $taskCompletedCount = 0;
        $taskInReviewCount = 0;
        $taskInProgressCount = 0;
        $taskPendingCount = 0;
        $taskPausedCount = 0;

        if ($projectIds->count() > 0) {
            $totalTasks = Task::whereIn('project_id', $projectIds)->count();
            
            $statusColumn = Schema::hasColumn('tasks', 'status') ? 'status' : (Schema::hasColumn('tasks', 'task_status') ? 'task_status' : null);

            if ($statusColumn) {
                $taskCompletedCount  = Task::whereIn('project_id', $projectIds)->where($statusColumn, 'completed')->count();
                $taskInReviewCount   = Task::whereIn('project_id', $projectIds)->where($statusColumn, 'review')->count();
                $taskInProgressCount = Task::whereIn('project_id', $projectIds)->where($statusColumn, 'in_progress')->count();
                $taskPendingCount    = Task::whereIn('project_id', $projectIds)->where($statusColumn, 'pending')->count();
                $taskPausedCount     = Task::whereIn('project_id', $projectIds)->where($statusColumn, 'paused')->count();
            }
        }

        // جلب المشاريع الأخيرة
        $recentProjects = Project::where('user_id', $userId)->latest('project_id')->take(4)->get();

        return view('dashboard.index', compact(
            'totalProjects',
            'totalTasks',
            'projectCompletedCount',
            'projectInReviewCount',
            'projectInProgressCount',
            'projectPendingCount',
            'projectPausedCount',
            'taskCompletedCount',
            'taskInReviewCount',
            'taskInProgressCount',
            'taskPendingCount',
            'taskPausedCount',
            'recentProjects'
        ));
    }
}