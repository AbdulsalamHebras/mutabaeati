<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;

class LessonController extends Controller
{


    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject' => 'required|string',
            'day' => 'required|string',
            'period' => 'required|string',
        ]);

        \App\Models\Lesson::create($request->all());

        return redirect()->back()->with('success', 'تم إضافة الحصة بنجاح');
    }
    public function update(Request $request)
    {
        $lesson = \App\Models\Lesson::find($request->lesson_id);

        $lesson->update([
            'subject' => $request->subject,
            'day' => $request->day,
            'period' => $request->period,
        ]);

        return back()->with('success', 'تم التعديل');
    }

}
