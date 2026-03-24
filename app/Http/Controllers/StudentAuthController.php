<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'national_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $student = Student::where('national_id', $request->national_id)->first();

        if ($student && $request->password === $student->national_id) {
            Auth::guard('student')->login($student);
            return redirect()->route('student.dashboard');
        }

        return redirect()->back()->with('error', 'بيانات الدخول غير صحيحة')->withInput($request->only('national_id'));
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
