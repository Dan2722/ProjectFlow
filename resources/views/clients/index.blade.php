@extends('layouts.app')
@section('title', 'العملاء')
@section('content-class', 'p-4 flex-grow-1')

@section('content')
<!-- هيدر قسم العملاء -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="task-page-title m-0">العملاء</h2>
    <button class="btn btn-add-task d-flex align-items-center gap-2" onclick="prepareAddClientModal('{{ route('clients.store') }}')">
        <span>عميل جديد +</span>
    </button>
</div>

<!-- كارد إجمالي العملاء الموحد النظيف (مطابق للـ Figma) -->
<div class="total-clients-card mb-4">
    <div class="count-number">{{ $clients->count() }}</div>
    <div class="label-text">
        <span>اجمالي العملاء</span>
        <i class="fa-solid fa-users-rectangle card-icon"></i>
    </div>
</div>

<!-- قائمة العملاء مع الحاوية المسؤولة عن الـ Scrolling -->
<div class="clients-container-scroll px-1" style="max-height: 480px; overflow-y: auto;">
    <div class="row g-3 row-cols-1 row-cols-md-2 row-cols-lg-3" id="clientsList">
        @forelse($clients as $client)
            <!-- كارد عميل ديناميكي -->
            <div class="col client-card-wrapper" 
                 data-client-name="{{ $client->name }}"
                 data-company-name="{{ $client->company_name }}"
                 data-client-email="{{ $client->email }}"
                 data-client-phone="{{ $client->phone }}"
                 data-client-project="{{ $client->project_name }}">
                 
                <div class="client-card p-3 rounded-3 w-100 border">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h3 class="client-name m-0">{{ $client->name }}</h3>
                            <div class="company-name">{{ $client->company_name }}</div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="client-badge">عميل</span>
                            <div class="task-actions">
                                <button class="btn-icon text-muted me-1 border-0 bg-transparent p-0" onclick="openEditClientModal(this, '{{ route('clients.update', $client) }}')">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <button class="btn-icon text-muted border-0 bg-transparent p-0" onclick="openDeleteClientModal(this, '{{ route('clients.destroy', $client) }}')">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr class="my-2 text-muted"/>
                    <div class="client-details d-flex flex-column gap-1 text-muted" style="font-size: 13px;">
                        <div><i class="fa-regular fa-envelope me-2"></i><span class="client-email">{{ $client->email }}</span></div>
                        <div><i class="fa-solid fa-phone me-2"></i><span class="client-phone" dir="ltr">{{ $client->phone }}</span></div>
                        <div><i class="fa-solid fa-briefcase me-2"></i>اسم المشروع: <span class="client-project">{{ $client->project_name }}</span></div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-4">
                <p class="text-muted">لا يوجد عملاء حالياً</p>
            </div>
        @endforelse
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
            <form id="clientForm" method="POST" action="{{ route('clients.store') }}">
                @csrf
                <input type="hidden" name="_method" id="clientFormMethod" value="POST">

                <div class="mb-3">
                    <label class="form-label custom-label">اسم العميل <span class="text-danger">*</span></label>
                    <input class="form-control custom-input" id="clientNameInput" name="name" required type="text"/>
                </div>
                <div class="mb-3">
                    <label class="form-label custom-label">اسم الشركة <span class="text-danger">*</span></label>
                    <input class="form-control custom-input" id="companyNameInput" name="company_name" required type="text"/>
                </div>
                <div class="mb-3">
                    <label class="form-label custom-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                    <input class="form-control custom-input" id="clientEmailInput" name="email" required type="email"/>
                </div>
                <div class="mb-3">
                    <label class="form-label custom-label">رقم الهاتف <span class="text-danger">*</span></label>
                    <input class="form-control custom-input" id="clientPhoneInput" name="phone" pattern="^05[0-9]{8}$" required title="يرجى إدخال رقم هاتف سعودي صحيح يبدأ بـ 05 ومكون من 10 أرقام" type="tel"/>
                </div>
                <div class="mb-3">
                    <label class="form-label custom-label">اسم المشروع <span class="text-danger">*</span></label>
                    <input class="form-control custom-input" id="clientProjectInput" name="project_name" required type="text"/>
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
            <form id="deleteClientForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <p class="delete-text mb-4 fw-bold" id="deleteClientModalText"></p>
                <div class="d-flex justify-content-center gap-3">
                    <button class="btn btn-delete-confirm" type="submit">حذف</button>
                    <button class="btn btn-delete-cancel" data-bs-dismiss="modal" type="button">إلغاء</button>
                </div>
            </form>
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