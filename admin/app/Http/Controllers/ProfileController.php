<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\StaffAccount;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // Check if the user is authenticated
        $staffAccount = Auth::guard('staff')->user();

        // If user is not authenticated, redirect to login page
        if (!$staffAccount) {
            return redirect()->route('login'); // Adjust this route name based on your login route
        }

        return view('profile.edit', [
            'staff' => $staffAccount, // Pass the authenticated staff data to the view
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $staffAccount = Auth::guard('staff')->user();

        // If user is not authenticated, redirect to login page
        if (!$staffAccount) {
            return redirect()->route('login'); // Adjust this route name based on your login route
        }

        // Start by validating and filling the basic profile data
        $staffAccount->fill($request->validated());

        // Handle the profile picture update
        if ($request->hasFile('profile_picture')) {
            // Validate the image upload
            $request->validate([
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Delete the old profile picture if it exists
            if ($staffAccount->profile_picture) {
                Storage::delete('public/' . $staffAccount->profile_picture);
            }

            // Store the new profile picture
            $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $staffAccount->profile_picture = $imagePath;
        }

        // If the email is changed, set email_verified_at to null
        if ($staffAccount->isDirty('email')) {
            $staffAccount->email_verified_at = null;
        }

        // Save the updated staff account data
        $staffAccount->save();

        return Redirect::route('profile.edit')->with('profileUpdated', 'Your profile has been updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
{
    $staffAccount = Auth::guard('staff')->user();

    // If user is not authenticated, redirect to login page
    if (!$staffAccount) {
        return redirect()->route('login');
    }

    // Validate the password fields
    $request->validate([
        'current_password' => ['required', 'current_password'], // Ensure current password is correct
        'password' => ['required', 'string', 'min:8', 'confirmed'], // Validate new password
    ]);

    // Check if the current password is correct
    if (!Hash::check($request->current_password, $staffAccount->password)) {
        return back()->withErrors(['current_password' => 'Current password is incorrect.']);
    }

    // Update the password if validation passes
    $staffAccount->password = Hash::make($request->password); // Hash the new password
    $staffAccount->save(); // Save the updated password

    // Re-authenticate the user to prevent logout
    Auth::guard('staff')->loginUsingId($staffAccount->id);

    return redirect()->route('profile.edit')->with('profileUpdated', 'Your password has been updated successfully.');
}

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('staffDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $staffAccount = Auth::guard('staff')->user();

        // If user is not authenticated, redirect to login page
        if (!$staffAccount) {
            return redirect()->route('login');
        }

        Auth::guard('staff')->logout(); // Log out the staff account

        $staffAccount->delete(); // Delete the staff account

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/staff/login')->with('accountDeleted', 'account-deleted');
    }
}
