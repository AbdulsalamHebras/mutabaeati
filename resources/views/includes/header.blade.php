<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> متابعاتي</title>

    <link rel="stylesheet" href="{{ asset('css/includes/header.css') }}">
</head>

<body>

<header class="header">
    <div class="container">

        <!-- الشعار -->
        <div class="logo">
           متابعاتي
        </div>

        <!-- زر الجوال -->
        <div class="menu-toggle" onclick="toggleMenu()">☰</div>

        <nav class="nav" id="navMenu">
            @php
                $rolePrefix = auth()->user()->isMuraqib() ? 'muraqib' : 'muhdir';
            @endphp

            <a href="{{ route($rolePrefix . '.dashboard') }}"
            class="nav-link {{ request()->routeIs($rolePrefix . '.dashboard') ? 'active' : '' }}">
                التحضير
            </a>

            <a href="{{route($rolePrefix . '.reports.index')}}"
            class="nav-link {{ request()->routeIs($rolePrefix . '.reports.index') ? 'active' : '' }}">
                التقارير
            </a>

            <a href="{{ route($rolePrefix . '.distribution') }}"
            class="nav-link {{ request()->routeIs($rolePrefix . '.distribution') ? 'active' : '' }}">
                توزيع الاختبارات
            </a>

        </nav>

        <!-- يمين الهيدر -->
        <div class="header-actions">

            <!-- 🔔 الإشعارات -->
            <div class="notifications" onclick="toggleNotifications()">
            🔔

                <!-- 🔢 عدد غير المقروء -->
                <span class="badge">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>

                <div class="dropdown" id="notifDropdown">

                    @forelse(auth()->user()->unreadNotifications as $notification)
                        <div class="notif-item {{ is_null($notification->read_at) ? 'unread' : '' }}"
                            onclick="markAsRead('{{ $notification->id }}')">

                            📌 {{ $notification->data['message'] }}

                        </div>
                    @empty
                        <p>لا توجد إشعارات</p>
                    @endforelse

                </div>
            </div>

            <!-- 👤 المستخدم -->
            <div class="user-menu" onclick="toggleUserMenu()">
                👤

                <div class="dropdown" id="userDropdown">

                    <hr>

                    <!-- تسجيل الخروج -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn">
                            تسجيل الخروج
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</header>

<div class="header-space"></div>

<script>
    function toggleMenu() {
        document.getElementById("navMenu").classList.toggle("show");
    }

    function toggleNotifications() {
        document.getElementById("notifDropdown").classList.toggle("show");
    }

    function toggleUserMenu() {
        document.getElementById("userDropdown").classList.toggle("show");
    }

    // إغلاق القوائم عند الضغط خارجها
    window.onclick = function(e) {
        if (!e.target.closest('.notifications')) {
            document.getElementById("notifDropdown").classList.remove("show");
        }
        if (!e.target.closest('.user-menu')) {
            document.getElementById("userDropdown").classList.remove("show");
        }
    }
    function markAsRead(id) {
        fetch('/notifications/read/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => {
            location.reload(); // تحديث الصفحة
        });
    }
</script>
