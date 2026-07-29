@extends('layouts.app')
@section('title', 'تفاصيل المهمة - تصميم الصفحة الرئيسية')
@section('content-class', 'p-4 flex-grow-1')
@section('topbar-extra-class', 'border-bottom')

@section('content')
<!-- Navigation / Breadcrumb قابل للنقر -->
<div class="mb-2 text-start">
<a class="text-muted small text-decoration-none me-1 hover-link" href="{{ route('tasks.index') }}">المهام</a>
<span class="text-muted small me-1">&lt;</span>
<span class="fw-bold small text-dark">تصميم الصفحة الرئيسية</span>
</div>
<!-- عنوان الصفحة بتنسيق ناعم ومصغر (مثل الصفحات الأخرى) -->
<h3 class="fw-bold mb-4 text-start fs-5" style="color: #2b2b2b;">المهام</h3>
<!-- Task Details Main Header Card -->
<div class="card custom-task-card p-4 mb-4 bg-white">
<!-- السطر الأول: العنوان والوصف في اليمين | وقيد التنفيذ في أقصى اليسار -->
<div class="d-flex justify-content-between align-items-start mb-4">
<!-- الجانب الأيمن: العنوان والشركة والوصف -->
<div class="text-start">
<div class="d-flex align-items-center gap-3 mb-2">
<h4 class="fw-bold m-0 fs-5 text-dark">تصميم الصفحة الرئيسية</h4>
<span class="text-muted small">شركة النخبة العقارية</span>
</div>
<p class="text-muted small m-0">إعداد تصميم حديث للواجهة الرئيسية يتضمن عرض المشاريع والخدمات وإحصائيات الشركة.</p>
</div>
<!-- أقصى اليسار: قيد التنفيذ أولاً ثم الأيقونة -->
<div class="d-inline-flex align-items-center gap-2 p-0 border-0 bg-transparent flex-shrink-0">
<span class="fw-normal text-secondary small">قيد التنفيذ</span>
<i class="fa-regular fa-id-badge text-muted" style="font-size: 1rem;"></i>
</div>
</div>
<!-- السطر الثاني: التوزيع الأفقي المتناسق للأسفل -->
<div class="d-flex align-content-center justify-content-between text-muted small pt-2 border-top-0">
<!-- التواريخ مفرودة على سطر واحد وفي أقصى اليسار -->
<div class="text-start text-nowrap">
<span>تاريخ الانتهاء: 15 يوليو 2026 → تاريخ البداية: 01 يوليو 2026</span>
</div>
<!-- مسند: دان سلام بعيدة وفي المنتصف/اليمين بقليل لتترك مساحة واسعة للتواريخ -->
<div class="text-center px-4">
<span><strong>ُمسند:</strong> دان سلام</span>
</div>
<!-- عنصر فارغ للتوازن -->
<div></div>
</div>
</div>
<!-- Comments Box Container -->
<div class="card border border-light-subtle rounded-4 p-4 shadow-sm">
<!-- Title & Count -->
<div class="d-flex align-items-center gap-2 mb-3">
<i class="fa-regular fa-comment fs-4" style="color: #8A84AD;"></i>
<h5 class="fw-bold m-0">التعليقات</h5>
<span class="badge rounded-circle text-dark bg-light border ms-1" id="commentsCountBadge">3</span>
</div>
<!-- Scrollable Comments Body -->
<div class="comments-scroll-area pe-2 mb-4" id="commentsContainer" style="max-height: 380px; overflow-y: auto;">
<!-- Comment 1 -->
<div class="d-flex gap-3 mb-3 border-bottom pb-3">
<div class="avatar rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 42px; height: 42px; background-color: #8A84AD; flex-shrink: 0;">سج</div>
<div class="w-100">
<div class="d-flex align-items-center gap-2 mb-1">
<span class="fw-bold">سارة جميل</span>
<span class="badge" style="background-color: #8A84AD;">عميل</span>
</div>
<div class="text-muted extra-small mb-2">03 يوليو 2026, 09:20 ص</div>
<p class="mb-0 text-dark small">نحتاج أن يكون قسم المشاريع أوضح ويظهر مباشرة عند الدخول للموقع.</p>
</div>
</div>
<!-- Comment 2 -->
<div class="d-flex gap-3 mb-3 border-bottom pb-3">
<div class="avatar rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 42px; height: 42px; background-color: #8A84AD; flex-shrink: 0;">دس</div>
<div class="w-100">
<div class="d-flex align-items-center gap-2 mb-1">
<span class="fw-bold">دان سلام</span>
<span class="badge" style="background-color: #8A84AD;">مستخدم</span>
</div>
<div class="text-muted extra-small mb-2">08 يوليو 2026, 02:45 م</div>
<p class="mb-2 text-dark small">تم تعديل ترتيب الأقسام وإبراز المشاريع في أول الصفحة، يرجى مراجعة النسخة الجديدة.</p>
<div class="p-2 border rounded-3 bg-light d-inline-flex align-items-center gap-2">
<i class="fa-regular fa-file text-muted"></i>
<span class="extra-small text-muted">Project-section.v2.fig (2.4 MB)</span>
</div>
</div>
</div>
<!-- Comment 3 -->
<div class="d-flex gap-3 mb-3">
<div class="avatar rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 42px; height: 42px; background-color: #8A84AD; flex-shrink: 0;">سج</div>
<div class="w-100">
<div class="d-flex align-items-center gap-2 mb-1">
<span class="fw-bold">سارة جميل</span>
<span class="badge" style="background-color: #8A84AD;">عميل</span>
</div>
<div class="text-muted extra-small mb-2">13 يوليو 2026, 11:10 ص</div>
<p class="mb-0 text-dark small">ممتاز، التصميم الحالي مناسب ويمكن الاعتماد عليه.</p>
</div>
</div>
</div>
<!-- Add Comment Form Area -->
<form id="commentForm" onsubmit="handleCommentSubmit(event)">
<!-- Attachments preview bar -->
<div class="d-flex flex-wrap gap-2 mb-2" id="attachmentsPreview"></div>
<!-- Hidden Inputs for Attachments -->
<input accept=".pdf,.doc,.docx,.zip,.fig" class="d-none" id="fileInput" multiple="" onchange="handleFileSelect(event)" type="file"/>
<input accept="image/*" class="d-none" id="imageInput" multiple="" onchange="handleImageSelect(event)" type="file"/>
<!-- Comment Text Area Input -->
<div class="position-relative mb-3">
<input class="form-control rounded-pill pe-4 ps-5 py-2 custom-input" id="commentTextInput" placeholder="اكتب تعليق......" type="text"/>
<button class="btn btn-link position-absolute start-0 top-50 translate-middle-y text-decoration-none border-0 p-0 ms-3" style="color: #8A84AD;" type="submit">
<i class="fa-regular fa-paper-plane fs-5"></i>
</button>
</div>
<!-- Action Buttons (Attach File & Photo) -->
<div class="d-flex gap-2">
<button class="btn rounded-pill text-white px-3 py-1 small" onclick="document.getElementById('fileInput').click()" style="background-color: #8A84AD;" type="button">
<i class="fa-solid fa-paperclip me-1"></i> تحميل ملف
                            </button>
<button class="btn rounded-pill text-white px-3 py-1 small" onclick="document.getElementById('imageInput').click()" style="background-color: #8A84AD;" type="button">
<i class="fa-regular fa-image me-1"></i> صورة
                            </button>
</div>
</form>
</div>
@endsection

@push('modals')
<div aria-hidden="true" class="modal fade" id="statusMessageModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
<div class="modal-content custom-modal text-center p-4">
<div class="success-icon-wrap mb-3 mx-auto">
<i class="fa-solid fa-circle-check text-success display-4"></i>
</div>
<h4 class="mb-4 fw-bold" id="statusModalMessage">تم إضافة التعليق بنجاح</h4>
<button class="btn btn-status-ok mx-auto" data-bs-dismiss="modal">حسناً</button>
</div>
</div>
</div>
@endpush
