<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();

        // Fetch only accepted reports
        $reports = $student->reports()->where('status', 'accepted')->orderBy('created_at', 'desc')->get();

        return view('student.dashboard', compact('student', 'reports'));
    }
}
