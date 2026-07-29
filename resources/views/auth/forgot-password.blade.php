@extends('layouts.auth')
@section('title', 'إعادة تعيين كلمة المرور - نظرة الحلول المستقبل')
@section('body-class', 'd-flex align-items-center justify-content-center min-vh-100')

@section('content')
<main class="login-wrapper text-center w-100 px-3">
<!-- شعار الشركة العلوي -->
<div class="logo-container mb-4">
<img alt="Future Vision Solution Logo" class="login-logo img-fluid" src="{{ asset('FVSLogo.jpg') }}"/>
</div>
<!-- كارد صندوق إعادة التعيين -->
<div class="login-card bg-white mx-auto p-4 p-sm-5 rounded-4 shadow-sm">
<!-- رابط العودة لتسجيل الدخول جهة اليمين -->
<div class="text-start mb-3">
<a class="forgot-password-link text-decoration-none d-inline-flex align-items-center gap-1" href="{{ route('login') }}" style="font-size: 13px;">
<!-- أيقونة السهم متجهة لليمين -->
<svg class="bi bi-chevron-right" fill="currentColor" height="14" viewbox="0 0 16 16" width="14" xmlns="http://www.w3.org/2000/svg">
<path d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" fill-rule="evenodd"></path>
</svg>
        العودة لتسجيل الدخول
    </a>
</div>
<h4 class="login-title mb-2 fs-6">إعادة تعيين كلمة المرور</h4>
<p class="login-subtitle mb-4">أدخل بريدك الإلكتروني لإرسال رابط إعادة التعيين</p>
<!-- 1. نموذج إدخال البريد الإلكتروني -->
<form id="resetForm" novalidate="">
<div class="text-start text-rtl mb-3">
<input class="form-control custom-input text-center" dir="ltr" id="emailInput" placeholder="البريد الإلكتروني" required="" type="email"/>
<!-- نص الخطأ باللون الأحمر -->
<small class="error-message text-danger d-block text-start d-none mt-1" id="emailError" style="font-size: 12px;"></small>
</div>
<!-- زر الإرسال -->
<button class="btn btn-login w-100 py-2 fw-semibold" type="submit">إرسال رابط التعيين</button>
</form>
<!-- 2. واجهة النجاح (مخفية افتراضياً وتظهر بعد الإرسال الناجح) -->
<div class="d-none text-center py-2" id="successState">
<!-- أيقونة الصح الخضراء -->
<div class="success-icon-bg mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 56px; height: 56px; background-color: #E8F5E9;">
<svg class="bi bi-check-lg" fill="#2E7D32" height="28" viewbox="0 0 16 16" width="28" xmlns="http://www.w3.org/2000/svg">
<path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"></path>
</svg>
</div>
<h4 class="fw-bold fs-6 mb-2">تم إرسال رابط إعادة التعيين إلى بريدك الإلكتروني بنجاح</h4>
<p class="login-subtitle mb-0" style="font-size: 13px; line-height: 1.6;">
                    يرجى التحقق من صندوق الوارد الخاص بك للحصول على رابط إعادة التعيين.
                </p>
</div>
</div>
</main>
@endsection
