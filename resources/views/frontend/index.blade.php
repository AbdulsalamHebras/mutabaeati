<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متابعتي | الرئيسية</title>
    <!-- Modern Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0f172a;
            --secondary-color: #3b82f6;
            --accent-color: #38bdf8;
            --bg-color: #f8fafc;
            --text-color: #1e293b;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
            color: var(--text-color);
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary-color), #1e293b);
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 0 5%;
            position: relative;
        }

        .hero-content {
            max-width: 600px;
            animation: fadeInRight 1s ease;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            background: linear-gradient(to right, #38bdf8, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.2rem;
            line-height: 1.8;
            color: #cbd5e1;
            margin-bottom: 30px;
        }

        /* Login Card */
        .login-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            width: 350px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: fadeInLeft 1s ease;
        }

        .login-card h2 {
            font-size: 1.8rem;
            margin-bottom: 25px;
            text-align: center;
            color: white;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: #cbd5e1;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: white;
            font-size: 1rem;
            box-sizing: border-box;
            outline: none;
            transition: 0.3s;
            font-family: inherit;
        }

        .input-group input:focus {
            border-color: var(--accent-color);
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            font-family: inherit;
        }

        .btn-submit:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.5);
        }

        /* Sections */
        .section {
            padding: 80px 5%;
        }

        .section-light {
            background: white;
        }

        .section-dark {
            background: #f1f5f9;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 50px;
            color: var(--primary-color);
        }

        /* Cards Grid */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            transition: 0.3s transform;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .feature-card h3 {
            font-size: 1.5rem;
            color: var(--secondary-color);
            margin-bottom: 15px;
        }

        /* Universities */
        .uni-card {
            background: linear-gradient(135deg, white, #f8fafc);
            padding: 30px;
            border-radius: 15px;
            border-right: 5px solid var(--secondary-color);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: 0.3s;
        }

        .uni-card:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }

        .uni-icon {
            font-size: 2.5rem;
        }

        .uni-info h3 {
            margin: 0 0 5px 0;
            color: var(--primary-color);
        }

        /* Announcements */
        .announcement-card {
            background: linear-gradient(135deg, #1e293b, var(--primary-color));
            color: white;
            padding: 30px;
            border-radius: 15px;
            border-right: 5px solid var(--accent-color);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
            position: relative;
            overflow: hidden;
        }

        .announcement-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        }

        .announcement-badge {
            background: var(--accent-color);
            color: var(--primary-color);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 15px;
        }

        /* Contact Us */
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            max-width: 900px;
            margin: 0 auto;
        }

        .contact-item {
            background: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            border: 1px solid #e2e8f0;
            transition: 0.3s;
        }

        .contact-item:hover {
            border-color: var(--secondary-color);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.1);
        }

        .contact-icon {
            font-size: 2.5rem;
            color: var(--secondary-color);
            margin-bottom: 15px;
        }

        /* Animations */
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @media (max-width: 768px) {
            .hero {
                flex-direction: column;
                padding-top: 100px;
                padding-bottom: 50px;
                text-align: center;
            }
            .hero-content {
                margin-bottom: 50px;
            }
        }

    </style>
