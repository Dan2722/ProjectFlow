@extends('layouts.app')
@section('title', 'المهام')
@section('content-class', 'p-4 flex-grow-1 d-flex flex-column overflow-hidden')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="task-page-title m-0">المهام</h2>
    <button class="btn btn-add-task d-flex align-items-center gap-2" data-bs-target="#taskModal" data-bs-toggle="modal" onclick="prepareAddModal()">
        <span>إضافة مهمة +</span>
    </button>
</div>

<div class="d-flex flex-row flex-nowrap gap-3 overflow-x-auto pb-3 Task-Style flex-grow-1 align-items-start">
    @php
        $statuses = [
            'قيد التنفيذ'  => ['icon' => 'fa-regular fa-id-badge', 'class' => ''],
            'قيد المراجعة' => ['icon' => 'fa-regular fa-clipboard', 'class' => ''],
            'مكتملة'       => ['icon' => 'fa-regular fa-circle-check', 'class' => 'text-success'],
            'متوقف مؤقتا' => ['icon' => 'fa-regular fa-circle-stop', 'class' => ''],
            'قيد الانتظار' => ['icon' => 'fa-solid fa-list-check', 'class' => '']
        ];
    @endphp

    @foreach($statuses as $statusName => $statusMeta)
        <div class="status-card-column p-3 rounded-3 bg-light" style="min-width: 300px; max-width: 320px;">
            <div class="status-header d-flex align-items-center justify-content-start gap-2 mb-3">
                <span class="status-title fw-bold">{{ $statusName }}</span>
                <i class="{{ $statusMeta['icon'] }} status-icon {{ $statusMeta['class'] }} ms-auto"></i>
            </div>
            
            <div class="task-list d-flex flex-column gap-2 overflow-y-auto px-1" style="max-height: 70vh;">
                @php
                    $filteredTasks = $tasks->where('status', $statusName);
                @endphp

                @forelse($filteredTasks as $task)
                    <div class="task-card p-3 rounded-3 bg-white border" 
                         data-task-id="{{ $task->task_id }}" 
                         data-task-title="{{ $task->task_title }}"
                         data-project-name="{{ $task->project_name }}"
                         data-company-name="{{ $task->company_name }}"
                         data-assigned-to="{{ $task->assigned_to }}"
                         data-description="{{ $task->task_description }}"
                         data-start-date="{{ $task->start_task }}"
                         data-end-date="{{ $task->end_task }}"
                         data-status="{{ $task->status }}">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h4 class="task-name m-0" style="font-size: 14px; font-weight: 600;">
                                <a class="text-decoration-none text-dark" href="{{ route('tasks.show', $task->task_id ?? $task->id) }}">{{ $task->task_title ?? $task->title }}</a>
                            </h4>
                            <div class="task-actions">
                                <button class="btn-icon text-muted me-1 border-0 bg-transparent p-0" onclick="openEditModal(this)"><i class="fa-regular fa-pen-to-square"></i></button>
                                <button class="btn-icon text-muted border-0 bg-transparent p-0" onclick="openDeleteModal(this)"><i class="fa-regular fa-trash-can"></i></button>
                            </div>
                        </div>
                        <p class="project-name mb-2 text-muted" style="font-size: 12px;">اسم المشروع : {{ $task->project_name ?? ($task->project->name ?? 'غير محدد') }}</p>
                        <div class="d-flex justify-content-between align-items-center" style="font-size: 11px;">
                            <span class="end-date text-muted">
                                تاريخ الانتهاء : {{ ($task->end_task ?? $task->end_date) ? \Carbon\Carbon::parse($task->end_task ?? $task->end_date)->format('Y/m/d') : 'غير محدد' }}
                            </span>
                            <div class="comments-count d-flex align-items-center gap-1 text-muted">
                                <i class="fa-regular fa-comment comment-icon"></i>
                                <span class="comment-num">{{ $task->comments_count ?? ($task->comments ? $task->comments->count() : 0) }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-3 extra-small" style="font-size: 12px;">
                        لا توجد مهام {{ $statusName }}
                    </div>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
@endsection

@push('modals')
{{-- Modal إضافة / تعديل المهمة --}}
<div aria-hidden="true" class="modal fade" id="taskModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-modal p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="modal-title m-0" id="taskModalTitle">إضافة مهمة</h3>
                <button aria-label="Close" class="btn-close m-0" data-bs-dismiss="modal" type="button"></button>
            </div>
            
            <form id="taskForm" action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="taskFormMethod" value="POST">

                <div class="mb-3">
                    <label class="form-label custom-label">اسم المهمة <span class="text-danger">*</span></label>
                    <input class="form-control custom-input" id="taskNameInput" name="task_title" required type="text" placeholder="أدخل اسم المهمة"/>
                </div>

                <div class="mb-3">
                    <label class="form-label custom-label">اسم المشروع <span class="text-danger">*</span></label>
                    <input class="form-control custom-input" id="projectNameInput" name="project_name" required type="text" placeholder="أدخل اسم المشروع"/>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label custom-label">اسم الشركة</label>
                        <input class="form-control custom-input" id="companyNameInput" name="company_name" type="text" placeholder="أدخل اسم الشركة" required/>
                    </div>
                    <div class="col-6">
                        <label class="form-label custom-label">مسند إلى</label>
                        <input class="form-control custom-input" id="assignedToInput" name="assigned_to" type="text" placeholder="اسم الشخص المسند إليه" required/>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label custom-label">الوصف <span class="text-danger">*</span></label>
                    <textarea class="form-control custom-input" id="descriptionInput" name="task_description" required rows="2" placeholder="أدخل وصف المهمة"></textarea>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label custom-label">تاريخ البدء <span class="text-danger">*</span></label>
                        <input class="form-control custom-date-btn" id="startDateInput" name="start_task" onchange="validateDates()" required type="date"/>
                    </div>
                    <div class="col-6">
                        <label class="form-label custom-label">تاريخ الانتهاء <span class="text-danger">*</span></label>
                        <input class="form-control custom-date-btn" id="endDateInput" name="end_task" onchange="validateDates()" required type="date"/>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label custom-label">الحالة <span class="text-danger">*</span></label>
                    <select class="form-select custom-input" id="statusSelect" name="status" required>
                        <option value="قيد التنفيذ">قيد التنفيذ</option>
                        <option value="قيد المراجعة">قيد المراجعة</option>
                        <option value="مكتملة">مكتملة</option>
                        <option value="متوقف مؤقتا">متوقف مؤقتا</option>
                        <option value="قيد الانتظار">قيد الانتظار</option>
                    </select>
                </div>

                <div class="text-center">
                    <button class="btn btn-save" type="submit">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal الحذف --}}
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
@endpush