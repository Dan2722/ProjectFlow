@extends('layouts.app')
@section('title', 'المهام')
@section('content-class', 'p-4 flex-grow-1 d-flex flex-column overflow-hidden')

@section('content')
<!-- هيدر قسم المهام -->
<div class="d-flex justify-content-between align-items-center mb-4">
<h2 class="task-page-title m-0">المهام</h2>
<button class="btn btn-add-task d-flex align-items-center gap-2" data-bs-target="#taskModal" data-bs-toggle="modal" onclick="prepareAddModal()">
<span>إضافة مهمة +</span>
</button>
</div>
<!-- أعمدة حالات المهام (نظام Kanban أفقي متجاوب) -->
<div class="d-flex flex-row flex-nowrap gap-3 overflow-x-auto pb-3 Task-Style flex-grow-1 align-items-start">
<!-- الحالة 1: قيد التنفيذ -->
<div class="status-card-column p-3 rounded-3 bg-light" style="min-width: 300px; max-width: 320px;">
<div class="status-header d-flex align-items-center justify-content-start gap-2 mb-3">
<span class="status-title fw-bold">قيد التنفيذ</span>
<i class="fa-regular fa-id-badge status-icon ms-auto"></i>
</div>
<div class="task-list d-flex flex-column gap-2 overflow-y-auto px-1" style="max-height: 70vh;">
<!-- مهمة 1 -->
<div class="task-card p-3 rounded-3 bg-white border" data-task-name="تصميم الصفحة الرئيسية">
<div class="d-flex justify-content-between align-items-center mb-1">
<h4 class="task-name m-0" style="font-size: 14px; font-weight: 600;">
<a class="text-decoration-none text-dark" href="{{ route('tasks.show', 1) }}">تصميم الصفحة الرئيسية</a>
</h4>
<div class="task-actions">
<button class="btn-icon text-muted me-1 border-0 bg-transparent p-0" onclick="openEditModal(this)"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon text-muted border-0 bg-transparent p-0" onclick="openDeleteModal(this)"><i class="fa-regular fa-trash-can"></i></button>
</div>
</div>
<p class="project-name mb-2 text-muted" style="font-size: 12px;">اسم المشروع : تطوير تطبيق إدارة طلبات الصيانة</p>
<div class="d-flex justify-content-between align-items-center" style="font-size: 11px;">
<span class="end-date text-muted">تاريخ الانتهاء : 20 أغسطس 2026</span>
<div class="comments-count d-flex align-items-center gap-1 text-muted">
<i class="fa-regular fa-comment comment-icon"></i>
<span class="comment-num">3</span>
</div>
</div>
</div>
<!-- مهمة 2 -->
<div class="task-card p-3 rounded-3 bg-white border" data-task-name="ربط الـ API للوحة التحكم">
<div class="d-flex justify-content-between align-items-center mb-1">
<h4 class="task-name m-0" style="font-size: 14px; font-weight: 600;">ربط الـ API للوحة التحكم</h4>
<div class="task-actions">
<button class="btn-icon text-muted me-1 border-0 bg-transparent p-0" onclick="openEditModal(this)"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon text-muted border-0 bg-transparent p-0" onclick="openDeleteModal(this)"><i class="fa-regular fa-trash-can"></i></button>
</div>
</div>
<p class="project-name mb-2 text-muted" style="font-size: 12px;">اسم المشروع : لوحة تحكم FVS</p>
<div class="d-flex justify-content-between align-items-center" style="font-size: 11px;">
<span class="end-date text-muted">تاريخ الانتهاء : 22 أغسطس 2026</span>
<div class="comments-count d-flex align-items-center gap-1 text-muted">
<i class="fa-regular fa-comment comment-icon"></i>
<span class="comment-num">5</span>
</div>
</div>
</div>
<!-- مهمة 3 -->
<div class="task-card p-3 rounded-3 bg-white border" data-task-name="تطبيق Responsive للـ Sidebar">
<div class="d-flex justify-content-between align-items-center mb-1">
<h4 class="task-name m-0" style="font-size: 14px; font-weight: 600;">تطبيق Responsive للـ Sidebar</h4>
<div class="task-actions">
<button class="btn-icon text-muted me-1 border-0 bg-transparent p-0" onclick="openEditModal(this)"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon text-muted border-0 bg-transparent p-0" onclick="openDeleteModal(this)"><i class="fa-regular fa-trash-can"></i></button>
</div>
</div>
<p class="project-name mb-2 text-muted" style="font-size: 12px;">اسم المشروع : تحسينات التجاوب مع الشاشات</p>
<div class="d-flex justify-content-between align-items-center" style="font-size: 11px;">
<span class="end-date text-muted">تاريخ الانتهاء : 26 أغسطس 2026</span>
<div class="comments-count d-flex align-items-center gap-1 text-muted">
<i class="fa-regular fa-comment comment-icon"></i>
<span class="comment-num">1</span>
</div>
</div>
</div>
<!-- مهمة 4 -->
<div class="task-card p-3 rounded-3 bg-white border" data-task-name="تحسين أداء تحميل الصفحة">
<div class="d-flex justify-content-between align-items-center mb-1">
<h4 class="task-name m-0" style="font-size: 14px; font-weight: 600;">تحسين أداء تحميل الصفحة</h4>
<div class="task-actions">
<button class="btn-icon text-muted me-1 border-0 bg-transparent p-0" onclick="openEditModal(this)"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon text-muted border-0 bg-transparent p-0" onclick="openDeleteModal(this)"><i class="fa-regular fa-trash-can"></i></button>
</div>
</div>
<p class="project-name mb-2 text-muted" style="font-size: 12px;">اسم المشروع : تحسينات تجربة المستخدم</p>
<div class="d-flex justify-content-between align-items-center" style="font-size: 11px;">
<span class="end-date text-muted">تاريخ الانتهاء : 30 أغسطس 2026</span>
<div class="comments-count d-flex align-items-center gap-1 text-muted">
<i class="fa-regular fa-comment comment-icon"></i>
<span class="comment-num">4</span>
</div>
</div>
</div>
<!-- مهمة 5 -->
<div class="task-card p-3 rounded-3 bg-white border" data-task-name="إعداد اختيارات الوضع الداكن">
<div class="d-flex justify-content-between align-items-center mb-1">
<h4 class="task-name m-0" style="font-size: 14px; font-weight: 600;">إعداد اختيارات الوضع الداكن</h4>
<div class="task-actions">
<button class="btn-icon text-muted me-1 border-0 bg-transparent p-0" onclick="openEditModal(this)"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon text-muted border-0 bg-transparent p-0" onclick="openDeleteModal(this)"><i class="fa-regular fa-trash-can"></i></button>
</div>
</div>
<p class="project-name mb-2 text-muted" style="font-size: 12px;">اسم المشروع : تطوير لوحة FVS</p>
<div class="d-flex justify-content-between align-items-center" style="font-size: 11px;">
<span class="end-date text-muted">تاريخ الانتهاء : 02 سبتمبر 2026</span>
<div class="comments-count d-flex align-items-center gap-1 text-muted">
<i class="fa-regular fa-comment comment-icon"></i>
<span class="comment-num">2</span>
</div>
</div>
</div>
</div>
</div>
<!-- الحالة 2: قيد المراجعة -->
<div class="status-card-column p-3 rounded-3 bg-light" style="min-width: 300px; max-width: 320px;">
<div class="status-header d-flex align-items-center justify-content-start gap-2 mb-3">
<span class="status-title fw-bold">قيد المراجعة</span>
<i class="fa-regular fa-clipboard status-icon ms-auto"></i>
</div>
<div class="task-list d-flex flex-column gap-2 overflow-y-auto px-1" style="max-height: 70vh;">
<div class="task-card p-3 rounded-3 bg-white border" data-task-name="مراجعة واجهات المستخدم">
<div class="d-flex justify-content-between align-items-center mb-1">
<h4 class="task-name m-0" style="font-size: 14px; font-weight: 600;">مراجعة واجهات المستخدم</h4>
<div class="task-actions">
<button class="btn-icon text-muted me-1 border-0 bg-transparent p-0" onclick="openEditModal(this)"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon text-muted border-0 bg-transparent p-0" onclick="openDeleteModal(this)"><i class="fa-regular fa-trash-can"></i></button>
</div>
</div>
<p class="project-name mb-2 text-muted" style="font-size: 12px;">اسم المشروع : نظام إدارة المحتوى</p>
<div class="d-flex justify-content-between align-items-center" style="font-size: 11px;">
<span class="end-date text-muted">تاريخ الانتهاء : 25 أغسطس 2026</span>
<div class="comments-count d-flex align-items-center gap-1 text-muted">
<i class="fa-regular fa-comment comment-icon"></i>
<span class="comment-num">2</span>
</div>
</div>
</div>
</div>
</div>
<!-- الحالة 3: مكتملة -->
<div class="status-card-column p-3 rounded-3 bg-light" style="min-width: 300px; max-width: 320px;">
<div class="status-header d-flex align-items-center justify-content-start gap-2 mb-3">
<span class="status-title fw-bold">مكتملة</span>
<i class="fa-regular fa-circle-check status-icon text-success ms-auto"></i>
</div>
<div class="task-list d-flex flex-column gap-2 overflow-y-auto px-1" style="max-height: 70vh;">
<div class="task-card p-3 rounded-3 bg-white border" data-task-name="إنشاء قاعدة البيانات">
<div class="d-flex justify-content-between align-items-center mb-1">
<h4 class="task-name m-0" style="font-size: 14px; font-weight: 600;">إنشاء قاعدة البيانات</h4>
<div class="task-actions">
<button class="btn-icon text-muted me-1 border-0 bg-transparent p-0" onclick="openEditModal(this)"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon text-muted border-0 bg-transparent p-0" onclick="openDeleteModal(this)"><i class="fa-regular fa-trash-can"></i></button>
</div>
</div>
<p class="project-name mb-2 text-muted" style="font-size: 12px;">اسم المشروع : منصة التجارة الإلكترونية</p>
<div class="d-flex justify-content-between align-items-center" style="font-size: 11px;">
<span class="end-date text-muted">تاريخ الانتهاء : 10 أغسطس 2026</span>
<div class="comments-count d-flex align-items-center gap-1 text-muted">
<i class="fa-regular fa-comment comment-icon"></i>
<span class="comment-num">5</span>
</div>
</div>
</div>
</div>
</div>
<!-- الحالة 4: متوقف مؤقتا -->
<div class="status-card-column p-3 rounded-3 bg-light" style="min-width: 300px; max-width: 320px;">
<div class="status-header d-flex align-items-center justify-content-start gap-2 mb-3">
<span class="status-title fw-bold">متوقف مؤقتا</span>
<i class="fa-regular fa-circle-stop status-icon ms-auto"></i>
</div>
<div class="task-list d-flex flex-column gap-2 overflow-y-auto px-1" style="max-height: 70vh;">
<div class="task-card p-3 rounded-3 bg-white border" data-task-name="ربط بوابات الدفع">
<div class="d-flex justify-content-between align-items-center mb-1">
<h4 class="task-name m-0" style="font-size: 14px; font-weight: 600;">ربط بوابات الدفع</h4>
<div class="task-actions">
<button class="btn-icon text-muted me-1 border-0 bg-transparent p-0" onclick="openEditModal(this)"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon text-muted border-0 bg-transparent p-0" onclick="openDeleteModal(this)"><i class="fa-regular fa-trash-can"></i></button>
</div>
</div>
<p class="project-name mb-2 text-muted" style="font-size: 12px;">اسم المشروع : تطبيق الحجوزات</p>
<div class="d-flex justify-content-between align-items-center" style="font-size: 11px;">
<span class="end-date text-muted">تاريخ الانتهاء : 01 سبتمبر 2026</span>
<div class="comments-count d-flex align-items-center gap-1 text-muted">
<i class="fa-regular fa-comment comment-icon"></i>
<span class="comment-num">1</span>
</div>
</div>
</div>
</div>
</div>
<!-- الحالة 5: قيد الانتظار -->
<div class="status-card-column p-3 rounded-3 bg-light" style="min-width: 300px; max-width: 320px;">
<div class="status-header d-flex align-items-center justify-content-start gap-2 mb-3">
<span class="status-title fw-bold">قيد الانتظار</span>
<i class="fa-solid fa-list-check status-icon ms-auto"></i>
</div>
<div class="task-card p-3 rounded-3 bg-white border" data-task-name="تطوير صفحة المشاريع">
<div class="d-flex justify-content-between align-items-center mb-1">
<h4 class="task-name m-0" style="font-size: 14px; font-weight: 600;">
<a class="text-decoration-none text-dark" href="{{ route('tasks.project-show', 2) }}">تطوير صفحة المشاريع</a>
</h4>
<div class="task-actions">
<button class="btn-icon text-muted me-1 border-0 bg-transparent p-0" onclick="openEditModal(this)"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon text-muted border-0 bg-transparent p-0" onclick="openDeleteModal(this)"><i class="fa-regular fa-trash-can"></i></button>
</div>
</div>
<p class="project-name mb-2 text-muted" style="font-size: 12px;">اسم المشروع : إعادة تصميم الموقع الإلكتروني</p>
<div class="d-flex justify-content-between align-items-center" style="font-size: 11px;">
<span class="end-date text-muted">تاريخ الانتهاء : 15 يوليو 2026</span>
<div class="comments-count d-flex align-items-center gap-1 text-muted">
<i class="fa-regular fa-comment comment-icon"></i>
<span class="comment-num">0</span>
</div>
</div>
</div>
</div>
</div>
@endsection

