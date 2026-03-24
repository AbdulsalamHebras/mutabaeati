<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة الطالب | تقاريري</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0f172a;
            --secondary-color: #3b82f6;
            --bg-color: #f1f5f9;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 0;
            color: #1e293b;
        }

        .header {
            background-color: white;
            padding: 20px 5%;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-color);
        }

        .logout-btn {
            background-color: #ef4444;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s; border:none; cursor:pointer; font-family: inherit;
        }
        
        .logout-btn:hover { background-color: #dc2626; }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .welcome-card {
            background: linear-gradient(135deg, var(--secondary-color), #818cf8);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
        }

        .welcome-card h1 { margin: 0 0 10px 0; font-size: 2rem; }
        .welcome-card p { margin: 0; opacity: 0.9; font-size: 1.1rem; }

        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .report-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            border-right: 5px solid #10b981;
            transition: 0.3s;
        }

        .report-card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }

        .report-date { color: #64748b; font-size: 0.9rem; margin-bottom: 10px; display: block; }
        
        .report-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .download-btn {
            display: inline-block;
            background: #f1f5f9;
            color: var(--secondary-color);
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
            width: 100%;
            text-align: center;
            box-sizing: border-box;
        }

        .download-btn:hover { background: var(--secondary-color); color: white; }

        .empty-state { text-align: center; padding: 50px; background: white; border-radius: 12px; color: #94a3b8; font-size: 1.2rem; }
    </style>
</head>
<body>

    <header class="header">
        <div class="header-title">منصة متابعتي | الطالب</div>
        <form action="{{ route('student.logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">تسجيل الخروج</button>
        </form>
    </header>

    <div class="container">
        
        <div class="welcome-card">
            <h1>مرحباً، {{ $student->name }}</h1>
            <p>الرقم الأكاديمي: {{ $student->academic_id ?? '-' }} | التخصص: {{ $student->specialization->name ?? '-' }}</p>
        </div>

        <h2 style="color: var(--primary-color); margin-bottom: 20px;">التقارير الأكاديمية (المعتمدة)</h2>

        @if($reports->isEmpty())
            <div class="empty-state">
                <div style="font-size: 3rem; margin-bottom: 15px;">📥</div>
                لا توجد تقارير معتمدة حتى الآن.
            </div>
        @else
            <div class="reports-grid">
                @foreach($reports as $report)
                    <div class="report-card">
                        <span class="report-date">تاريخ الرفع: {{ $report->created_at->format('Y-m-d') }}</span>
                        <div class="report-title">تقرير أسبوعي</div>
                        <a href="{{ asset('storage/' . $report->file_path) }}" target="_blank" class="download-btn">
                            📥 استعراض / تحميل
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

</body>
</html>
