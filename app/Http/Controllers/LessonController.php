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
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        \App\Models\Lesson::create($request->all());

        return redirect()->back()->with('success', 'تم إضافة الحصة بنجاح');
    }
    public function update(Request $request)
    {
        $lesson = \App\Models\Lesson::find($request->lesson_id);

        $request->validate([
            'subject' => 'required|string',
            'day' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $lesson->update([
            'subject' => $request->subject,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return back()->with('success', 'تم التعديل');
    }

}
