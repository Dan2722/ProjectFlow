@extends('layouts.app')
@section('title', 'المشاريع - تفاصيل المشروع')
@section('content-class', 'p-4 flex-grow-1 bg-white')

@push('styles')
<style>
    /* تخصيص لون وشكل شريط التمرير الداخلي لأقسام الحالات */
    .flex-grow-1.overflow-auto::-webkit-scrollbar {
        width: 6px;
    }
    .flex-grow-1.overflow-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .flex-grow-1.overflow-auto::-webkit-scrollbar-thumb {
        background: #8A84AD;
        border-radius: 10px;
    }
    .flex-grow-1.overflow-auto::-webkit-scrollbar-thumb:hover {
        background: #736d94;
    }
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $isClient = $user && $user->role === 'client';
    $isAdmin = $user && ($user->role === 'admin' || str_contains($user->email, 'adm'));
@endphp

<!-- رسائل التنبيه والنجاح -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show text-start mb-3 rounded-3 shadow-sm py-2 px-3 small" role="alert">
        <i class="fa-regular fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show text-start mb-3 rounded-3 shadow-sm py-2 px-3 small" role="alert">
        <i class="fa-regular fa-circle-xmark me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- مسار التنقل (Breadcrumb) -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0" style="font-size: 14px;">
        <li class="breadcrumb-item"><a class="text-decoration-none text-muted" href="{{ route('projects.index') }}" id="breadcrumbProj">المشاريع</a></li>
        <li aria-current="page" class="breadcrumb-item active fw-semibold text-dark" id="breadcrumbSub">{{ $project->project_name }}</li>
    </ol>
</nav>

<!-- عنوان الصفحة وزر إضافة مهمة (يُخفي زر الإضافة نهائياً عند تسجيل دخول العميل) -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="task-page-title m-0" id="pageMainTitle">المشاريع</h1>
    @if(!$isClient)
    <button class="btn btn-add-task d-flex align-items-center gap-2" data-bs-target="#taskModal" data-bs-toggle="modal" onclick="prepareAddModal()">
        <span>إضافة مهمة +</span>
    </button>
    @endif
</div>

@php
    // مصفوفة خريطة الأيقونات الموحدة لكل الحالات
    $statusIconsMap = [
        'قيد التنفيذ'  => ['icon' => 'fa-regular fa-id-badge', 'class' => ''],
        'قيد المراجعة' => ['icon' => 'fa-regular fa-clipboard', 'class' => ''],
        'مكتملة'       => ['icon' => 'fa-regular fa-circle-check', 'class' => 'text-success'],
        'متوقف مؤقتا'  => ['icon' => 'fa-regular fa-circle-stop', 'class' => ''],
        'متوقف مؤقتاً' => ['icon' => 'fa-regular fa-circle-stop', 'class' => ''],
        'قيد الانتظار' => ['icon' => 'fa-solid fa-list-check', 'class' => '']
    ];

    $projectStatus = $project->status ?? 'قيد التنفيذ';
    $projectStatusMeta = $statusIconsMap[$projectStatus] ?? ['icon' => 'fa-regular fa-id-badge', 'class' => ''];
@endphp

