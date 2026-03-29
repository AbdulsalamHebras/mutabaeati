<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Lesson;
use App\Models\Specialization;
use Illuminate\Http\Request;

class MuraqibController extends Controller
{
    public function dashboard(Request $request)
    {
        $batchId = auth()->user()->batch_id;

        $students = Student::where('status', 'نشط')
            ->where('batch_id', $batchId)
            ->with(['university', 'batch', 'specialization'])
            ->get()
            ->groupBy('university.name');

        $query = Lesson::whereHas('student', function ($q) use ($batchId) {
            $q->where('batch_id', $batchId);
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

        $sections = Student::distinct()->pluck('section');
        $specializations = Specialization::all();

        return view('muraqib.dashboard', compact('students', 'sections', 'specializations', 'lessons'));
    }

    public function lessonFilter(Request $request)
    {
        $batchId = auth()->user()->batch_id;

        $query = Lesson::whereHas('student', function ($q) use ($batchId) {
            $q->where('batch_id', $batchId);
        })->with(['student.specialization', 'student.batch']);

        if ($request->period) {
            $query->where('period', $request->period);
        }

        if ($request->section) {
            $query->whereHas('student', fn($q) =>
                $q->where('section', $request->section)
            );
        }

        // No need to filter by batch_id from request since Muraqib is restricted to their batch
        // But if they somehow send it, it's ignored or we can just apply it (which would be redundant or a security issue if they try to change it)

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

        if ($request->ajax()) {
            return response()->json($lessons);
        }

        return view('muraqib.dashboard', compact('lessons'));
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

        $batchId = auth()->user()->batch_id;

        $sections = \App\Models\Student::where('batch_id', $batchId)
            ->select('section')
            ->distinct()
            ->pluck('section');

        $query = \App\Models\Student::where('batch_id', $batchId)
            ->where('status', 'نشط')
            ->with(['university', 'batch', 'specialization', 'examDistributions'])
            ->whereHas('examDistributions', function ($q) use ($request) {
                if ($request->period) {
                    $q->where('period', $request->period);
                }
                if ($request->date) {
                    $q->whereDate('date', $request->date);
                }
            });

        // فلترة الشعبة
        if ($request->section) {
            $query->where('section', $request->section);
        }

        // فلترة التاريخ
        if ($request->date) {
            // Already handled in whereHas, but if we want to filter students who have exams on that date
            $query->with(['examDistributions' => function($q) use ($request) {
                $q->whereDate('date', $request->date);
                if ($request->period) {
                    $q->where('period', $request->period);
                }
            }]);
        } elseif ($request->period) {
            $query->with(['examDistributions' => function($q) use ($request) {
                $q->where('period', $request->period);
            }]);
        }

        // فلترة التخصص
        if ($request->specialization_id) {
            $query->where('specialization_id', $request->specialization_id);
        }

        $students = $query->get()->groupBy('university.name');

        $specializations = \App\Models\Specialization::all();

        return view('muraqib.distribution', compact('students', 'specializations','periods', 'sections'));
    }

    public function reports()
    {
        $batchId = auth()->user()->batch_id;

        $students = Student::where('status', 'نشط')
            ->where('batch_id', $batchId)
            ->with(['university', 'batch', 'specialization', 'reports' => function($query) {
                // Fetch reports uploaded this current week
                $query->whereBetween('created_at', [
                    \Carbon\Carbon::now()->startOfWeek(),
                    \Carbon\Carbon::now()->endOfWeek()
                ]);
            }])
            ->get()
            ->groupBy('university.name');

        return view('muraqib.reports.index', compact('students'));
    }

    public function updateReportStatus(Request $request, \App\Models\Report $report)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
            'rejection_reason' => 'nullable|string',
        ]);

        if ($request->status === 'rejected' && empty($request->rejection_reason)) {
            return redirect()->back()->with('error', 'يجب كتابة سبب الرفض');
        }

        $report->update([
            'status' => $request->status,
            'rejection_reason' => $request->status === 'rejected' ? $request->rejection_reason : null,
        ]);

        return redirect()->back()->with('success', 'تم الاستجابة على التقرير بنجاح');
    }
}
