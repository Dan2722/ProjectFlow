@extends('layouts.app')
@section('title', 'المشاريع')
@section('content-class', 'p-4 flex-grow-1')

@section('content')
<!-- رأس الصفحة: العنوان وزر الإضافة -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <h2 class="page-title m-0">المشاريع</h2>
    <button class="btn btn-add-project px-4 py-2" onclick="prepareAddProjectModal('{{ route('projects.store') }}')" type="button">
        مشروع جديد +
    </button>
</div>

<!-- رسائل النجاح من السيرفر -->
@if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            showStatusMessage("{{ session('success') }}");
        });
    </script>
@endif

<!-- حاوية كروت المشاريع الديناميكية -->
<div class="projects-scroll-container">
    <div class="row g-4" id="projectsGrid">
        @forelse($projects as $project)
            <div class="col-12 col-md-6 col-lg-4 project-card-wrapper" 
                 data-project-id="{{ $project->project_id }}"
                 data-project-name="{{ $project->project_name }}"
                 data-company-name="{{ $project->company_name }}"
                 data-project-desc="{{ $project->project_description }}"
                 data-start-date="{{ $project->start_project }}"
                 data-end-date="{{ $project->end_project }}"
                 data-status="{{ $project->status }}">
                 
                <div class="project-card position-relative p-3">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex flex-column align-items-start text-end gap-1">
                            <h3 class="project-card-title m-0">
                                <a class="text-decoration-none text-dark" href="{{ route('projects.show', $project->project_id) }}">
                                    {{ $project->project_name }}
                                </a>
                            </h3>
                            <span class="badge-project-status my-1">{{ $project->status }}</span>
                            <p class="project-card-desc mb-0">{{ $project->project_description }}</p>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <!-- زر التعديل -->
                            <button class="btn-icon text-muted border-0 bg-transparent p-0" title="تعديل" onclick="openEditProjectModal(this, '{{ route('projects.update', $project->project_id) }}')">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            
                            <!-- زر الحذف -->
                            <button class="btn-icon text-muted border-0 bg-transparent p-0 ms-1" title="حذف" onclick="openDeleteProjectModal(this, '{{ route('projects.destroy', $project->project_id) }}')">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>
                    </div>

                   <!-- عرض اسم الشركة على اليمين والمهام على اليسار -->
<div class="d-flex align-items-center justify-content-between text-muted extra-small mb-3">
    <!-- اليمين: اسم الشركة والأيقونة -->
    <div class="d-flex align-items-center gap-1">
        <i class="fa-regular fa-building"></i>
        <span>{{ $project->company_name ? $project->company_name : 'غير محدد' }}</span>
    </div>

    <!-- اليسار: عدد المهام والأيقونة -->
    <div class="d-flex align-items-center gap-1">
        <i class="fa-solid fa-list-check"></i>
        <span>{{ $project->tasks_count ?? ($project->tasks ? $project->tasks->count() : 0) }} مهام</span>
    </div>
</div>

                    <hr class="my-2 text-muted opacity-25"/>
                    <div class="d-flex align-items-center justify-content-between text-muted extra-small pt-1">
                        <div class="d-flex align-items-center gap-1">
                            <i class="fa-regular fa-calendar-days"></i>
                            <span>البدء: {{ $project->start_project }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <i class="fa-regular fa-calendar-check"></i>
                            <span>الانتهاء: {{ $project->end_project ?? 'غير محدد' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">لا توجد مشاريع مضافة حالياً. اضغطي على "مشروع جديد" للبدء!</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('modals')
<!-- 1. مودال إضافة وتعديل مشروع -->
<div aria-hidden="true" class="modal fade" id="projectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-modal p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="modal-title m-0" id="projectModalTitle" style="font-size: 18px; font-weight: 700;">إضافة مشروع جديد</h3>
                <button aria-label="Close" class="btn-close m-0" data-bs-dismiss="modal" type="button"></button>
            </div>
            <div class="modal-body p-0">
                <form id="projectForm" action="{{ route('projects.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="projectFormMethod" value="POST">
                    
                    <!-- اسم المشروع -->
                    <div class="mb-3 text-end">
                        <label class="custom-label mb-1">اسم المشروع <span class="text-danger">*</span></label>
                        <input class="form-control custom-input text-end" id="projectNameInput" name="project_name" required type="text"/>
                    </div>

                    <!-- اسم الشركة / العميل -->
                  
<div class="mb-3 text-end">
    <label class="custom-label mb-1">اسم الشركة  <span class="text-danger">*</span></label>
    <input class="form-control custom-input text-end" id="projectCompanyNameInput" name="company_name" required type="text" placeholder="أدخلي اسم الشركة أو العميل"/>
</div>

                    <!-- الوصف -->
                    <div class="mb-3 text-end">
                        <label class="custom-label mb-1">الوصف <span class="text-danger">*</span></label>
                        <textarea class="form-control custom-input text-end" id="projectDescInput" name="project_description" rows="2" required></textarea>
                    </div>

                  <!-- التواريخ -->
<div class="row g-2 mb-3">
    <div class="col-6 text-end">
        <label class="custom-label mb-1">تاريخ البدء <span class="text-danger">*</span></label>
        <input class="form-control custom-date-btn text-center" id="projectStartDateInput" name="start_project" required type="date" min="{{ date('Y-m-d') }}"/>
    </div>
    <div class="col-6 text-end">
        <label class="custom-label mb-1">تاريخ الانتهاء <span class="text-danger">*</span></label>
        <!-- أضفنا min هنا أيضاً لتاريخ الانتهاء -->
        <input class="form-control custom-date-btn text-center" id="projectEndDateInput" name="end_project" required type="date" min="{{ date('Y-m-d') }}"/>
    </div>
</div>

                    <!-- الحالة -->
                    <div class="mb-4 text-end">
                        <label class="custom-label mb-1">الحالة <span class="text-danger">*</span></label>
                        <select class="form-select custom-input text-center" id="projectStatusSelect" name="status" required>
                            <option value="قيد التنفيذ">قيد التنفيذ</option>
                            <option value="قيد المراجعة">قيد المراجعة</option>
                            <option value="قيد الانتظار">قيد الانتظار</option>
                            <option value="متوقف مؤقتاً">متوقف مؤقتاً</option>
                            <option value="مكتملة">مكتملة</option>
                        </select>
                    </div>

                    <!-- زر الحفظ -->
                    <div class="text-center pt-2">
                        <button class="btn btn-save" type="submit">حفظ المشروع</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 2. مودال تأكيد الحذف -->
<div class="modal fade" id="deleteProjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-modal p-4 text-center">
            <div class="modal-body p-0">
                <p class="delete-text mb-4" id="deleteProjectModalText">هل تريد حذف هذا المشروع؟</p>
                <form id="deleteProjectForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex justify-content-center gap-3">
                        <button type="submit" class="btn btn-delete-confirm"> حذف</button>
                        <button type="button" class="btn btn-delete-cancel" data-bs-dismiss="modal">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 3. مودال التنبيهات والرسائل الموحد -->
<div class="modal fade" id="statusMessageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-modal p-4 text-center">
            <div class="modal-body p-0">
                <div class="mb-3">
                    <i class="fa-regular fa-circle-check status-success-icon"></i>
                </div>
                <h4 class="fw-bold mb-3" id="statusModalMessage">تمت العملية بنجاح</h4>
                <button type="button" class="btn btn-status-ok" data-bs-dismiss="modal">حسناً</button>
            </div>
        </div>
    </div>
</div>
@endpush