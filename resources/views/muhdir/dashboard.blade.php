<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحضير المحضر</title>

    <link rel="stylesheet" href="{{ asset('css/muhdir/dashboard.css') }}">
</head>
<body>
    @include('includes.header')
    <input type="text" id="searchInput" placeholder="🔍 ابحث عن طالب..." class="search-box">

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
                                <div id="pagination"></div>
                                <button onclick="exportTable()">📥 تصدير Excel</button>

                                <!-- 📊 جدول الطلاب -->
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>الاسم</th>
                                            <th>الهاتف</th>
                                            <th>الرقم الوطني</th>
                                            <th> كلمة المرور</th>
                                            <th>البريد</th>
                                            <th>التخصص</th>
                                            <th>المدة</th>
                                            <th>القسم</th>
                                            <th>ملاحظات</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($batchStudents as $student)
                                            <tr>
                                                <td>{{ $student->name }}</td>
                                                <td>{{ $student->phone }}</td>
                                                <td>{{ $student->national_id }}</td>
                                                <td>{{ $student->platform_password }}</td>
                                                <td>{{ $student->email }}</td>
                                                <td>{{ $student->specialization->name ?? '-' }}</td>
                                                <td>{{ $student->duration }}</td>
                                                <td>{{ $student->section }}</td>
                                                <td>{{ $student->notes }}</td>
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

    </div>
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
    document.getElementById("searchInput").addEventListener("keyup", function () {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll(".table tbody tr");

        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value)
                ? ""
                : "none";
        });
    });
    let rowsPerPage = 5;
        let table = document.querySelector(".table tbody");
        let rows = table.querySelectorAll("tr");

        function showPage(page) {
            rows.forEach((row, index) => {
                row.style.display = (index >= (page-1)*rowsPerPage && index < page*rowsPerPage)
                    ? ""
                    : "none";
            });
        }

        function createPagination() {
            let pageCount = Math.ceil(rows.length / rowsPerPage);
            let container = document.getElementById("pagination");

            for (let i = 1; i <= pageCount; i++) {
                let btn = document.createElement("button");
                btn.innerText = i;
                btn.onclick = () => showPage(i);
                container.appendChild(btn);
            }
        }

        createPagination();
        showPage(1);
        function exportTable() {
            let table = document.querySelector(".table").outerHTML;
            let url = 'data:application/vnd.ms-excel,' + encodeURIComponent(table);
            let link = document.createElement("a");
            link.href = url;
            link.download = "students.xls";
            link.click();
        }
    </script>

    @include('includes.footer')
</body>
