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
            ->get();
            
        return view('muhdir.dashboard', compact('students'));
    }

    public function distributions()
    {
        return view('muhdir.distributions');
    }

    public function reports()
    {
        return view('muhdir.reports');
    }
}
