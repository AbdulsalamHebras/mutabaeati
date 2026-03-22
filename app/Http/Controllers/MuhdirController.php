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
        $periods = ['من 4 الى 5', 'من 5 الى 6', 'من 6 الى 7', 'من 7 الى 8','من 8 الى 9','من 9 الى 10'];

        $sections = \App\Models\Student::select('section')
            ->distinct()
            ->pluck('section');

        $query = auth()->user()->students()
            ->where('status', 'نشط')
            ->with([
                'university',
                'batch',
                'specialization',
                'examDistributions',
                'lessons' // 🔥 أضفناها هنا بدل الاستعلام الثاني
            ]);

        // فلترة الفترة
        if ($request->period) {
            $query->whereHas('examDistributions', function ($q) use ($request) {
                $q->where('period', $request->period);
            });
        }

        // فلترة الشعبة
        if ($request->section) {
            $query->where('section', $request->section);
        }

        $students = $query->get()->groupBy('university.name');

        $specializations = \App\Models\Specialization::all();

        return view('muhdir.distribution', compact('students', 'specializations','periods', 'sections'));
    }
    public function reports()
    {
        return view('muhdir.reports');
    }
}
