<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class MuhdirController extends Controller
{
    public function dashboard(Request $request)
    {
        $students = auth()->user()->students()
            ->where('status', 'نشط')
            ->with(['university', 'batch', 'specialization'])
            ->get()
            ->groupBy('university.name'); // 👈 تجميع حسب الجامعة
            $query = \App\Models\Lesson::whereHas('student', function ($q) {
                $q->where('muhdir_id', auth()->id());
            })->with(['student.specialization', 'student.batch']);
        // فلترة الوقت
        if ($request->period) {
            $query->where('period', $request->period);
        }

        // فلترة الشعبة
        if ($request->section) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('section', $request->section);
            });
        }

        // فلترة التخصص
        if ($request->specialization_id) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('specialization_id', $request->specialization_id);
            });
        }

        $lessons = $query->get();

        $sections = \App\Models\Student::distinct()->pluck('section');
        $specializations = \App\Models\Specialization::all();


        return view('muhdir.dashboard', compact('students', 'sections', 'specializations', 'lessons'));
    }
    public function lessonFilter(Request $request)
    {
        $query = \App\Models\Lesson::whereHas('student', function ($q) {
            $q->where('muhdir_id', auth()->id());
        })->with(['student.specialization']);

        if ($request->period) {
            $query->where('period', $request->period);
        }

        if ($request->section) {
            $query->whereHas('student', fn($q) =>
                $q->where('section', $request->section)
            );
        }

        if ($request->batch_id) {
            $query->whereHas('student', fn($q) =>
                $q->where('batch_id', $request->batch_id)
            );
        }

        if ($request->specialization_id) {
            $query->whereHas('student', fn($q) =>
                $q->where('specialization_id', $request->specialization_id)
            );
        }

        if ($request->search) {
            $query->whereHas('student', fn($q) =>
                $q->where('name', 'like', '%' . $request->search . '%')
            );
        }

        $lessons = $query->get();

        // 🔥 إذا الطلب AJAX
        if ($request->ajax()) {
            return response()->json($lessons);
        }

        return view('muhdir.dashboard', compact('lessons'));
    }

    public function distributions(Request $request)
    {
        $periods = [
            'من 9 الى 10 صباحاً',
            'من 10 الى 11 صباحاً',
            'من 11 الى 12 صباحاً',
            'من 12 الى 1 مساءً',
            'من 1 الى 2 مساءً',
            'من 2 الى 3 مساءً',
            'من 4 الى 5 مساءً',
            'من 5 الى 6 مساءً',
            'من 6 الى 7 مساءً',
            'من 7 الى 8 مساءً',
            'من 8 الى 9 مساءً',
            'من 9 الى 10 مساءً'
        ];

        $sections = \App\Models\Student::select('section')
            ->distinct()
            ->pluck('section');

        $query = \App\Models\ExamDistribution::whereHas('student', function ($q) {
            $q->where('muhdir_id', auth()->id())->where('status', 'نشط');
        })->with([
            'student.university',
            'student.batch',
            'student.specialization'
        ]);

        // فلترة الفترة
        if ($request->period) {
            $query->where('period', $request->period);
        }

        // فلترة الشعبة
        if ($request->section) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('section', $request->section);
            });
        }

        // فلترة التاريخ
        if ($request->date) {
            $query->whereDate('date', $request->date);
        }

        // فلترة التخصص
        if ($request->specialization_id) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('specialization_id', $request->specialization_id);
            });
        }

        $distributions = $query->get()->groupBy('student.university.name');

        $specializations = \App\Models\Specialization::all();

        return view('muhdir.distribution', compact('distributions', 'specializations','periods', 'sections'));
    }
    public function reports()
    {
        $students = auth()->user()->students()
            ->where('status', 'نشط')
            ->with(['university', 'batch', 'specialization', 'reports' => function($query) {
                // Fetch reports uploaded this current week
                $query->whereBetween('created_at', [
                    \Carbon\Carbon::now()->startOfWeek(),
                    \Carbon\Carbon::now()->endOfWeek()
                ]);
            }])
            ->get()
            ->groupBy('university.name');

        return view('muhdir.reports.index', compact('students'));
    }
}