<!-- كارد المشروع الرئيسي -->
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white" style="border: 1px solid #EFEEF3 !important;">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center gap-2 mb-2">
                <h3 class="project-card-title m-0" id="cardProjTitle">{{ $project->project_name }}</h3>
                <span class="text-muted" id="companyName" style="font-size: 13px;">{{ $project->company_name ?? 'غير محدد' }}</span>
            </div>
            <p class="text-secondary mb-3" id="projDesc" style="font-size: 13px; line-height: 1.6;">
                {{ $project->project_description ?? 'لا يوجد وصف متاح لهذا المشروع.' }}
            </p>
            <div class="d-flex gap-4 text-muted" style="font-size: 12px;">
                @php
                    \Carbon\Carbon::setLocale('ar');
                @endphp
                <span id="startDateText">تاريخ البداية : {{ $project->start_project ? \Carbon\Carbon::parse($project->start_project)->translatedFormat('d F Y') : 'غير محدد' }}</span>
                <span><i class="fa-solid fa-arrow-left-long mx-1"></i> <span id="endDateText">تاريخ الانتهاء : {{ $project->end_project ? \Carbon\Carbon::parse($project->end_project)->translatedFormat('d F Y') : 'غير محدد' }}</span></span>
            </div>
        </div>
        
        @php
            $totalTasksCount = $project->tasks->count();
            $completedTasksCount = $project->tasks->where('status', 'مكتملة')->count();
            $progressPercentage = $totalTasksCount > 0 ? round(($completedTasksCount / $totalTasksCount) * 100) : 0;
        @endphp

        <div class="col-lg-4 mt-3 mt-lg-0 text-lg-end">
            <div class="d-flex align-items-center justify-content-lg-end gap-2 mb-2">
                <div class="d-flex align-items-center gap-2" style="color: #000000; font-size: 14px; font-weight: 400;">
                    <span id="statusInProgress">{{ $projectStatus }}</span>
                    <i class="{{ $projectStatusMeta['icon'] }} {{ $projectStatusMeta['class'] }}" style="font-size: 16px; color: #8A84AD;"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 12px;">
                    <span class="text-muted" id="progressLabel">نسبة الإنجاز</span>
                    <span class="fw-bold" style="color: #8A84AD;">{{ $progressPercentage }}%</span>
                </div>
                <div class="progress" style="height: 6px; background-color: #EFEEF3;">
                    <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="{{ $progressPercentage }}" class="progress-bar rounded-pill" role="progressbar" style="width: {{ $progressPercentage }}%; background-color: #8A84AD;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- شبكة أعمدة الحالات -->
@php
    $statuses = [
        'قيد التنفيذ'  => ['icon' => 'fa-regular fa-id-badge', 'class' => ''],
        'قيد المراجعة' => ['icon' => 'fa-regular fa-clipboard', 'class' => ''],
        'مكتملة'       => ['icon' => 'fa-regular fa-circle-check', 'class' => 'text-success'],
        'متوقف مؤقتا'  => ['icon' => 'fa-regular fa-circle-stop', 'class' => ''],
        'قيد الانتظار' => ['icon' => 'fa-solid fa-list-check', 'class' => '']
    ];
