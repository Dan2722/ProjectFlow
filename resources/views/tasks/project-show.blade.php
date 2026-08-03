@extends('layouts.app')
@section('title', 'تفاصيل المهمة - ' . $task->task_title)
@section('content-class', 'p-4 flex-grow-1')
@section('topbar-extra-class', 'border-bottom')

@section('content')
<!-- Navigation / Breadcrumb قابل للنقر -->
<div class="mb-2 text-start">
    <a class="text-muted small text-decoration-none me-1 hover-link" href="{{ route('tasks.index') }}">المهام</a>
    <span class="text-muted small me-1">&lt;</span>
    <span class="fw-bold small text-dark">{{ $task->task_title }}</span>
</div>

<!-- عنوان الصفحة -->
<h3 class="task-page-title";">المهام</h3>

<!-- Task Details Main Header Card -->
<div class="card custom-task-card p-4 mb-4 bg-white">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div class="text-start">
            <div class="d-flex align-items-center gap-3 mb-2">
                <h4 class="fw-bold m-0 fs-5 text-dark">{{ $task->task_title }}</h4>
                <span class="text-muted small">{{ optional($task->project)->company_name ?? 'شركة غير محددة' }}</span>
            </div>
            <p class="text-muted small m-0">{{ $task->task_description ?? 'لا يوجد وصف للمهمة.' }}</p>
        </div>
        <div class="d-inline-flex align-items-center gap-2 p-0 border-0 bg-transparent flex-shrink-0">
            <span class="fw-normal text-secondary small">{{ $task->status }}</span>
            <i class="fa-regular fa-id-badge text-muted" style="font-size: 1rem;"></i>
        </div>
    </div>

    <div class="d-flex align-content-center justify-content-between text-muted small pt-2 border-top-0">
        <div class="text-start text-nowrap">
            <span>
                تاريخ البداية: {{ $task->start_task ? \Carbon\Carbon::parse($task->start_task)->locale('ar')->translatedFormat('d F Y') : 'غير محدد' }} 
                → تاريخ الانتهاء: {{ $task->end_task ? \Carbon\Carbon::parse($task->end_task)->locale('ar')->translatedFormat('d F Y') : 'غير محدد' }}
            </span>
        </div>
        <div class="text-center px-4">
            <span><strong>ُمُسند:</strong> {{ optional($task->assignedUser)->username ?? 'غير مسند' }}</span>
        </div>
        <div></div>
    </div>
</div>

<!-- Comments Box Container -->
<div class="card border border-light-subtle rounded-4 p-4 shadow-sm">
    <div class="d-flex align-items-center gap-2 mb-3">
        <i class="fa-regular fa-comment fs-4" style="color: #8A84AD;"></i>
        <h5 class="task-page-title">التعليقات</h5>
        <span class="badge rounded-circle text-dark bg-light border ms-1" id="commentsCountBadge">
            {{ isset($task->comments) ? $task->comments->count() : 0 }}
        </span>
    </div>

    <!-- Scrollable Comments Body -->
    <div class="comments-scroll-area pe-2 mb-4" id="commentsContainer" style="max-height: 380px; overflow-y: auto;">
        @forelse($task->comments ?? [] as $index => $comment)
            @php
                $commentUser = $comment->user;
                $userName = $commentUser ? ($commentUser->name ?? $commentUser->username ?? 'مستخدم') : 'مستخدم';
                $initials = mb_substr($userName, 0, 2);
                $userRole = $commentUser?->role ?? 'admin';
                $currentUserId = $commentUser?->id ?? null;
                
                $roleLabel = match($userRole) {
                    'client' => 'عميل',
                    'employee' => 'موظف',
                    default => 'مدير'
                };

                // التحقق مما إذا كان التعليق الحالي من نفس المستخدم السابق تماماً لدمجهما
                $previousComment = $index > 0 ? $task->comments[$index - 1] : null;
                $prevUser = $previousComment?->user;
                $prevUserId = $prevUser?->id ?? null;
                
                $isSameUser = false;
                if ($previousComment) {
                    if ($currentUserId && $prevUserId) {
                        $isSameUser = ($currentUserId === $prevUserId);
                    } else {
                        $prevUserName = $prevUser ? ($prevUser->name ?? $prevUser->username ?? 'مستخدم') : 'مستخدم';
                        $isSameUser = ($userName === $prevUserName);
                    }
                }
            @endphp

            @if($isSameUser)
                <!-- تعليق مدمج (نفس المستخدم ورا بعض: بدون تكرار الأفاتار أو الاسم، وبدون خط فاصل) -->
                <div class="comment-item ms-5 ps-2 mb-3">
                   <div class="text-muted extra-small mb-1">
    {{ $comment->created_at->timezone('Asia/Riyadh')->locale('ar')->translatedFormat('h:i a') }}
