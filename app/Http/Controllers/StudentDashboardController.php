<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        return view('student.dashboard', [
            'user' => Auth::user(),
        ]);
    }
}
