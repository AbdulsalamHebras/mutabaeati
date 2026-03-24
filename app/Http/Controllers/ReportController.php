<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function create(Student $student)
    {
        // Ensure student belongs to the authenticated muhdir
        if ($student->muhdir_id !== auth()->id()) {
            abort(403);
        }

        return view('muhdir.reports.create', compact('student'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB
            
        ]);

        $student = Student::findOrFail($request->student_id);
        if ($student->muhdir_id !== auth()->id()) {
            abort(403);
        }

        $path = $request->file('file')->store('reports', 'public');

        Report::create([
            'student_id' => $request->student_id,
            'muhdir_id' => auth()->id(),
            'file_path' => $path,

        ]);

        return redirect()->route('muhdir.dashboard')->with('success', 'تم رفع التقرير بنجاح');
    }

    public function storeMultiple(Request $request)
    {
        $request->validate([
            'reports' => 'nullable|array',
            'reports.*' => 'file|mimes:pdf,doc,docx|max:10240',
        ]);

        if (!$request->hasFile('reports') || empty($request->file('reports'))) {
            return redirect()->back()->with('error', 'الرجاء اختيار ملف واحد على الأقل');
        }

        $uploadedCount = 0;

        foreach ($request->file('reports') as $student_id => $file) {
            $student = Student::find($student_id);
            if ($student && $student->muhdir_id === auth()->id()) {
                // Check if a report exists for this week
                $existingReport = Report::where('student_id', $student->id)
                    ->whereBetween('created_at', [
                        \Carbon\Carbon::now()->startOfWeek(),
                        \Carbon\Carbon::now()->endOfWeek()
                    ])->first();

                if ($existingReport) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($existingReport->file_path);
                    $existingReport->delete();
                }

                $path = $file->store('reports', 'public');

                Report::create([
                    'student_id' => $student->id,
                    'muhdir_id' => auth()->id(),
                    'file_path' => $path,

                ]);
                $uploadedCount++;
            }
        }

        if ($uploadedCount > 0) {
            return redirect()->route('muhdir.reports.index')->with('success', "تم رفع {$uploadedCount} ملف/ملفات بنجاح");
        }

        return redirect()->back()->with('error', 'لم يتم رفع أي ملفات، تأكد من اختيارها بشكل صحيح');
    }
}