@endphp

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    @foreach($statuses as $statusName => $statusMeta)
        @php
            $filteredTasks = $project->tasks->where('status', $statusName);
            $tasksCount = $filteredTasks->count();
        @endphp

        <div class="col">
            <div class="card border rounded-4 p-3 bg-white shadow-sm d-flex flex-column" style="border-color: #EFEEF3 !important; height: 440px;">
                <!-- رأس العمود -->
                <div class="status-header d-flex align-items-center justify-content-start gap-2 mb-3">
                    <span class="status-title">{{ $statusName }}</span>
                    <i class="{{ $statusMeta['icon'] }} status-icon status-success-icon {{ $statusMeta['class'] }} ms-auto" style="color: #8A84AD;"></i>
                </div>

                <div class="flex-grow-1 overflow-auto pe-1" style="max-height: 350px;">
                    <div class="d-flex flex-column gap-3">
                        @forelse($filteredTasks as $task)
                            <div class="card border rounded-3 p-3 bg-white task-card d-flex flex-column justify-content-between shadow-xs" 
                                   style="border-color: #EFEEF3 !important;"
                                   data-task-id="{{ $task->task_id }}" 
                                   data-task-title="{{ $task->task_title }}"
                                   data-project-id="{{ $task->project_id }}"
                                   data-assigned-to="{{ $task->assigned_to }}"
                                   data-description="{{ $task->task_description }}"
                                   data-start-date="{{ $task->start_task }}"
                                   data-end-date="{{ $task->end_task }}"
                                   data-status="{{ $task->status }}"
                                   data-company-name="{{ optional($task->project)->company_name }}">
                                
                                <div>
                                    <a class="fw-bold task-name text-decoration-none text-dark" href="{{ route('tasks.show', $task->task_id) }}" style="font-size: 14px;">
                                        {{ $task->task_title }}
                                    </a>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top">
                                    <div class="text-muted end-date" style="font-size: 11px;">
                                        {{ $task->end_task ? \Carbon\Carbon::parse($task->end_task)->translatedFormat('d F Y') : 'غير محدد' }}
                                    </div>

                                    <div class="task-actions d-flex align-items-center gap-2" style="font-size: 14px;">
                                        {{-- أزرار التعديل والحذف للأدمن فقط --}}
                                        @if($isAdmin)
                                            <button class="btn-icon border-0 bg-transparent p-0" onclick="openEditModal(this)" style="color: #8A84AD;"><i class="fa-regular fa-pen-to-square"></i></button>
                                            <button class="btn-icon border-0 bg-transparent p-0" onclick="openDeleteModal(this)" style="color: #8A84AD;"><i class="fa-regular fa-trash-can"></i></button>
                                        @endif

                                        {{-- أيقونة وعدد التعليقات تظهر للجميع (بما فيهم العميل) --}}
                                        <div class="d-flex align-items-center gap-1" style="color: #8A84AD;">
                                            <i class="fa-regular fa-comment"></i>
                                            <span style="font-size: 12px;">{{ $task->comments_count ?? ($task->comments ? $task->comments->count() : 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4 small" style="font-size: 12px;">
                                لا توجد مهام {{ $statusName }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@push('modals')
{{-- الموديلات تظهر للأدمن فقط للإضافة والتعديل، وتُحجب تماماً عن العميل --}}
@if(!$isClient)
    {{-- Modal إضافة / تعديل المهمة --}}
    <div aria-hidden="true" class="modal fade" id="taskModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="modal-title m-0" id="taskModalTitle" style="font-size: 18px; font-weight: 700;">إضافة مهمة</h3>
                    <button aria-label="Close" class="btn-close m-0" data-bs-dismiss="modal" type="button"></button>
                </div>
                
                <form id="taskForm" action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="taskFormMethod" value="POST">

                    <div class="mb-3">
                        <label class="form-label custom-label">اسم المهمة <span class="text-danger">*</span></label>
                        <input class="form-control custom-input w-100" id="taskNameInput" name="task_title" required type="text" placeholder="أدخل اسم المهمة"/>
                    </div>

                    <div class="mb-3">
                        <label class="form-label custom-label">اسم المشروع <span class="text-danger">*</span></label>
                        <select class="form-select custom-input w-100" id="projectIdInput" name="project_id" required>
                            <option value="{{ $project->project_id }}" selected>{{ $project->project_name }}</option>
                            @foreach($projects ?? [] as $p)
                                @if($p->project_id != $project->project_id)
                                    <option value="{{ $p->project_id }}">{{ $p->project_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label custom-label">اسم الشركة <span class="text-danger">*</span></label>
                            <select class="form-select custom-input w-100" id="companyNameInput" required>
                                <option value="{{ $project->company_name }}" selected>{{ $project->company_name ?? 'اختر الشركة' }}</option>
                            </select>
                        </div>
                        
                        <div class="col-6">
                            <label class="form-label custom-label">مسند إلى</label>
                            <select class="form-select custom-input w-100" id="assignedToInput" name="assigned_to">
                                <option value="">اختر الموظف</option>
                                @foreach($employees ?? [] as $employee)
                                    <option value="{{ $employee->employee_id ?? $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label custom-label">الوصف <span class="text-danger">*</span></label>
                        <textarea class="form-control custom-input w-100" id="descriptionInput" name="task_description" required rows="2" placeholder="أدخل وصف المهمة"></textarea>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label custom-label">تاريخ البدء <span class="text-danger">*</span></label>
                            <input class="form-control custom-date-btn w-100" id="startDateInput" name="start_task" required type="date"/>
                        </div>
                        <div class="col-6">
                            <label class="form-label custom-label">تاريخ الانتهاء <span class="text-danger">*</span></label>
                            <input class="form-control custom-date-btn w-100" id="endDateInput" name="end_task" required type="date"/>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label custom-label">الحالة <span class="text-danger">*</span></label>
                        <select class="form-select custom-input w-100" id="statusSelect" name="status" required>
                            <option value="قيد التنفيذ">قيد التنفيذ</option>
                            <option value="قيد المراجعة">قيد المراجعة</option>
                            <option value="مكتملة">مكتملة</option>
                            <option value="متوقف مؤقتاً">متوقف مؤقتاً</option>
                            <option value="قيد الانتظار">قيد الانتظار</option>
                        </select>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-save px-5" type="submit">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal الحذف (للأدمن فقط) --}}
    @if($isAdmin)
    <div aria-hidden="true" class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content custom-modal text-center p-4">
                <h4 class="delete-text mb-4 fw-bold" id="deleteModalText">هل تريد حذف المهمة؟</h4>
                <form id="deleteTaskForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex justify-content-center gap-3">
                        <button type="submit" class="btn btn-delete-confirm">حذف</button>
                        <button type="button" class="btn btn-delete-cancel" data-bs-dismiss="modal">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endif
@endpush