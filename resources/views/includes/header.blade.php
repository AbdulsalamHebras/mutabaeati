<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام الاختبارات</title>

    <link rel="stylesheet" href="{{ asset('css/includes/header.css') }}">
</head>

<body>

<header class="header">
    <div class="container">

        <!-- الشعار -->
        <div class="logo">
            نظام الاختبارات
        </div>

        <!-- زر الجوال -->
        <div class="menu-toggle" onclick="toggleMenu()">☰</div>

        <!-- القائمة -->
        <nav class="nav" id="navMenu">
            <a href="{{ route('muhdir.dashboard') }}" class="nav-link active">التحضير</a>
            <a href="" class="nav-link">التقارير</a>
            <a href="{{ route('muhdir.distribution') }}" class="nav-link">توزيع الاختبارات</a>
        </nav>

        <!-- يمين الهيدر -->
        <div class="header-actions">

            <!-- 🔔 الإشعارات -->
            <div class="notifications" onclick="toggleNotifications()">
                🔔
                <span class="badge">3</span>

                <div class="dropdown" id="notifDropdown">
                    <p>📌 لديك إشعار جديد</p>
                    <p>📌 تم إضافة اختبار جديد</p>
                    <p>📌 تم رفع تقرير</p>
                </div>
            </div>

            <!-- 👤 المستخدم -->
            <div class="user-menu" onclick="toggleUserMenu()">
                👤

                <div class="dropdown" id="userDropdown">
                    <a href="#">الملف الشخصي</a>

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
</script>
