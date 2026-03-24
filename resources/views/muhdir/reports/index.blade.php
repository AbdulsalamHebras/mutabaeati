<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>  التقارير</title>

    <link rel="stylesheet" href="{{ asset('css/muhdir/dashboard.css') }}">
</head>
<body>
    @include('includes.header')
    <h2>  التقارير</h2>
    <input type="text" id="searchInput" placeholder="🔍 ابحث عن طالب..." class="search-box">

    @if(session('success'))
        <div id="alert-success" style="background-color: #d4edda; color: #155724; padding: 15px; margin: 15px auto; max-width: 1200px; border-radius: 5px; transition: opacity 0.5s ease;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div id="alert-error" style="background-color: #f8d7da; color: #721c24; padding: 15px; margin: 15px auto; max-width: 1200px; border-radius: 5px; transition: opacity 0.5s ease;">
            {{ session('error') }}
        </div>
    @endif

    <script>
        setTimeout(() => {
            const success = document.getElementById('alert-success');
            if (success) {
                success.style.opacity = '0';
                setTimeout(() => success.remove(), 500);
            }
            const error = document.getElementById('alert-error');
            if (error) {
                error.style.opacity = '0';
                setTimeout(() => error.remove(), 500);
            }
        }, 5000);
    </script>

    <div class="container">
        <form action="{{ route('muhdir.reports.storeMultiple') }}" method="POST" enctype="multipart/form-data">
            @csrf



        @foreach($students as $universityName => $uniStudents)

            <!-- 🎓 الجامعة -->
            <div class="card">
                <div class="card-header" onclick="toggle(this)">
                    {{ $universityName }}
                </div>

                <div class="card-body">

                    @php
                        $batches = $uniStudents->groupBy('batch.name');
                    @endphp

                    @foreach($batches as $batchName => $batchStudents)

                        <!-- 📦 الدفعة -->
                        <div class="sub-card">
                            <div class="sub-header" onclick="toggle(this)">
                                الدفعة: {{ $batchName }}
                            </div>

                            <div class="sub-body">


                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>الاسم</th>
                                                <th>رقم الجوال</th>
                                                <th> الرقم الهوية</th>
                                                <th> كلمة المرور</th>
                                                <th>البريد</th>
                                                <th>التخصص</th>
                                                <th>المدة</th>
                                                <th>الشعبة</th>
                                                <th>رفع التقرير</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($batchStudents as $student)
                                                @php
                                                    $currentWeekReport = $student->reports->first();
                                                @endphp
                                                <tr style="{{ $currentWeekReport ? 'background-color: #d4edda;' : '' }}">
                                                    <td>{{ $student->name }}</td>
                                                    <td>{{ $student->phone }}</td>
                                                    <td>{{ $student->national_id }}</td>
                                                    <td>{{ $student->platform_password }}</td>
                                                    <td>{{ $student->email }}</td>
                                                    <td>{{ $student->specialization->name ?? '-' }}</td>
                                                    <td>{{ $student->duration }}</td>
                                                    <td>{{ $student->section }}</td>
                                                    <td>
                                                        <input type="file" name="reports[{{ $student->id }}]">
                                                        @if($currentWeekReport)
                                                            <div style="margin-top: 5px;">
                                                                <a href="{{ asset('storage/' . $currentWeekReport->file_path) }}" target="_blank" style="color: #155724; text-decoration: underline; font-size: 14px;">
                                                                    📥 تحميل التقرير المرفوع
                                                                </a>
                                                            </div>
                                                            <div style="margin-top: 10px; padding: 5px; border-radius: 4px; font-size: 13px; font-weight: bold; text-align: center;
                                                                @if($currentWeekReport->status == 'accepted') background: #d4edda; color: #155724;
                                                                @elseif($currentWeekReport->status == 'rejected') background: #f8d7da; color: #721c24;
                                                                @else background: #fff3cd; color: #856404; @endif
                                                            ">
                                                                الحالة:
                                                                @if($currentWeekReport->status == 'accepted') ✅ مقبول
                                                                @elseif($currentWeekReport->status == 'rejected') ❌ مرفوض
                                                                @else ⏳ قيد المراجعة @endif

                                                                @if($currentWeekReport->status == 'rejected' && $currentWeekReport->rejection_reason)
                                                                    <div style="margin-top: 5px; font-weight: normal; font-size: 12px; color: #721c24;">السبب: {{ $currentWeekReport->rejection_reason }}</div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>


                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
            <div style="text-align: left; margin-top: 20px;">
                <button type="submit" style="background-color: var(--primary-color, #0d6efd); color: white; padding: 12px 30px; border: none; border-radius: 5px; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    🚀 رفع التقارير المحددة
                </button>
            </div>
        </form>
    </div>

    <script>
        window.routes = {
            lessonFilter: "{{ route('muhdir.lessonFilter') }}"
        };
    </script>
    <script src="{{ asset('js/muhdir/dashboard.js') }}"></script>

    @include('includes.footer')
</body>
