<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StaffAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StaffAccountController extends Controller
{

    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff_accounts,email',
            'password' => 'required|string|min:6',
            'roles' => 'required|in:0,1,2',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('staff_profiles', 'public');
        }

        StaffAccount::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles' => $request->roles,
            'profile_picture' => $path,
        ]);

        return redirect()->back()->with('success', 'Staff created successfully.');
    }

}
