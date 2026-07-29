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
<!-- مسار التنقل (Breadcrumb) -->
<nav aria-label="breadcrumb" class="mb-3">
<ol class="breadcrumb mb-0" style="font-size: 14px;">
<li class="breadcrumb-item"><a class="text-decoration-none text-muted" href="{{ route('projects.index') }}" id="breadcrumbProj">المشاريع</a></li>
<li aria-current="page" class="breadcrumb-item active fw-semibold text-dark" id="breadcrumbSub">إعادة تصميم الموقع الإلكتروني</li>
</ol>
</nav>
<!-- عنوان الصفحة -->
<h1 class="fw-bold mb-4" id="pageMainTitle" style="font-size: 26px; color: #000000;">المشاريع</h1>
<!-- كارد المشروع الرئيسي -->
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white" style="border: 1px solid #EFEEF3 !important;">
<div class="row align-items-center">
<div class="col-lg-8">
<div class="d-flex align-items-center gap-2 mb-2">
<h2 class="fw-bold m-0" id="cardProjTitle" style="font-size: 18px; color: #000000;">إعادة تصميم الموقع الإلكتروني</h2>
<span class="text-muted" id="companyName" style="font-size: 13px;">شركة النخبة العقارية</span>
</div>
<p class="text-secondary mb-3" id="projDesc" style="font-size: 13px; line-height: 1.6;">
                                تحديث الموقع الإلكتروني للشركة بهدف تحسين تجربة المستخدم، تسريع الأداء، وإبراز المشاريع العقارية بطريقة احترافية مع توافق كامل مع الجوال.
                            </p>
