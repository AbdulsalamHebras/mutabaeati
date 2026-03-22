<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class MuhdirController extends Controller
{
    public function dashboard()
    {
        $students = auth()->user()->students()
            ->where('status', 'نشط')
            ->with(['university', 'batch', 'specialization'])
            ->get()
            ->groupBy('university.name'); // 👈 تجميع حسب الجامعة

        return view('muhdir.dashboard', compact('students'));
    }

    public function distributions(Request $request)
    {
        $query = auth()->user()->students()
            ->where('status', 'نشط')
            ->with(['university', 'batch', 'specialization', 'examDistribution']);

        // 🔍 فلترة حسب وقت الاختبار (من جدول exam_distributions)
        if ($request->period) {
            $query->whereHas('examDistribution', function ($q) use ($request) {
                $q->where('period', $request->period);
            });
        }

        // فلترة عادية
        if ($request->section) {
            $query->where('section', $request->section);
        }

        if ($request->specialization_id) {
            $query->where('specialization_id', $request->specialization_id);
        }

        $students = $query->get()->groupBy('university.name');

        $specializations = \App\Models\Specialization::all();

        return view('muhdir.distribution', compact('students', 'specializations'));

    }

    public function reports()
    {
        return view('muhdir.reports');
    }
}