</head>
<body>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>منصة متابعتي</h1>
            <p>
                المنصة الأولى لمتابعة حضور وتقارير وتقييمات الطلاب في الجامعات والمعاهد بأحدث تقنيات التعليم والمتابعة الإلكترونية الفعالة.
            </p>
            <a href="#about" style="color: white; text-decoration: none; border: 2px solid rgba(255,255,255,0.3); padding: 10px 30px; border-radius: 30px; transition: 0.3s; display: inline-block; margin-bottom: 10px;">
                تعرف علينا أكثر ↓
            </a>
            <a href="#contact" style="background: var(--accent-color); color: var(--primary-color); font-weight: bold; text-decoration: none; border: 2px solid var(--accent-color); padding: 10px 30px; border-radius: 30px; margin-right: 10px; transition: 0.3s; display: inline-block; margin-bottom: 10px;">
                تواصل معنا 📞
            </a>

            <!-- Employee Login Link -->
            <div style="margin-top: 20px;">
                <a href="{{ route('login') }}" style="color: #38bdf8; text-decoration: none; font-size: 1.1rem;">هل أنت مُحضر أو مراقب؟ تسجيل الدخول للإدارة →</a>
            </div>
        </div>

        <div class="login-card">
            <h2>تسجيل دخول الطالب</h2>

            @if(session('error'))
                <div style="background: rgba(239, 68, 68, 0.2); color: #fca5a5; padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('student.login.submit') }}" method="POST">
                @csrf
                <div class="input-group">
                    <label>رقم الهوية</label>
                    <input type="text" name="national_id" value="{{ old('national_id') }}" required placeholder="أدخل رقم الهوية">
                </div>
                <div class="input-group">
                    <label>كلمة المرور (رقم الهوية)</label>
                    <input type="password" name="password" required placeholder="أدخل كلمة المرور">
                </div>
                <button type="submit" class="btn-submit">تسجيل الدخول  →</button>
            </form>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="section section-light">
        <h2 class="section-title">من نحن</h2>
        <div style="max-width: 800px; margin: 0 auto; text-align: center; font-size: 1.25rem; line-height: 1.8; color: #475569;">
            "متابعتي" هي منصة تقنية متقدمة تهدف إلى ربط كل من الإداريين، والمراقبين، والمُحضرين، والطلاب في بيئة رقمية واحدة متكاملة.
            نسعى لتقديم أعلى معايير الجودة في متابعة الطلاب الأكاديمية وإصدار تقاريرهم الأسبوعية والشهرية بشفافية تامة ودقة عالية.
        </div>
    </section>

    <!-- What We Offer Section -->
    <section class="section section-dark">
        <h2 class="section-title">ماذا نقدم</h2>
        <div class="grid">
            <div class="feature-card">
                <div style="font-size: 3rem; margin-bottom: 20px;">📊</div>
                <h3>متابعة مستمرة</h3>
                <p style="color: #64748b; line-height: 1.6;">
                    نوفر لوحات تحكم متخصصة لمتابعة غياب وحضور الطلاب وتقييم أدائهم بشكل أسبوعي ومستمر لضمان أعلى جودة تعليمية.
                </p>
            </div>
            <div class="feature-card">
                <div style="font-size: 3rem; margin-bottom: 20px;">📁</div>
                <h3>تقارير رقمية</h3>
                <p style="color: #64748b; line-height: 1.6;">
                    إمكانية رفع ومراجعة وتحميل التقارير الأكاديمية إلكترونياً واعتمادها من قبل المراقبين بسلاسة وثوقية.
                </p>
            </div>
            <div class="feature-card">
                <div style="font-size: 3rem; margin-bottom: 20px;">🛡️</div>
                <h3>بيئة آمنة</h3>
                <p style="color: #64748b; line-height: 1.6;">
                    تشفير كامل للبيانات وتوفير خصوصية تامة لكل طالب ليتمكن من الاطلاع على معلوماته ونتائجه بأمان كامل.
                </p>
            </div>
        </div>
    </section>

    <!-- Announcements Section -->
    <section class="section section-dark">
        <h2 class="section-title">آخر الإعلانات</h2>
        <div style="max-width: 1000px; margin: 0 auto;" class="grid">
            <div class="announcement-card">
                <span class="announcement-badge">جديد 🌟</span>
                <h3 style="margin: 0 0 15px 0;">فتح باب التسجيل في الدفعة الثامنة جامعة الأمير مقرن بن عبدالعزيز</h3>
                <p style="color: #cbd5e1; line-height: 1.6; margin: 0;">
                    نُعلن لطلابنا الأعزاء عن بدء استقبال طلبات التسجيل للدفعة الثامنة للفصل الدراسي القادم في جامعة الأمير مقرن بن عبدالعزيز. بادر بالتسجيل الآن لضمان مقعدك وابدأ رحلتك الأكاديمية بنجاح!
                    ملاحظة: التسجيل مجاني ومتاح الى شهر مايو 2026، ومشهد القبول المبدئي مجاني
                </p>
                <div style="margin-top: 20px; font-size: 0.9rem; color: #94a3b8;">للتسجيل أو الاستفسار يرجى التواصل عبر الارقام الظاهر اسفل</div>
            </div>

            {{-- <div class="announcement-card">
                <span class="announcement-badge">تنبيه هام 📢</span>
                <h3 style="margin: 0 0 15px 0;">تحديث جداول الدورة التأهيلية</h3>
                <p style="color: #cbd5e1; line-height: 1.6; margin: 0;">
                    تم تحديث جدول الحصص الخاص بطلاب الدورات التأسيسية. يُرجى من الطلاب المعنيين الدخول للوحة التحكم لمراجعة الجدول والتأكد من أوقات الحصص الجديدة.
                </p>
                <div style="margin-top: 20px; font-size: 0.9rem; color: #94a3b8;">📅 التاريخ: تم النشر مؤخراً</div>
            </div> --}}
        </div>
    </section>

    <!-- Universities Section -->
    <section class="section section-light">
        <h2 class="section-title">الجامعات والمعاهد التابعة</h2>
        <div style="max-width: 1000px; margin: 0 auto;" class="grid">
            @forelse($universities as $university)
                <div class="uni-card">
                    <div class="uni-icon">🎓</div>
                    <div class="uni-info">
                        <h3>{{ $university->name }}</h3>
                        <span style="color: #94a3b8; font-size: 0.9rem;">صرح تعليمي معتمد</span>
                    </div>
                </div>
            @empty
                <div style="text-align: center; color: #94a3b8; font-size: 1.2rem; width: 100%;">
                    لا توجد جامعات مضافة حالياً.
                </div>
            @endforelse
        </div>
    </section>

    <!-- Contact Us Section -->
    <section id="contact" class="section section-dark">
        <h2 class="section-title">تواصل معنا</h2>
        <div class="contact-grid">
            <div class="contact-item">
                <div class="contact-icon">💬</div>
                <h3 style="margin: 0 0 10px 0; color: var(--primary-color);"> واتس آب</h3>
                <p style="color: #64748b; margin: 0; font-weight: bold; font-size: 1.1rem;">+966 54 138 7494</p>
            </div>

            <div class="contact-item">
                <div class="contact-icon">💬</div>
                <h3 style="margin: 0 0 10px 0; color: var(--primary-color);">واتس آب</h3>
                <p style="color: #64748b; margin: 0; font-weight: bold; font-size: 1.1rem; direction: ltr;">+966 54 731 2586</p>
            </div>


        </div>
    </section>

    <!-- Footer -->
    <footer style="background: var(--primary-color); color: white; text-align: center; padding: 20px; font-size: 0.9rem;">
        جميع الحقوق محفوظة &copy; {{ date('Y') }} لمنصة متابعتي.
    </footer>

</body>
</html>
