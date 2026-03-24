<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقارير الطلاب</title>

    <link rel="stylesheet" href="{{ asset('css/muhdir/dashboard.css') }}">
</head>
<body>
    @include('includes.header')
    <h2>تقارير الأسبوع الحالي</h2>
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
                                                <th>رقم الهوية</th>
                                                <th>البريد</th>
                                                <th>التخصص</th>
                                                <th>الشعبة</th>
                                                <th>حالة التقرير</th>
                                                <th>الإجراء</th>
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
                                                    <td>{{ $student->email }}</td>
                                                    <td>{{ $student->specialization->name ?? '-' }}</td>
                                                    <td>{{ $student->section }}</td>

                                                    <td>
                                                        @if($currentWeekReport)
                                                            @if($currentWeekReport->status == 'accepted')
                                                                <span style="color: green; font-weight: bold;">مقبول</span>
                                                            @elseif($currentWeekReport->status == 'rejected')
                                                                <span style="color: red; font-weight: bold;">مرفوض</span>
                                                            @else
                                                                <span style="color: orange; font-weight: bold;">قيد المراجعة</span>
                                                            @endif
                                                        @else
                                                            <span style="color: gray;">لم يُرفع بعد</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if($currentWeekReport)
                                                            <div style="margin-bottom: 10px;">
                                                                <a href="{{ asset('storage/' . $currentWeekReport->file_path) }}" target="_blank" style="color: #155724; text-decoration: underline; font-size: 14px;">
                                                                    📥 تحميل التقرير
                                                                </a>
                                                            </div>

                                                            <form action="{{ route('muraqib.reports.status', $currentWeekReport->id) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                <input type="hidden" name="status" value="accepted">
                                                                <button type="submit" style="background: green; color: white; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer;">✔️ قبول</button>
                                                            </form>

                                                            <button onclick="rejectReport({{ $currentWeekReport->id }})" style="background: red; color: white; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer;">❌ رفض</button>
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
    </div>

    <script>
        function rejectReport(reportId) {
            let reason = prompt("الرجاء كتابة سبب الرفض:");
            if (reason !== null && reason.trim() !== "") {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = '/muraqib/reports/' + reportId + '/status';

                let csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                let status = document.createElement('input');
                status.type = 'hidden';
                status.name = 'status';
                status.value = 'rejected';
                form.appendChild(status);

                let reasonInput = document.createElement('input');
                reasonInput.type = 'hidden';
                reasonInput.name = 'rejection_reason';
                reasonInput.value = reason;
                form.appendChild(reasonInput);

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

    <script src="{{ asset('js/muhdir/dashboard.js') }}"></script>

    @include('includes.footer')
</body>
</html>
