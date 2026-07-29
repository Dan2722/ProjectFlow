<header class="Upper-navigation d-flex align-items-center justify-content-between px-4 py-3 @yield('topbar-extra-class')">
    <div class="header-right">
        <h1 class="welcome-text m-0">مرحباً {{ auth()->user()->username ?? 'دان' }}!</h1>
    </div>

    <div class="header-left d-flex gap-3 align-items-center">
        @auth
            @php
                $unreadNotifications = auth()->user()->unreadNotifications;
                $allNotifications = auth()->user()->notifications()->take(5)->get();
            @endphp

            <div class="dropdown">
                <button class="nav-icon-btn border-0 d-flex align-items-center justify-content-center position-relative"
                        type="button" id="notificationsDropdown" data-bs-toggle="dropdown"
                        aria-expanded="false" aria-label="الإشعارات">
                    <i class="fa-solid fa-bell"></i>
                    
                    {{-- إظهار النقطة الحمراء فقط عند وجود إشعارات غير مقروءة --}}
                    @if($unreadNotifications->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"
                              style="width: 8px; height: 8px;"></span>
                    @endif
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-2 mt-2 notification-menu"
                    aria-labelledby="notificationsDropdown" style="width: 310px; border-radius: 14px;">
                    
                    <li class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-6 text-dark">الإشعارات</span>
                        <span class="badge bg-light text-secondary" style="font-size: 11px;">
                            {{ $unreadNotifications->count() }} جديد
                        </span>
                    </li>

                    @forelse($allNotifications as $notification)
                        <li>
                            <a class="dropdown-item py-2 px-3 text-wrap d-flex flex-column gap-1 border-bottom {{ $notification->unread() ? 'bg-light' : '' }}" 
                               href="{{ $notification->data['url'] ?? '#' }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold text-dark" style="font-size: 13px;">
                                        {{ $notification->data['title'] ?? 'إشعار' }}
                                    </span>
                                    <span class="text-muted" style="font-size: 10px;">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="mb-0 text-secondary" style="font-size: 12px; line-height: 1.4;">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>
                            </a>
                        </li>
                    @empty
                        <li>
                            <div class="text-center pt-3 pb-2 px-3">
                                <span class="text-muted d-block py-1" style="font-size: 12px;">لا توجد إشعارات حالياً</span>
                            </div>
                        </li>
                    @endforelse
                </ul>
            </div>
        @endauth

        <a href="{{ route('profile.show') }}"
           class="nav-icon-btn text-decoration-none border-0 d-flex align-items-center justify-content-center {{ request()->routeIs('profile.*') ? 'active' : '' }}"
           aria-label="الملف الشخصي">
            <i class="fa-solid fa-user"></i>
        </a>
    </div>
</header>