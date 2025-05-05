<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\StaffAccount;

class StaffAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $credentials = [
    'email' => strtolower($request->email),
    'password' => $request->password,
];


    if (Auth::guard('staff')->attempt($credentials)) {
    $staff = Auth::guard('staff')->user();

    switch ($staff->roles) {
        case 0:
            return redirect()->route('admin.dashboard');
        case 1:
            return redirect()->route('accounting.dashboard');
        case 2:
            return redirect()->route('registrar.dashboard');
        default:
            return redirect()->route('home');
    }
}


    // If authentication fails
    \Log::warning('Authentication failed for email: ' . $request->email);  // Log failed attempts
    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
}

    public function logout()
    {
        session()->forget(['staff_id', 'staff_role', 'staff_name']);
        return redirect()->route('staff.login')->with('success', 'Logged out.');
    }
}
