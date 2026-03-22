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
    <h2>توزيع الاختبارات</h2>
    <form method="GET" class="filters">

        <input type="time" name="period" value="{{ request('period') }}">

        <input type="text" name="section" placeholder="الشعبة"
            value="{{ request('section') }}">

        <select name="specialization_id">
            <option value="">كل التخصصات</option>
            @foreach($specializations as $spec)
                <option value="{{ $spec->id }}"
                    {{ request('specialization_id') == $spec->id ? 'selected' : '' }}>
                    {{ $spec->name }}
                </option>
            @endforeach
        </select>

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

                        <!-- 📊 الجدول -->
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>الاسم</th>
                                    <th>الهاتف</th>
                                    <th>القسم</th>
                                    <th>التخصص</th>
                                    <th>الشعبة</th>
                                    <th>المادة</th>
                                    <th>وقت الاختبار</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($batchStudents as $student)
                                <tr>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->phone }}</td>
                                    <td>{{ $student->national_id }}</td>
                                    <td>{{ $student->specialization->name ?? '-' }}</td>
                                    <td>{{ $student->section }}</td>
                                    <td>{{ $student->examDistribution->subject ?? 'غير محدد' }}</td>
                                    <td>{{ $student->examDistribution->period ?? 'غير محدد' }}</td>


                                </tr>
                                @endforeach
                            </tbody>
                        </table>

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

</body>
