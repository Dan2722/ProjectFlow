
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
    
    <!-- مكتملة (مشاريع مكتملة أو مهام) -->
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
            <div class="stat-subtext">مراجعات نشطة</div>
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
            <div class="stat-subtext">مشاريع ومهام جارية</div>
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
            <div class="stat-subtext">معلقة</div>
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
            <div class="stat-subtext">متوقف</div>
        </div>
    </div>
</div>
<!-- قسم المشاريع الأخيرة -->
<div class="recent-projects-section mt-4" dir="rtl">
    <h2 class="page-title mb-3 text-start">المشاريع الأخيرة</h2>
    
    <div class="recent-projects-container p-3 p-md-4">
        <div class="d-flex flex-column gap-3 recent-projects-scroll">
            @forelse($recentProjects as $project)
                <div class="project-item-card p-3 text-end">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="text-start w-100">
                            <!-- استخدام الأعمدة الفعلية من قاعدة البيانات -->
                            <h3 class="fw-bold fs-6 mb-1 text-dark text-end">{{ $project->project_title }}</h3>
                            <div class="text-muted small text-end">مشروع خاص</div>
                        </div>
                        <span class="badge badge-in-progress">{{ $project->project_status }}</span>
                    </div>


<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="fw-bold small text-dark">
                                المهام 0/0
                            </div>
                            <div class="text-muted extra-small">تاريخ الانتهاء : {{ $project->end_project ? \Carbon\Carbon::parse($project->end_project)->format('d F Y') : 'غير محدد' }}</div>
                        </div>
                        
                        <div class="d-flex align-items-center gap-2 flex-grow-1 flex-md-grow-0 justify-content-end" style="min-width: 180px;">
                            <div class="progress custom-progress flex-grow-1" style="height: 7px;">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="0" class="progress-bar custom-bg-purple" role="progressbar" style="width: 0%;"></div>
                            </div>
                            <span class="small text-muted fw-semibold">0%</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-folder-open fa-2x mb-2 text-secondary" style="color: #8A84AD;"></i>
                    <p class="fw-semibold fs-6 mb-0">لايوجد</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

