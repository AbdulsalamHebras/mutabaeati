function toggle(element) {
    let body = element.nextElementSibling;

    if (!body) return;

    if (body.style.display === "block") {
        body.style.display = "none";
    } else {
        body.style.display = "block";
    }
}
// ==========================
// 📦 INIT
// ==========================
document.addEventListener("DOMContentLoaded", () => {

    initSearch();
    initFilters();
    initPagination();
    initFormValidation();

});

function initFormValidation() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const startInput = this.querySelector('input[name="start_time"]');
            const endInput = this.querySelector('input[name="end_time"]');

            if (startInput && endInput && startInput.value && endInput.value) {
                if (startInput.value >= endInput.value) {
                    e.preventDefault();
                    alert("خطأ: يجب أن يكون وقت البداية قبل وقت النهاية");
                }
            }
        });
    });
}

// ==========================
// 🔍 البحث العام (جدول الطلاب)
// ==========================
function initSearch() {
    const input = document.getElementById("searchInput");

    if (!input) return;

    input.addEventListener("keyup", function () {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll(".table tbody tr");

        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value)
                ? ""
                : "none";
        });
    });
}

// ==========================
// ⚡ Live Filter + Search
// ==========================
function initFilters() {
    const subBodies = document.querySelectorAll('.sub-body');

    subBodies.forEach(subBody => {
        const search = subBody.querySelector(".liveSearch");
        const startTime = subBody.querySelector(".filterStartTime");
        const endTime = subBody.querySelector(".filterEndTime");
        const section = subBody.querySelector(".filterSection");
        const spec = subBody.querySelector(".filterSpec");
        const table = subBody.querySelector(".lessonsTable");

        if (!search || !table) return;

        let timeout;

        function fetchLessons() {
            clearTimeout(timeout);

            timeout = setTimeout(() => {
                let searchVal = search.value;
                let startVal = startTime.value;
                let endVal = endTime.value;
                let sectionVal = section.value;
                let specVal = spec.value;
                let batchId = table.dataset.batch || '';

                if (startVal && endVal && startVal >= endVal) {
                    table.innerHTML = `<tr><td colspan="9" style="text-align: center; color: red;">عذراً: وقت البداية يجب أن يكون قبل وقت النهاية</td></tr>`;
                    return;
                }

                table.innerHTML = `<tr><td colspan="9" style="text-align: center;">جاري البحث...</td></tr>`;

                fetch(`${window.routes.lessonFilter}?search=${encodeURIComponent(searchVal)}&start_time=${encodeURIComponent(startVal)}&end_time=${encodeURIComponent(endVal)}&section=${encodeURIComponent(sectionVal)}&specialization_id=${encodeURIComponent(specVal)}&batch_id=${encodeURIComponent(batchId)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    let html = "";
                    if (data.length === 0) {
                        html = `<tr><td colspan="9">لا توجد نتائج</td></tr>`;
                    } else {
                        data.forEach(lesson => {
                            html += `
                                <tr>
                                    <td>${lesson.student.name}</td>
                                    <td>${lesson.student.national_id}</td>
                                    <td>${lesson.student.platform_password}</td>
                                    <td>${lesson.student.specialization?.name ?? '-'}</td>
                                    <td>${lesson.student.section}</td>
                                    <td>${lesson.subject}</td>
                                    <td>${lesson.day}</td>
                                    <td>${formatTime(lesson.start_time)} - ${formatTime(lesson.end_time)}</td>
                                    <td>
                                        <button onclick="editLesson(${lesson.id}, '${lesson.subject}', '${lesson.day}', '${lesson.start_time}', '${lesson.end_time}')">
                                            ✏️ تعديل
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    table.innerHTML = html;
                }).catch(err => {
                    console.error('Error fetching lessons:', err);
                    table.innerHTML = `<tr><td colspan="9" style="text-align: center;">حدث خطأ في جلب البيانات</td></tr>`;
                });

            }, 400); // debounce
        }

        search.addEventListener("keyup", fetchLessons);
        startTime.addEventListener("change", fetchLessons);
        endTime.addEventListener("change", fetchLessons);
        section.addEventListener("change", fetchLessons);
        spec.addEventListener("change", fetchLessons);
    });
}

function formatTime(timeStr) {
    if (!timeStr) return '-';
    // Expects HH:mm or HH:mm:ss
    let [hours, minutes] = timeStr.split(':');
    let ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    return hours + ':' + minutes + ' ' + ampm;
}

// ==========================
// 📄 Pagination
// ==========================
function initPagination() {

    const table = document.querySelector(".table tbody");
    if (!table) return;

    const rows = table.querySelectorAll("tr");
    const rowsPerPage = 5;
    const container = document.getElementById("pagination");

    if (!container) return;

    function showPage(page) {
        rows.forEach((row, index) => {
            row.style.display = (index >= (page - 1) * rowsPerPage && index < page * rowsPerPage)
                ? ""
                : "none";
        });
    }

    const pageCount = Math.ceil(rows.length / rowsPerPage);

    for (let i = 1; i <= pageCount; i++) {
        let btn = document.createElement("button");
        btn.innerText = i;
        btn.onclick = () => showPage(i);
        container.appendChild(btn);
    }

    showPage(1);
}

// ==========================
// 📥 Export Excel
// ==========================
function exportTable() {
    let table = document.querySelector(".table").outerHTML;
    let url = 'data:application/vnd.ms-excel,' + encodeURIComponent(table);
    let link = document.createElement("a");
    link.href = url;
    link.download = "students.xls";
    link.click();
}

// ==========================
// 🪟 Modals
// ==========================

function openModal(students) {
    const select = document.getElementById("studentSelect");
    select.innerHTML = "";

    students.forEach(student => {
        let option = document.createElement("option");
        option.value = student.id;
        option.text = student.name;
        select.appendChild(option);
    });

    document.getElementById("lessonModal").style.display = "block";
}

function closeModal() {
    document.getElementById("lessonModal").style.display = "none";
}

function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}

function editLesson(id, subject, day, startTime, endTime) {
    document.getElementById('lesson_id').value = id;
    document.getElementById('subject').value = subject;
    document.getElementById('day').value = day;
    document.getElementById('edit_start_time').value = startTime?.substring(0, 5);
    document.getElementById('edit_end_time').value = endTime?.substring(0, 5);

    document.getElementById('editModal').style.display = 'block';
}

// ==========================
// 🔍 البحث داخل select
// ==========================
function filterStudents() {
    let input = document.getElementById("studentSearch").value.toLowerCase();
    let options = document.getElementById("studentSelect").options;

    for (let i = 0; i < options.length; i++) {
        let text = options[i].text.toLowerCase();
        options[i].style.display = text.includes(input) ? "" : "none";
    }
}

// ==========================
// ❌ إغلاق عند الضغط خارج
// ==========================
window.onclick = function(e) {
    const lessonModal = document.getElementById("lessonModal");
    const editModal = document.getElementById("editModal");

    if (e.target === lessonModal) lessonModal.style.display = "none";
    if (e.target === editModal) editModal.style.display = "none";
}
