@extends('layouts.app')
@section('title', 'الصفحة الرئيسية')
@section('content-class', 'p-4 flex-grow-1 bg-white')

@section('content')
<!-- شبكة الكروت الإحصائية -->
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3 mb-5" dir="rtl">
    <!-- إجمالي المشاريع -->
    <div class="col">
        <div class="stat-card text-end">
            <div class="stat-number">{{ $totalProjects ?? 0 }}</div>
            <div class="stat-label">
                <span>اجمالي المشاريع</span>
                <i class="fa-solid fa-briefcase"></i>
            </div>
        </div>
    </div>
    
    <!-- إجمالي المهام -->
    <div class="col">
        <div class="stat-card text-end">
            <div class="stat-number">{{ $totalTasks ?? 0 }}</div>
            <div class="stat-label">
                <span>اجمالي المهام</span>
                <i class="fa-solid fa-list-check"></i>
            </div>
        </div>
    </div>
    
    <!-- مكتملة -->
    <div class="col">
        <div class="stat-card text-end">
            <div class="stat-number">{{ $projectCompletedCount + $taskCompletedCount }}</div>
            <div class="stat-label">
                <span>مكتملة</span>
                <i class="fa-regular fa-circle-check text-success"></i>
            </div>
            <div class="stat-subtext">مشاريع: {{ $projectCompletedCount }} | مهام: {{ $taskCompletedCount }}</div>
        </div>
    </div>
    
    <!-- قيد المراجعة -->
    <div class="col">
        <div class="stat-card text-end">
            <div class="stat-number">{{ $projectInReviewCount + $taskInReviewCount }}</div>
            <div class="stat-label">
                <span>قيد المراجعة</span>
                <i class="fa-regular fa-clipboard"></i>
            </div>
            <div class="stat-subtext">مشاريع: {{ $projectInReviewCount }} | مهام: {{ $taskInReviewCount }}</div>
        </div>
    </div>
    
    <!-- قيد التنفيذ -->
    <div class="col">
        <div class="stat-card text-end">
            <div class="stat-number">{{ $projectInProgressCount + $taskInProgressCount }}</div>
            <div class="stat-label">
                <span>قيد التنفيذ</span>
                <i class="fa-solid fa-users-gear"></i>
            </div>
            <div class="stat-subtext">مشاريع: {{ $projectInProgressCount }} | مهام: {{ $taskInProgressCount }}</div>
        </div>
    </div>
    
    <!-- قيد الانتظار -->
    <div class="col">
        <div class="stat-card text-end">
            <div class="stat-number">{{ $projectPendingCount + $taskPendingCount }}</div>
            <div class="stat-label">
                <span>قيد الانتظار</span>
                <i class="fa-solid fa-bars-staggered"></i>
            </div>
            <div class="stat-subtext">مشاريع: {{ $projectPendingCount }} | مهام: {{ $taskPendingCount }}</div>
        </div>
    </div>
    
    <!-- متوقف مؤقتاً -->
    <div class="col">
        <div class="stat-card text-end">
            <div class="stat-number">{{ $projectPausedCount + $taskPausedCount }}</div>
            <div class="stat-label">
                <span>متوقف مؤقتاً</span>
                <i class="fa-regular fa-circle-stop"></i>
            </div>
            <div class="stat-subtext">مشاريع: {{ $projectPausedCount }} | مهام: {{ $taskPausedCount }}</div>
        </div>
    </div>
</div>

<!-- قسم المشاريع الأخيرة -->
<div class="recent-projects-section mt-4" dir="rtl">
    <h2 class="task-page-title mb-3 ">المشاريع الأخيرة</h2>
    
    <div class="recent-projects-container p-3 p-md-4">
        <div class="d-flex flex-column gap-3 recent-projects-scroll">
            @forelse($recentProjects as $project)
                @php
                    $totalProjectTasks = $project->tasks->count();
                    $completedProjectTasks = $project->tasks->where('status', 'مكتملة')->count();
                    $progressPercentage = $totalProjectTasks > 0 ? round(($completedProjectTasks / $totalProjectTasks) * 100) : 0;
                    
                    // تحديد كلاس البادج بناءً على حالة المشروع
                    $statusClass = match($project->project_status) {
                        'مكتملة' => 'badge-completed',
                        'قيد المراجعة' => 'badge-review',
                        'قيد التنفيذ' => 'badge-in-progress',
                        'قيد الانتظار' => 'badge-pending',
                        'متوقف مؤقتاً', 'متوقف مؤقتا' => 'badge-paused',
                        default => 'badge-in-progress'
                    };
                @endphp
                <div class="project-item-card p-3">
                    
                    <!-- السطر الأول: تفاصيل المشروع يمين (العنوان والشركة) + بادج الحالة يسار -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="text-end">
                            <h3 class="fw-bold fs-6 mb-1 text-dark">{{ $project->project_name }}</h3>
                            <div class="text-muted small">{{ $project->company_name ?? 'مشروع خاص' }}</div>
                        </div>
                        {{-- حالة المشروع الديناميكية --}}
                        <span class="badge {{ $statusClass }}">{{ $project->project_status }}</span>
                    </div>

                    <!-- السطر الثاني: معلومات المهام والتاريخ يمين + شريط التقدم والنسبة يسار -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-2">
                        <!-- جهة اليمين: المهام وتاريخ الانتهاء بالعربي -->
                        <div class="d-flex align-items-center gap-3">
                            <div class="fw-bold small text-dark">
                                المهام {{ $completedProjectTasks }}/{{ $totalProjectTasks }}
                            </div>
                            <div class="text-muted small">
                                تاريخ الانتهاء : {{ $project->end_project ? \Carbon\Carbon::parse($project->end_project)->locale('ar')->translatedFormat('d F Y') : 'غير محدد' }}
                            </div>
                        </div>

                        <!-- جهة اليسار: النسبة المئوية وشريط التقدم -->
                        <div class="d-flex align-items-center gap-2" style="width: 180px;">
                            <span class="small text-muted fw-semibold">{{ $progressPercentage }}%</span>
                            <div class="progress custom-progress flex-grow-1" style="height: 7px;">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="{{ $progressPercentage }}" 
                                     class="progress-bar custom-bg-purple" role="progressbar" 
                                     style="width: {{ $progressPercentage }}%;"></div>
                            </div>
                        </div>
                    </div>

                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-folder-open fa-2x mb-2 text-secondary" style="color: #8A84AD;"></i>
                    <p class="fw-semibold fs-6 mb-0">لا توجد مشاريع مضافة حديثاً</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection