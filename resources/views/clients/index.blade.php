@extends('layouts.app')
@section('title', 'العملاء')
@section('content-class', 'p-4 flex-grow-1')

@section('content')
<!-- هيدر قسم العملاء -->
<div class="d-flex justify-content-between align-items-center mb-4">
<h2 class="task-page-title m-0">العملاء</h2>
<button class="btn btn-add-task d-flex align-items-center gap-2" onclick="prepareAddClientModal()">
<span>عميل جديد +</span>
</button>
</div>
<!-- كارد إجمالي العملاء الموحد النظيف (مطابق للـ Figma) -->
<div class="total-clients-card mb-4">
<div class="count-number">7</div>
<div class="label-text">
<span>اجمالي العملاء</span>
<i class="fa-solid fa-users-rectangle card-icon"></i>
</div>
</div>
<!-- قائمة العملاء مع الحاوية المسؤولة عن الـ Scrolling -->
<div class="clients-container-scroll px-1" style="max-height: 480px; overflow-y: auto;">
<div class="row g-3 row-cols-1 row-cols-md-2 row-cols-lg-3" id="clientsList">
<!-- كارد عميل 1 -->
<div class="col client-card-wrapper">
<div class="client-card p-3 rounded-3 w-100 border">
<div class="d-flex justify-content-between align-items-start mb-2">
<div>
<h3 class="client-name m-0">سارة أحمد</h3>
<div class="company-name">شركة الحلول المبتكرة</div>
</div>
<div class="d-flex align-items-center gap-2">
<span class="client-badge">عميل</span>
<div class="task-actions">
<button class="btn-icon text-muted me-1 border-0 bg-transparent p-0" onclick="openEditClientModal(this)"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon text-muted border-0 bg-transparent p-0" onclick="openDeleteClientModal(this)"><i class="fa-regular fa-trash-can"></i></button>
</div>
</div>
</div>
<hr class="my-2 text-muted"/>
<div class="client-details d-flex flex-column gap-1 text-muted" style="font-size: 13px;">
<div><i class="fa-regular fa-envelope me-2"></i><span class="client-email">sara@innovative.com</span></div>
<div><i class="fa-solid fa-phone me-2"></i><span class="client-phone" dir="ltr">0512345678</span></div>
<div><i class="fa-solid fa-briefcase me-2"></i>اسم المشروع: <span class="client-project">تطوير تطبيق إدارة طلبات الصيانة</span></div>
</div>
</div>
</div>
<!-- كارد عميل 2 -->
<div class="col client-card-wrapper">
<div class="client-card p-3 rounded-3 w-100 border">
<div class="d-flex justify-content-between align-items-start mb-2">
<div>
<h3 class="client-name m-0">محمد القحطاني</h3>
<div class="company-name">مؤسسة التقنية الرقمية</div>
</div>
<div class="d-flex align-items-center gap-2">
<span class="client-badge">عميل</span>
<div class="task-actions">
<button class="btn-icon text-muted me-1 border-0 bg-transparent p-0" onclick="openEditClientModal(this)"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon text-muted border-0 bg-transparent p-0" onclick="openDeleteClientModal(this)"><i class="fa-regular fa-trash-can"></i></button>
</div>
</div>
</div>
<hr class="my-2 text-muted"/>
<div class="client-details d-flex flex-column gap-1 text-muted" style="font-size: 13px;">
<div><i class="fa-regular fa-envelope me-2"></i><span class="client-email">mohammed@digitaltech.sa</span></div>
<div><i class="fa-solid fa-phone me-2"></i><span class="client-phone" dir="ltr">0551122334</span></div>
<div><i class="fa-solid fa-briefcase me-2"></i>اسم المشروع: <span class="client-project">نصمة إدارة الموارد البشرية</span></div>
</div>
</div>
</div>
<!-- كارد عميل 3 -->
<div class="col client-card-wrapper">
<div class="client-card p-3 rounded-3 w-100 border">
<div class="d-flex justify-content-between align-items-start mb-2">
<div>
<h3 class="client-name m-0">ريم العتيبي</h3>
<div class="company-name">شركة السحاب الذكي</div>
</div>
<div class="d-flex align-items-center gap-2">
<span class="client-badge">عميل</span>
<div class="task-actions">
<button class="btn-icon text-muted me-1 border-0 bg-transparent p-0" onclick="openEditClientModal(this)"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon text-muted border-0 bg-transparent p-0" onclick="openDeleteClientModal(this)"><i class="fa-regular fa-trash-can"></i></button>
</div>
</div>
</div>
<hr class="my-2 text-muted"/>
<div class="client-details d-flex flex-column gap-1 text-muted" style="font-size: 13px;">
<div><i class="fa-regular fa-envelope me-2"></i><span class="client-email">reem@smartcloud.sa</span></div>
<div><i class="fa-solid fa-phone me-2"></i><span class="client-phone" dir="ltr">0567788990</span></div>
<div><i class="fa-solid fa-briefcase me-2"></i>اسم المشروع: <span class="client-project">بوابة الخدمات السحابية</span></div>
</div>
</div>
</div>
<!-- كارد عميل 4 -->
<div class="col client-card-wrapper">
<div class="client-card p-3 rounded-3 w-100 border">
<div class="d-flex justify-content-between align-items-start mb-2">
<div>
<h3 class="client-name m-0">عبدالله الشهري</h3>
<div class="company-name">مجموعة المستقبل التجارية</div>
</div>
<div class="d-flex align-items-center gap-2">
<span class="client-badge">عميل</span>
<div class="task-actions">
<button class="btn-icon text-muted me-1 border-0 bg-transparent p-0" onclick="openEditClientModal(this)"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon text-muted border-0 bg-transparent p-0" onclick="openDeleteClientModal(this)"><i class="fa-regular fa-trash-can"></i></button>
</div>
</div>
</div>
<hr class="my-2 text-muted"/>
<div class="client-details d-flex flex-column gap-1 text-muted" style="font-size: 13px;">
<div><i class="fa-regular fa-envelope me-2"></i><span class="client-email">abdullah@futuregroup.sa</span></div>
<div><i class="fa-solid fa-phone me-2"></i><span class="client-phone" dir="ltr">0509988776</span></div>
<div><i class="fa-solid fa-briefcase me-2"></i>اسم المشروع: <span class="client-project">متجر إلكتروني متكامل</span></div>
</div>
</div>
</div>
<!-- كارد عميل 5 -->
<div class="col client-card-wrapper">
<div class="client-card p-3 rounded-3 w-100 border">
<div class="d-flex justify-content-between align-items-start mb-2">
<div>
<h3 class="client-name m-0">فاطمة الحربي</h3>
<div class="company-name">وكالة الإبداع الإعلامي</div>
</div>
<div class="d-flex align-items-center gap-2">
<span class="client-badge">عميل</span>
<div class="task-actions">
<button class="btn-icon text-muted me-1 border-0 bg-transparent p-0" onclick="openEditClientModal(this)"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon text-muted border-0 bg-transparent p-0" onclick="openDeleteClientModal(this)"><i class="fa-regular fa-trash-can"></i></button>
</div>
</div>
</div>
<hr class="my-2 text-muted"/>
<div class="client-details d-flex flex-column gap-1 text-muted" style="font-size: 13px;">
<div><i class="fa-regular fa-envelope me-2"></i><span class="client-email">fatima@creativemedia.sa</span></div>
<div><i class="fa-solid fa-phone me-2"></i><span class="client-phone" dir="ltr">0543322110</span></div>
<div><i class="fa-solid fa-briefcase me-2"></i>اسم المشروع: <span class="client-project">منصة إدارة الحملات التسويقية</span></div>
</div>
</div>
</div>
<!-- كارد عميل 6 -->
<div class="col client-card-wrapper">
<div class="client-card p-3 rounded-3 w-100 border">
<div class="d-flex justify-content-between align-items-start mb-2">
<div>
<h3 class="client-name m-0">خالد الدوسري</h3>
<div class="company-name">شركة الرواد للبرمجيات</div>
</div>
<div class="d-flex align-items-center gap-2">
<span class="client-badge">عميل</span>
<div class="task-actions">
<button class="btn-icon text-muted me-1 border-0 bg-transparent p-0" onclick="openEditClientModal(this)"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon text-muted border-0 bg-transparent p-0" onclick="openDeleteClientModal(this)"><i class="fa-regular fa-trash-can"></i></button>
</div>
</div>
</div>
<hr class="my-2 text-muted"/>
<div class="client-details d-flex flex-column gap-1 text-muted" style="font-size: 13px;">
<div><i class="fa-regular fa-envelope me-2"></i><span class="client-email">khaled@pioneersoft.sa</span></div>
<div><i class="fa-solid fa-phone me-2"></i><span class="client-phone" dir="ltr">0534455667</span></div>
<div><i class="fa-solid fa-briefcase me-2"></i>اسم المشروع: <span class="client-project">تطوير واجهات برمجية API</span></div>
</div>
</div>
</div>
<!-- كارد عميل 7 -->
<div class="col client-card-wrapper">
<div class="client-card p-3 rounded-3 w-100 border">
<div class="d-flex justify-content-between align-items-start mb-2">
<div>
<h3 class="client-name m-0">منال الغامدي</h3>
<div class="company-name">مؤسسة الحلول الذكية</div>
</div>
<div class="d-flex align-items-center gap-2">
<span class="client-badge">عميل</span>
<div class="task-actions">
<button class="btn-icon text-muted me-1 border-0 bg-transparent p-0" onclick="openEditClientModal(this)"><i class="fa-regular fa-pen-to-square"></i></button>
<button class="btn-icon text-muted border-0 bg-transparent p-0" onclick="openDeleteClientModal(this)"><i class="fa-regular fa-trash-can"></i></button>
</div>
</div>
</div>
<hr class="my-2 text-muted"/>
<div class="client-details d-flex flex-column gap-1 text-muted" style="font-size: 13px;">
<div><i class="fa-regular fa-envelope me-2"></i><span class="client-email">manal@smartsolution.sa</span></div>
<div><i class="fa-solid fa-phone me-2"></i><span class="client-phone" dir="ltr">0581122334</span></div>
<div><i class="fa-solid fa-briefcase me-2"></i>اسم المشروع: <span class="client-project">لوحة تحكم تحليل البيانات</span></div>
</div>
</div>
</div>
</div>
</div>
@endsection

@push('modals')
<div aria-hidden="true" class="modal fade" id="clientModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content custom-modal p-4">
<div class="d-flex justify-content-between align-items-center mb-3">
<h3 class="modal-title m-0 fw-semibold" id="clientModalTitle">إضافة عميل</h3>
<button aria-label="Close" class="btn-close m-0" data-bs-dismiss="modal" type="button"></button>
</div>
<form id="clientForm" onsubmit="handleClientSubmit(event)">
<div class="mb-3">
<label class="form-label custom-label">اسم العميل <span class="text-danger">*</span></label>
<input class="form-control custom-input" id="clientNameInput" required="" type="text"/>
</div>
<div class="mb-3">
<label class="form-label custom-label">اسم الشركة <span class="text-danger">*</span></label>
<input class="form-control custom-input" id="companyNameInput" required="" type="text"/>
</div>
<div class="mb-3">
<label class="form-label custom-label">البريد الإلكتروني <span class="text-danger">*</span></label>
<input class="form-control custom-input" id="clientEmailInput" required="" type="email"/>
</div>
<div class="mb-3">
<label class="form-label custom-label">رقم الهاتف <span class="text-danger">*</span></label>
<input class="form-control custom-input" id="clientPhoneInput" pattern="^05[0-9]{8}$" required="" title="يرجى إدخال رقم هاتف سعودي صحيح يبدأ بـ 05 ومكون من 10 أرقام" type="tel"/>
</div>
<div class="mb-3">
<label class="form-label custom-label">اسم المشروع <span class="text-danger">*</span></label>
<input class="form-control custom-input" id="clientProjectInput" required="" type="text"/>
</div>
<div class="d-flex justify-content-center mt-4">
<button class="btn btn-save" type="submit">حفظ</button>
</div>
</form>
</div>
</div>
</div>

<div aria-hidden="true" class="modal fade" id="deleteClientModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content custom-modal text-center p-4">
<p class="delete-text mb-4 fw-bold" id="deleteClientModalText"></p>
<div class="d-flex justify-content-center gap-3">
<button class="btn btn-delete-confirm" onclick="confirmDeleteClient()" type="button">حذف</button>
<button class="btn btn-delete-cancel" data-bs-dismiss="modal" type="button">إلغاء</button>
</div>
</div>
</div>
</div>

<div aria-hidden="true" class="modal fade" id="statusMessageModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content custom-modal text-center p-4">
<div class="mb-3">
<i class="fa-solid fa-circle-check status-success-icon"></i>
</div>
<p class="delete-text mb-4 fw-bold" id="statusModalMessage">تمت العملية بنجاح</p>
<div class="d-flex justify-content-center">
<button class="btn btn-status-ok" data-bs-dismiss="modal" type="button">حسناً</button>
</div>
</div>
</div>
</div>
@endpush
