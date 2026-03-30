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
        $query = \App\Models\Lesson::where(function ($q) {
            $q->whereHas('student', function ($q) {
                $q->where('muhdir_id', auth()->id());
            })
            ->orWhereHas('student.examDistributions', function ($q) {
                $q->where('supervisor_id', auth()->id());
            });
        })->with(['student.specialization', 'student.batch']);
        // فلترة الوقت
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

        $sections = \App\Models\Student::distinct()->pluck('section');
        $specializations = \App\Models\Specialization::all();


        return view('muhdir.dashboard', compact('students', 'sections', 'specializations', 'lessons'));
    }
    public function lessonFilter(Request $request)
    {
        $query = \App\Models\Lesson::where(function ($q) use ($request) {
            $q->whereHas('student', function ($q) {
                $q->where('muhdir_id', auth()->id());
            })
            ->orWhereHas('student.examDistributions', function ($q) {
                $q->where('supervisor_id', auth()->id());
            });
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

        return view('muhdir.distribution', compact('students', 'specializations', 'sections'));
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
