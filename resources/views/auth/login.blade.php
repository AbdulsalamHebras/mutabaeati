<x-guest-layout>
    <head>
        <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
        <!-- أيقونات -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    </head>

    <div class="login-wrapper" dir="rtl">
        <div class="login-card">

            <!-- Header -->
            <div class="text-center mb-6">
                <h1>تسجيل الدخول</h1>
                <p class="subtitle">أهلاً بك مجددًا في بوابة المتابعة الأولى</p>
            </div>

            <form method="POST" action="{{ route('login') }}" x-data="{ show: false }">
                @csrf

                <!-- Email -->
                <div class="input-group">
                    <label>البريد الإلكتروني</label>
                    <input type="email" name="email" required>
                </div>

                <!-- Password -->
                <div class="input-group password-box">
                    <label>كلمة المرور</label>

                    <div class="password-wrapper">
                        <input :type="show ? 'text' : 'password'" name="password" required>

                        <i class="fa-solid"
                           :class="show ? 'fa-eye-slash' : 'fa-eye'"
                           @click="show = !show">
                        </i>
                    </div>
                </div>

                <!-- Options -->
                <div class="options">
                    <label><input type="checkbox" name="remember"> تذكرني</label>
                </div>

                <!-- User Type -->
                <div class="input-group">
                    <label>نوع المستخدم</label>
                    <select name="role">
                        <option value="" disabled selected>اختر نوع المستخدم</option>
                        <option value="muhdir">محضر</option>
                        <option value="muraqib">مراقب</option>
                    </select>
                </div>

                <button type="submit">تسجيل الدخول</button>

            </form>
        </div>
    </div>
</x-guest-layout>
