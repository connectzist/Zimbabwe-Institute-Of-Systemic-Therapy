<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentProfileController extends Controller
{
    public function showProfile()
    {
        $student = Auth::guard('student')->user();
        return view('student.profile', compact('student'));
    }
}
