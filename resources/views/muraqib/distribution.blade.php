<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/muhdir/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/muhdir/filter.css') }}">
    <title>توزيع الاختبارات</title>
</head>
<body>
    @include('includes.header')
    <h2>توزيع الاختبارات</h2>
    <form method="GET" class="filters">

        <select name="period" class="form-control">
            <option value="">اختر الفترة</option>
            @foreach($periods as $period)
                <option value="{{ $period }}"
                    {{ request('period') == $period ? 'selected' : '' }}>
                    {{ $period }}
                </option>
            @endforeach
        </select>

        <select name="section" class="form-control">
            <option value="">اختر الشعبة</option>
            @foreach($sections as $section)
                <option value="{{ $section }}"
                    {{ request('section') == $section ? 'selected' : '' }}>
                    {{ $section }}
                </option>
            @endforeach
        </select>

        <select name="specialization_id">
            <option value="">كل التخصصات</option>
            @foreach($specializations as $spec)
                <option value="{{ $spec->id }}"
                    {{ request('specialization_id') == $spec->id ? 'selected' : '' }}>
                    {{ $spec->name }}
                </option>
            @endforeach
        </select>

        <input type="date" name="date" value="{{ request('date') }}" class="form-control">

        <button type="submit">فلترة</button>

    </form>
    @foreach($students as $universityName => $uniStudents)

        <div class="card">
            <div class="card-header" onclick="toggle(this)">
                🎓 {{ $universityName }}
            </div>

            <div class="card-body">

                @php
                    $batches = $uniStudents->groupBy('batch.name');
                @endphp

                @foreach($batches as $batchName => $batchStudents)

                <div class="sub-card">
                    <div class="sub-header" onclick="toggle(this)">
                        📦 الدفعة: {{ $batchName }}
                    </div>

                    <div class="sub-body">

                        <!-- 🔍 البحث -->
                        <input type="text" class="search-box" placeholder="بحث...">

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>الاسم</th>
                                        <th>رقم الجوال</th>
                                        <th>القسم</th>
                                        <th>التخصص</th>
                                        <th>الشعبة</th>
                                        <th>المادة</th>
                                        <th>يوم الاختبار</th>
                                        <th>تاريخ الاختبار</th>
                                        <th>وقت الاختبار</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($batchStudents as $student)
                                    <tr>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->phone }}</td>
                                        <td>{{$student->national_id}}</td>
                                        <td>{{ $student->specialization->name ?? '-' }}</td>
                                        <td>{{ $student->section }}</td>

                                        <!-- 📚 المواد -->
                                        <td>
                                            @foreach($student->examDistributions as $exam)
                                                <div class="cell-item">
                                                    {{ $exam->subject }}
                                                </div>
                                            @endforeach
                                        </td>

                                        <!-- ⏰ الأوقات -->
                                        <td>
                                            @foreach($student->examDistributions as $exam)
                                                <div class="cell-item">
                                                    {{ $exam->exam_day }}
                                                </div>
                                            @endforeach
                                        </td>

                                        <td>
                                            @foreach($student->examDistributions as $exam)
                                                <div class="cell-item">
                                                    {{ $exam->date }}
                                                </div>
                                            @endforeach
                                        </td>

                                        <td>
                                            @foreach($student->examDistributions as $exam)
                                                <div class="cell-item">
                                                    {{ $exam->period }}
                                                </div>
                                            @endforeach
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
        <script>
            function toggle(element) {
                let body = element.nextElementSibling;

                if (!body) return;

                if (body.style.display === "block") {
                    body.style.display = "none";
                } else {
                    body.style.display = "block";
                }
            }
        </script>
        @include('includes.footer')


</body>
