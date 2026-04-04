<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحضير المحضر</title>

    <link rel="stylesheet" href="{{ asset('css/muhdir/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/muhdir/filter.css') }}">

</head>
<body>
    @include('includes.header')
    <h2>  التحضير</h2>
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

                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>الاسم</th>
                                                <th>رقم الجوال</th>
                                                <th> رقم الهوية</th>
                                                <th> كلمة المرور</th>
                                                <th>البريد</th>
                                                <th>الرقم الاكاديمي</th>
                                                <th>التخصص</th>
                                                <th>المدة</th>
                                                <th>الشعبة</th>
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
                                                    <td>{{ $student->academic_id }}</td>
                                                    <td>{{ $student->specialization->name ?? '-' }}</td>
                                                    <td>{{ $student->duration }}</td>
                                                    <td>{{ $student->section }}</td>
                                                    <td>{{ $student->notes }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="lessons-header">

                                    <h4>📅 جدول الحصص</h4>
                                    <input type="text" class="liveSearch" placeholder="🔍 ابحث عن طالب...">

                                        <div class="time-filter">
                                            <label>من:</label>
                                            <input type="time" class="filterStartTime">
                                            <label>إلى:</label>
                                            <input type="time" class="filterEndTime">
                                        </div>

                                        <select class="filterSection">
                                            <option value="">كل الشعب</option>
                                            @foreach($sections as $section)
                                                <option value="{{ $section }}">{{ $section }}</option>
                                            @endforeach
                                        </select>

                                        <select class="filterSpec">
                                            <option value="">كل التخصصات</option>
                                            @foreach($specializations as $spec)
                                                <option value="{{ $spec->id }}">{{ $spec->name }}</option>
                                            @endforeach
                                        </select>
                                    <button class="add-btn"
                                        onclick='openModal(@json($batchStudents))'>
                                        ➕ إضافة حصة
                                    </button>
                                </div>
                                <div class="table-responsive">

                                    <table class="table" >
                                        <thead>
                                            <tr>
                                                <th>الطالب</th>
                                                <th>رقم الهوية</th>
                                                <th>كلمة المرور</th>
                                                <th> التخصص</th>
                                                <th>الشعبة</th>
                                                <th>المادة</th>
                                                <th>اليوم</th>
                                                <th>الوقت</th>
                                                <th>الإجراء</th>
                                            </tr>
                                        </thead>

                                        <tbody class="lessonsTable" data-batch="{{ $batchStudents->first()->batch_id }}">
                                            @foreach($lessons->where('student.batch_id', $batchStudents->first()->batch_id) as $lesson)
                                            <tr>
                                                <td>{{ $lesson->student->name }}</td>
                                                <td>{{ $lesson->student->national_id }}</td>
                                                <td>{{ $lesson->student->platform_password }}</td>
                                                <td>{{ $lesson->student->specialization->name }}</td>
                                                <td>{{ $lesson->student->section }}</td>

                                                <td>{{ $lesson->subject }}</td>
                                                <td>{{ $lesson->day }}</td>
                                                <td>
                                                    {{ $lesson->start_time?->format('h:i A') }} - {{ $lesson->end_time?->format('h:i A') }}
                                                </td>

                                                <td>
                                                    <button onclick="editLesson({{ $lesson->id }}, '{{ $lesson->subject }}', '{{ $lesson->day }}', '{{ $lesson->start_time?->format('H:i') }}', '{{ $lesson->end_time?->format('H:i') }}')">
                                                        ✏️ تعديل
                                                    </button>
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
    <div id="lessonModal" class="modal">

        <div class="modal-content">

            <div class="modal-header">
                <h3>إضافة حصة جديدة</h3>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>

            <form method="POST" action="{{ route('muhdir.lessons.store') }}">
                @csrf

                <div class="form-group">
                    <label for="studentSearch">🔍 البحث عن طالب</label>
                    <input type="text" id="studentSearch" placeholder="اكتب اسم الطالب للبحث..." onkeyup="filterStudents()" autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="studentSelect">👤 اختيار الطالب من القائمة</label>
                    <select name="student_id" id="studentSelect" size="5" required>
                    </select>
                </div>

                <div class="form-group">
                    <label for="subject">📚 المادة</label>
                    <input type="text" name="subject" id="subject" placeholder="اسم المادة" required>
                </div>

                <div class="form-group">
                    <label for="day">📅 اليوم</label>
                    <select name="day" id="day" required>
                        <option value="الأحد">الأحد</option>
                        <option value="الإثنين">الإثنين</option>
                        <option value="الثلاثاء">الثلاثاء</option>
                        <option value="الأربعاء">الأربعاء</option>
                        <option value="الخميس">الخميس</option>
                    </select>
                </div>

                <div class="modal-time-inputs">
                    <div class="time-field">
                        <label>🕒 وقت البداية</label>
                        <input type="time" name="start_time" required>
                    </div>
                    <div class="time-field">
                        <label>🕒 وقت النهاية</label>
                        <input type="time" name="end_time" required>
                    </div>
                </div>

                <button type="submit" class="save-btn">💾 حفظ الحصة</button>

            </form>

        </div>

    </div>
    <div id="editModal" class="modal">
    <div class="modal-content">
            <div class="modal-header">
                <h3>تعديل الحصة</h3>
                <span class="close" onclick="closeEditModal()">&times;</span>
            </div>
            <form method="POST" action="{{ route('muhdir.lessons.update') }}">
                @csrf

                <input type="hidden" name="lesson_id" id="lesson_id">

                <div class="form-group">
                    <label for="subject">📚 المادة</label>
                    <input type="text" name="subject" id="subject" placeholder="المادة" required>
                </div>

                <div class="form-group">
                    <label for="day">📅 اليوم</label>
                    <select name="day" id="day" required>
                        <option value="الأحد">الأحد</option>
                        <option value="الإثنين">الإثنين</option>
                        <option value="الثلاثاء">الثلاثاء</option>
                        <option value="الأربعاء">الأربعاء</option>
                        <option value="الخميس">الخميس</option>
                    </select>
                </div>

                <div class="modal-time-inputs">
                    <div class="time-field">
                        <label>🕒 وقت البداية</label>
                        <input type="time" name="start_time" id="edit_start_time" required>
                    </div>
                    <div class="time-field">
                        <label>🕒 وقت النهاية</label>
                        <input type="time" name="end_time" id="edit_end_time" required>
                    </div>
                </div>

                <button type="submit" class="save-btn">💾 حفظ التعديلات</button>
            </form>

        </div>
    </div>
    <script>
        window.routes = {
            lessonFilter: "{{ route('muhdir.lessonFilter') }}"
        };
    </script>
    <script src="{{ asset('js/muhdir/dashboard.js') }}"></script>

    @include('includes.footer')
</body>
