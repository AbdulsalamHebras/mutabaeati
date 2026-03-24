<?php

namespace App\Http\Controllers;

use App\Models\University;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        $universities = University::all();
        return view('frontend.index', compact('universities'));
    }
}
