<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LogInController extends Controller
{
    public function AdminLogin()
    {
        return view("auth.admin_login");
    }
    public function loginUser(Request $request)
    {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:8|max:12',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::guard('web')->attempt($credentials)) {
        $user = Auth::user();

        switch ($user->role) {
            case 'CertificateAdmin':
                return redirect()->route('dashboard.certdashboard');
            case 'SuperAdmin':
                return redirect()->route('dashboard.dashboard');
            case 'DiplomaAdmin':
                return redirect()->route('dashboard.diplodashboard');
            case 'AdvancedDiplomaAdmin':
                return redirect()->route('dashboard.advdiplodashboard');
            default:
                Auth::logout();
                return back()->with('fail', 'Your role does not have a valid dashboard.');
        }
    }

    return back()->with('fail', 'Incorrect email or password.');
    }

}
