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
        if ($request->start_time || $request->end_time) {
            $isValidRange = !$request->start_time || !$request->end_time || $request->start_time < $request->end_time;
            if ($isValidRange) {
                if ($request->start_time) {
                    $query->where('start_time', '>=', $request->start_time);
                }
                if ($request->end_time) {
                    $query->where('end_time', '<=', $request->end_time);
                }
            }
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

        if ($request->start_time || $request->end_time) {
            $isValidRange = !$request->start_time || !$request->end_time || $request->start_time < $request->end_time;
            if ($isValidRange) {
                if ($request->start_time) {
                    $query->where('start_time', '>=', $request->start_time);
                }
                if ($request->end_time) {
                    $query->where('end_time', '<=', $request->end_time);
                }
            }
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
        $batchId = auth()->user()->batch_id;

        $sections = \App\Models\Student::where('batch_id', $batchId)
            ->select('section')
            ->distinct()
            ->pluck('section');

        $query = \App\Models\Student::where('batch_id', $batchId)
            ->where('status', 'نشط')
            ->with(['university', 'batch', 'specialization', 'examDistributions'])
            ->whereHas('examDistributions', function ($q) use ($request) {
                $isValidRange = !$request->start_time || !$request->end_time || $request->start_time < $request->end_time;
                if ($isValidRange) {
                    if ($request->start_time) {
                        $q->where('start_time', '>=', $request->start_time);
                    }
                    if ($request->end_time) {
                        $q->where('end_time', '<=', $request->end_time);
                    }
                }
                if ($request->date) {
                    $q->whereDate('date', $request->date);
                }
            })
            ->orWhereHas('examDistributions', function ($q) {
                $q->where('supervisor_id', auth()->id());
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
                $isValidRange = !$request->start_time || !$request->end_time || $request->start_time < $request->end_time;
                if ($isValidRange) {
                    if ($request->start_time) {
                        $q->where('start_time', '>=', $request->start_time);
                    }
                    if ($request->end_time) {
                        $q->where('end_time', '<=', $request->end_time);
                    }
                }
            }]);
        } elseif ($request->start_time || $request->end_time) {
            $query->with(['examDistributions' => function($q) use ($request) {
                $isValidRange = !$request->start_time || !$request->end_time || $request->start_time < $request->end_time;
                if ($isValidRange) {
                    if ($request->start_time) {
                        $q->where('start_time', '>=', $request->start_time);
                    }
                    if ($request->end_time) {
                        $q->where('end_time', '<=', $request->end_time);
                    }
                }
            }]);
        }

        // فلترة التخصص
        if ($request->specialization_id) {
            $query->where('specialization_id', $request->specialization_id);
        }

        $students = $query->get()->groupBy('university.name');

        $specializations = \App\Models\Specialization::all();

        return view('muraqib.distribution', compact('students', 'specializations', 'sections'));
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
