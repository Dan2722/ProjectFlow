@extends('layouts.app')
@section('title', 'تفاصيل المهمة - تطوير صفحة المشاريع')
@section('content-class', 'p-4 flex-grow-1')

@section('content')
<!-- Navigation / Breadcrumb قابل للنقر -->
<div class="mb-2 text-start">
<a class="text-muted small text-decoration-none me-1 hover-link" href="{{ route('tasks.index') }}">المهام</a>
<span class="text-muted small me-1">&lt;</span>
<span class="fw-bold small text-dark">تطوير صفحة المشاريع</span>
</div>
<!-- عنوان الصفحة بتنسيق ناعم ومصغر -->
<h3 class="fw-bold mb-4 text-start fs-5" style="color: #2b2b2b;">المهام</h3>
<!-- Task Details Main Header Card -->
<div class="card custom-task-card p-4 mb-4 bg-white">
<!-- السطر الأول: العنوان والوصف في اليمين | وقيد التنفيذ في أقصى اليسار -->
<div class="d-flex justify-content-between align-items-start mb-4">
<!-- الجانب الأيمن: العنوان والشركة والوصف -->
<div class="text-start">
<div class="d-flex align-items-center gap-3 mb-2">
<h4 class="fw-bold m-0 fs-5 text-dark">تطوير صفحة المشاريع</h4>
<span class="text-muted small">شركة النخبة العقارية</span>
</div>
<p class="text-muted small m-0">برمجة صفحة تعرض جميع المشاريع مع إمكانية التصفية والبحث.</p>
</div>
<!-- أقصى اليسار: حالة المهمة والأيقونة -->
<div class="d-inline-flex align-items-center gap-2 p-0 border-0 bg-transparent flex-shrink-0">
<span class="fw-normal text-secondary small">قيدالانتظار</span>
<i class="fa-solid fa-list-check text-muted" style="font-size: 1rem;"></i>
</div>
</div>
<!-- السطر الثاني: التوزيع الأفقي المتناسق للأسفل -->
<div class="d-flex align-content-center justify-content-between text-muted small pt-2 border-top-0">
<!-- التواريخ مفرودة على سطر واحد وفي أقصى اليسار -->
<div class="text-start text-nowrap">
<span>تاريخ الانتهاء: 10 أغسطس 2026 → تاريخ البداية: 16 يوليو 2026</span>
</div>
<!-- مسند: دان سلام -->
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
<span class="badge rounded-circle text-dark bg-light border ms-1" id="commentsCountBadge">0</span>
</div>
<!-- Scrollable Comments Body -->
<div class="comments-scroll-area text-start py-3" id="commentsContainer">
<p class="text-muted fs-6 m-0 text-center" id="emptyStateMsg">لا توجد تعليقات بعد، اكتب أول تعليق.</p>
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