<div class="d-flex gap-4 text-muted" style="font-size: 12px;">
<span id="startDateText">تاريخ البداية : 10 مايو 2026</span>
<span><i class="fa-solid fa-arrow-left-long mx-1"></i> <span id="endDateText">تاريخ الانتهاء : 31 أكتوبر 2026</span></span>
</div>
</div>
<div class="col-lg-4 mt-3 mt-lg-0 text-lg-end">
<div class="d-flex align-items-center justify-content-lg-end gap-2 mb-2">
<div class="d-flex align-items-center gap-2" style="color: #000000; font-size: 14px; font-weight: 400;">
<span id="statusInProgress">قيد التنفيذ</span>
<i class="fa-solid fa-users-gear" style="font-size: 16px; color: #8A84AD;"></i>
</div>
</div>
<div class="mt-3">
<div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 12px;">
<span class="text-muted" id="progressLabel">نسبة الإنجاز</span>
<span class="fw-bold" style="color: #8A84AD;">30%</span>
</div>
<div class="progress" style="height: 6px; background-color: #EFEEF3;">
<div aria-valuemax="100" aria-valuemin="0" aria-valuenow="30" class="progress-bar rounded-pill" role="progressbar" style="width: 30%; background-color: #8A84AD;"></div>
</div>
</div>
</div>
</div>
</div>
<!-- شبكة كاردات الحالات (3 أعمدة في كل صف باستخدام row-cols-lg-3 و g-4) -->
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
<!-- كارد حالة 1: قيد التنفيذ -->
<div class="col">
<div class="card border rounded-4 p-3 bg-white shadow-sm d-flex flex-column" style="border-color: #EFEEF3 !important; height: 440px;">
<div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
<div class="d-flex align-items-center gap-2">
<span class="fw-bold" style="font-size: 15px; color: #000000;">قيد التنفيذ</span>
<i class="fa-solid fa-users-gear" style="color: #8A84AD; font-size: 15px;"></i>
</div>
<span class="badge rounded-pill bg-light text-secondary px-2 py-1" style="font-size: 11px;">1 مهمة</span>
</div>
<div class="flex-grow-1 overflow-auto pe-1" style="max-height: 350px;">
<div class="d-flex flex-column gap-3">
<div class="card border rounded-3 p-3 bg-white task-card d-flex flex-column justify-content-between shadow-xs" style="border-color: #EFEEF3 !important;">
<div><span class="fw-bold task-name" style="font-size: 14px; color: #000000;">تطوير لوحة التحكم الرئيسية</span></div>
<div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top">
<div class="text-muted end-date" style="font-size: 11px;">20 سبتمبر 2026</div>
<div class="task-actions d-flex align-items-center gap-2" style="font-size: 14px;">
<button class="btn-icon border-0 bg-transparent p-0" onclick="openEditModal(this)" style="color: #8A84AD;"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon border-0 bg-transparent p-0" onclick="openDeleteModal(this)" style="color: #8A84AD;"><i class="fa-regular fa-trash-can"></i></button>
<div class="d-flex align-items-center gap-1" style="color: #8A84AD;"><i class="fa-regular fa-comment"></i><span style="font-size: 12px;">2</span></div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- كارد حالة 2: قيد المراجعة (5 مهام) -->
<div class="col">
<div class="card border rounded-4 p-3 bg-white shadow-sm d-flex flex-column" style="border-color: #EFEEF3 !important; height: 440px;">
<div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
<div class="d-flex align-items-center gap-2">
<span class="fw-bold" style="font-size: 15px; color: #000000;">قيد المراجعة</span>
<i class="fa-regular fa-clipboard" style="color: #8A84AD; font-size: 15px;"></i>
</div>
<span class="badge rounded-pill bg-light text-secondary px-2 py-1" style="font-size: 11px;">5 مهام</span>
</div>
<div class="flex-grow-1 overflow-auto pe-1" style="max-height: 350px;">
<div class="d-flex flex-column gap-3">
<div class="card border rounded-3 p-3 bg-white task-card d-flex flex-column justify-content-between shadow-xs" style="border-color: #EFEEF3 !important;">
<div><span class="fw-bold task-name" style="font-size: 14px; color: #000000;">اختبار الموقع والتوافق</span></div>
<div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top">
<div class="text-muted end-date" style="font-size: 11px;">30 أغسطس 2026</div>
<div class="task-actions d-flex align-items-center gap-2" style="font-size: 14px;">
<button class="btn-icon border-0 bg-transparent p-0" onclick="openEditModal(this)" style="color: #8A84AD;"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon border-0 bg-transparent p-0" onclick="openDeleteModal(this)" style="color: #8A84AD;"><i class="fa-regular fa-trash-can"></i></button>
<div class="d-flex align-items-center gap-1" style="color: #8A84AD;"><i class="fa-regular fa-comment"></i><span style="font-size: 12px;">1</span></div>
</div>
</div>
</div>
<div class="card border rounded-3 p-3 bg-white task-card d-flex flex-column justify-content-between shadow-xs" style="border-color: #EFEEF3 !important;">
<div><span class="fw-bold task-name" style="font-size: 14px; color: #000000;">مراجعة أكواد الواجهات الأمامية</span></div>
<div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top">
<div class="text-muted end-date" style="font-size: 11px;">02 سبتمبر 2026</div>
<div class="task-actions d-flex align-items-center gap-2" style="font-size: 14px;">
<button class="btn-icon border-0 bg-transparent p-0" onclick="openEditModal(this)" style="color: #8A84AD;"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon border-0 bg-transparent p-0" onclick="openDeleteModal(this)" style="color: #8A84AD;"><i class="fa-regular fa-trash-can"></i></button>
<div class="d-flex align-items-center gap-1" style="color: #8A84AD;"><i class="fa-regular fa-comment"></i><span style="font-size: 12px;">2</span></div>
</div>
</div>
</div>
<div class="card border rounded-3 p-3 bg-white task-card d-flex flex-column justify-content-between shadow-xs" style="border-color: #EFEEF3 !important;">
<div><span class="fw-bold task-name" style="font-size: 14px; color: #000000;">تدقيق النصوص والترجمة</span></div>
<div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top">
<div class="text-muted end-date" style="font-size: 11px;">05 سبتمبر 2026</div>
<div class="task-actions d-flex align-items-center gap-2" style="font-size: 14px;">
<button class="btn-icon border-0 bg-transparent p-0" onclick="openEditModal(this)" style="color: #8A84AD;"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon border-0 bg-transparent p-0" onclick="openDeleteModal(this)" style="color: #8A84AD;"><i class="fa-regular fa-trash-can"></i></button>
<div class="d-flex align-items-center gap-1" style="color: #8A84AD;"><i class="fa-regular fa-comment"></i><span style="font-size: 12px;">0</span></div>
</div>
</div>
</div>
<div class="card border rounded-3 p-3 bg-white task-card d-flex flex-column justify-content-between shadow-xs" style="border-color: #EFEEF3 !important;">
<div><span class="fw-bold task-name" style="font-size: 14px; color: #000000;">فحص سرعة استجابة الخادم</span></div>
<div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top">
<div class="text-muted end-date" style="font-size: 11px;">10 سبتمبر 2026</div>
<div class="task-actions d-flex align-items-center gap-2" style="font-size: 14px;">
<button class="btn-icon border-0 bg-transparent p-0" onclick="openEditModal(this)" style="color: #8A84AD;"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon border-0 bg-transparent p-0" onclick="openDeleteModal(this)" style="color: #8A84AD;"><i class="fa-regular fa-trash-can"></i></button>
<div class="d-flex align-items-center gap-1" style="color: #8A84AD;"><i class="fa-regular fa-comment"></i><span style="font-size: 12px;">4</span></div>
</div>
</div>
</div>
<div class="card border rounded-3 p-3 bg-white task-card d-flex flex-column justify-content-between shadow-xs" style="border-color: #EFEEF3 !important;">
<div><span class="fw-bold task-name" style="font-size: 14px; color: #000000;">اعتماد التصميم النهائي من العميل</span></div>
<div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top">
<div class="text-muted end-date" style="font-size: 11px;">15 سبتمبر 2026</div>
<div class="task-actions d-flex align-items-center gap-2" style="font-size: 14px;">
<button class="btn-icon border-0 bg-transparent p-0" onclick="openEditModal(this)" style="color: #8A84AD;"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon border-0 bg-transparent p-0" onclick="openDeleteModal(this)" style="color: #8A84AD;"><i class="fa-regular fa-trash-can"></i></button>
<div class="d-flex align-items-center gap-1" style="color: #8A84AD;"><i class="fa-regular fa-comment"></i><span style="font-size: 12px;">1</span></div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- كارد حالة 3: متوقف مؤقتاً -->
<div class="col">
<div class="card border rounded-4 p-3 bg-white shadow-sm d-flex flex-column" style="border-color: #EFEEF3 !important; height: 440px;">
<div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
<div class="d-flex align-items-center gap-2">
<span class="fw-bold" style="font-size: 15px; color: #000000;">متوقف مؤقتاً</span>
<i class="fa-regular fa-circle-stop" style="color: #8A84AD; font-size: 15px;"></i>
</div>
<span class="badge rounded-pill bg-light text-secondary px-2 py-1" style="font-size: 11px;">1 مهمة</span>
</div>
<div class="flex-grow-1 overflow-auto pe-1" style="max-height: 350px;">
<div class="d-flex flex-column gap-3">
<div class="card border rounded-3 p-3 bg-white task-card d-flex flex-column justify-content-between shadow-xs" style="border-color: #EFEEF3 !important;">
<div><span class="fw-bold task-name" style="font-size: 14px; color: #000000;">تطوير صفحة المشاريع</span></div>
<div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top">
<div class="text-muted end-date" style="font-size: 11px;">10 أغسطس 2026</div>
<div class="task-actions d-flex align-items-center gap-2" style="font-size: 14px;">
<button class="btn-icon border-0 bg-transparent p-0" onclick="openEditModal(this)" style="color: #8A84AD;"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon border-0 bg-transparent p-0" onclick="openDeleteModal(this)" style="color: #8A84AD;"><i class="fa-regular fa-trash-can"></i></button>
<div class="d-flex align-items-center gap-1" style="color: #8A84AD;"><i class="fa-regular fa-comment"></i><span style="font-size: 12px;">0</span></div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- كارد حالة 4: قيد الانتظار -->
<div class="col">
<div class="card border rounded-4 p-3 bg-white shadow-sm d-flex flex-column" style="border-color: #EFEEF3 !important; height: 440px;">
<div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
<div class="d-flex align-items-center gap-2">
<span class="fw-bold" style="font-size: 15px; color: #000000;">قيد الانتظار</span>
<i class="fa-solid fa-bars-staggered" style="color: #8A84AD; font-size: 15px;"></i>
</div>
<span class="badge rounded-pill bg-light text-secondary px-2 py-1" style="font-size: 11px;">1 مهمة</span>
</div>
<div class="flex-grow-1 overflow-auto pe-1" style="max-height: 350px;">
<div class="d-flex flex-column gap-3">
<div class="card border rounded-3 p-3 bg-white task-card d-flex flex-column justify-content-between shadow-xs" style="border-color: #EFEEF3 !important;">
<div><span class="fw-bold task-name" style="font-size: 14px; color: #000000;">تصميم الصفحة الرئيسية</span></div>
<div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top">
<div class="text-muted end-date" style="font-size: 11px;">15 يوليو 2026</div>
<div class="task-actions d-flex align-items-center gap-2" style="font-size: 14px;">
<button class="btn-icon border-0 bg-transparent p-0" onclick="openEditModal(this)" style="color: #8A84AD;"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon border-0 bg-transparent p-0" onclick="openDeleteModal(this)" style="color: #8A84AD;"><i class="fa-regular fa-trash-can"></i></button>
<div class="d-flex align-items-center gap-1" style="color: #8A84AD;"><i class="fa-regular fa-comment"></i><span style="font-size: 12px;">3</span></div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- كارد حالة 5: مكتملة -->
<div class="col">
<div class="card border rounded-4 p-3 bg-white shadow-sm d-flex flex-column" style="border-color: #EFEEF3 !important; height: 440px;">
<div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
<div class="d-flex align-items-center gap-2">
<span class="fw-bold" style="font-size: 15px; color: #000000;">مكتملة</span>
<i class="fa-regular fa-circle-check" style="color: #8A84AD; font-size: 15px;"></i>
</div>
<span class="badge rounded-pill bg-light text-secondary px-2 py-1" style="font-size: 11px;">1 مهمة</span>
</div>
<div class="flex-grow-1 overflow-auto pe-1" style="max-height: 350px;">
<div class="d-flex flex-column gap-3">
<div class="card border rounded-3 p-3 bg-white task-card d-flex flex-column justify-content-between shadow-xs" style="border-color: #EFEEF3 !important;">
<div><span class="fw-bold task-name" style="font-size: 14px; color: #000000;">إعداد هيكل قاعدة البيانات</span></div>
<div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top">
<div class="text-muted end-date" style="font-size: 11px;">01 يوليو 2026</div>
<div class="task-actions d-flex align-items-center gap-2" style="font-size: 14px;">
<button class="btn-icon border-0 bg-transparent p-0" onclick="openEditModal(this)" style="color: #8A84AD;"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon border-0 bg-transparent p-0" onclick="openDeleteModal(this)" style="color: #8A84AD;"><i class="fa-regular fa-trash-can"></i></button>
<div class="d-flex align-items-center gap-1" style="color: #8A84AD;"><i class="fa-regular fa-comment"></i><span style="font-size: 12px;">5</span></div>
</div>
</div>
</div>
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