@push('modals')
<div aria-hidden="true" class="modal fade" id="taskModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content custom-modal p-4">
<div class="d-flex justify-content-between align-items-center mb-3">
<h3 class="modal-title m-0" id="taskModalTitle">اضافة مهمة</h3>
<button aria-label="Close" class="btn-close m-0" data-bs-dismiss="modal" type="button"></button>
</div>
<form id="taskForm" onsubmit="handleTaskSubmit(event)">
<div class="mb-3">
<label class="form-label custom-label">اسم المهمة <span class="text-danger">*</span></label>
<input class="form-control custom-input" id="taskNameInput" required="" type="text"/>
</div>
<div class="mb-3">
<label class="form-label custom-label">اسم الشركة <span class="text-danger">*</span></label>
<input class="form-control custom-input" required="" type="text"/>
</div>
<div class="mb-3">
<label class="form-label custom-label">اسم المشروع <span class="text-danger">*</span></label>
<input class="form-control custom-input" required="" type="text"/>
</div>
<div class="mb-3">
<label class="form-label custom-label">الوصف <span class="text-danger">*</span></label>
<textarea class="form-control custom-input" required="" rows="2"></textarea>
</div>
<div class="mb-3">
<label class="form-label custom-label">مسند اليه <span class="text-danger">*</span></label>
<input class="form-control custom-input" required="" type="text"/>
</div>
<!-- التواريخ -->
<div class="row g-2 mb-3">
<div class="col-6">
<label class="form-label custom-label">تاريخ البدء</label>
<input class="form-control custom-date-btn" id="startDateInput" required="" type="date"/>
</div>
<div class="col-6">
<label class="form-label custom-label">تاريخ الانتهاء </label>
<input class="form-control custom-date-btn" id="endDateInput" required="" type="date"/>
</div>
</div>
<div class="mb-4">
<label class="form-label custom-label">الحالة</label>
<select class="form-select custom-input" required="">
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

<div aria-hidden="true" class="modal fade" id="deleteModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
<div class="modal-content custom-modal text-center p-4">
<p class="delete-text mb-4" id="deleteModalText">هل تريد حذف مهمة؟</p>
<div class="d-flex justify-content-center gap-3">
<button class="btn btn-delete-confirm" onclick="confirmDelete()">حذف</button>
<button class="btn btn-delete-cancel" data-bs-dismiss="modal">إلغاء</button>
</div>
</div>
</div>
</div>

<div aria-hidden="true" class="modal fade" id="statusMessageModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
<div class="modal-content custom-modal text-center p-4">
<div class="success-icon-wrap mb-3 mx-auto">
<i class="fa-solid fa-circle-check text-success display-4"></i>
</div>
<h4 class="mb-4 fw-bold" id="statusModalMessage">تم حفظ التعديلات بنجاح</h4>
<button class="btn btn-status-ok mx-auto" data-bs-dismiss="modal">حسناً</button>
</div>
</div>
</div>
@endpush