</div>
                    @if($comment->comment_text)
                        <p class="mb-2 text-dark small">{{ $comment->comment_text }}</p>
                    @endif

                    @if($comment->attachment)
                        @php
                            $fileName = basename($comment->attachment);
                            $extension = pathinfo($comment->attachment, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                        @endphp
                        
                        @if($isImage)
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $comment->attachment) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $comment->attachment) }}" alt="attachment" class="img-thumbnail rounded-3" style="max-height: 120px;">
                                </a>
                            </div>
                        @else
                            <div class="p-2 border rounded-3 bg-light d-inline-flex align-items-center gap-2 mt-2">
                                <i class="fa-regular fa-file text-muted"></i>
                                <a href="{{ asset('storage/' . $comment->attachment) }}" target="_blank" class="extra-small text-muted text-decoration-none">
                                    {{ $fileName }}
                                </a>
                            </div>
                        @endif
                    @endif
                </div>
            @else
                <!-- التعليق الأساسي أو تعليق لمستخدم مختلف (يظهر الخط الفاصل والأفاتار والاسم) -->
                <div class="d-flex gap-3 mb-3 comment-item {{ $index > 0 ? 'border-top pt-3' : '' }}">
                    <div class="avatar rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 42px; height: 42px; background-color: #8A84AD; flex-shrink: 0;">
                        {{ $initials }}
                    </div>
                    <div class="w-100">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="fw-bold comment-username">{{ $userName }}</span>
                            <span class="badge" style="background-color: #8A84AD;">{{ $roleLabel }}</span>
                        </div>
                        <div class="text-muted extra-small mb-2">
                            {{ $comment->created_at->locale('ar')->translatedFormat('d F Y, h:i a') }}
                        </div>
                        
                        @if($comment->comment_text)
                            <p class="mb-2 text-dark small">{{ $comment->comment_text }}</p>
                        @endif

                        @if($comment->attachment)
                            @php
                                $fileName = basename($comment->attachment);
                                $extension = pathinfo($comment->attachment, PATHINFO_EXTENSION);
                                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                            @endphp
                            
                            @if($isImage)
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $comment->attachment) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $comment->attachment) }}" alt="attachment" class="img-thumbnail rounded-3" style="max-height: 120px;">
                                    </a>
                                </div>
                            @else
                                <div class="p-2 border rounded-3 bg-light d-inline-flex align-items-center gap-2 mt-2">
                                    <i class="fa-regular fa-file text-muted"></i>
                                    <a href="{{ asset('storage/' . $comment->attachment) }}" target="_blank" class="extra-small text-muted text-decoration-none">
                                        {{ $fileName }}
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endif
        @empty
            <p class="text-muted fs-6 m-0 text-center" id="emptyStateMsg">لا توجد تعليقات بعد، اكتب أول تعليق.</p>
        @endforelse
    </div>

    <!-- Add Comment Form Area -->
    <form id="commentForm" action="{{ route('comments.store', $task->task_id) }}" method="POST" enctype="multipart/form-data" onsubmit="return handleCommentSubmit(event)">
    @csrf
    <div class="d-flex flex-wrap gap-2 mb-2" id="attachmentsPreview"></div>
    
    <input class="d-none" id="attachmentInput" name="attachment" type="file" onchange="handleFileSelect(event)"/>
    
    <div class="position-relative mb-3">
        <input class="form-control rounded-pill pe-4 ps-5 py-2 custom-input" id="commentTextInput" name="comment_text" placeholder="اكتب تعليق...... أو أرفق صور/ملفات" type="text"/>
        <button class="btn btn-link position-absolute start-0 top-50 translate-middle-y text-decoration-none border-0 p-0 ms-3" style="color: #8A84AD;" type="submit">
            <i class="fa-regular fa-paper-plane fs-5"></i>
        </button>
    </div>

    <div class="d-flex gap-2">
        <button class="btn rounded-pill text-white px-3 py-1 small" onclick="document.getElementById('attachmentInput').accept='.pdf,.doc,.docx,.zip,.fig'; document.getElementById('attachmentInput').click();" style="background-color: #8A84AD;" type="button">
            <i class="fa-solid fa-paperclip me-1"></i> تحميل ملف
        </button>
        <button class="btn rounded-pill text-white px-3 py-1 small" onclick="document.getElementById('attachmentInput').accept='image/*'; document.getElementById('attachmentInput').click();" style="background-color: #8A84AD;" type="button">
            <i class="fa-regular fa-image me-1"></i> صورة
        </button>
    </div>
</form>
</div>
@endsection