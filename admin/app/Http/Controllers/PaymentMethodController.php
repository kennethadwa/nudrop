<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::all();
        return view('accounting.payment_settings', compact('paymentMethods'));
    }

    public function create()
    {
        return view('accounting.add_payment_method');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'payment_image' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'payment_number' => 'required|string|max:50', // payment_number is now required and non-nullable
        ]);

        if ($request->hasFile('payment_image')) {
            $path = $request->file('payment_image')->store('payment_images', 'public');
            $validated['payment_image'] = $path;
        }

        $validated['is_active'] = 0; // default inactive

        PaymentMethod::create($validated);

        return redirect()->route('accounting.payment_settings')->with('success', 'Payment method created successfully.');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('accounting.edit_payment_method', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'payment_image' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'payment_number' => 'required|string|max:50', // payment_number is now required and non-nullable
        ]);

        // Check if a new image is uploaded
        if ($request->hasFile('payment_image')) {
            // Store the image and get the path
            $imagePath = $request->file('payment_image')->store('payment_images', 'public');
            // Set the payment_image path in the validated data
            $validated['payment_image'] = $imagePath;

            // Optionally, delete the old image if you want to manage file cleanup
            if ($paymentMethod->payment_image) {
                // Remove the old image from storage
                Storage::disk('public')->delete($paymentMethod->payment_image);
            }
        }

        // Update the PaymentMethod with validated data (including new image if any)
        $paymentMethod->update($validated);

        return redirect()->route('accounting.payment_settings')->with('success', 'Payment method updated successfully.');
    }

    public function toggleActive(Request $request, $id)
{
    $paymentMethod = PaymentMethod::findOrFail($id);
    $paymentMethod->is_active = $request->has('is_active');
    $paymentMethod->save();

    return back()->with('success', 'Payment method updated.');
}



    public function savePaymentMethods(Request $request)
    {
        // Validate the selected payment methods
        $validated = $request->validate([
            'active_payment_methods' => 'required|array',
            'active_payment_methods.*' => 'exists:payment_methods,id',
        ]);

        // Get the selected payment method IDs
        $paymentMethodIds = $validated['active_payment_methods'];

        // Deactivate all other payment methods first
        PaymentMethod::whereNotIn('id', $paymentMethodIds)->update(['is_active' => 0]);

        // Activate the selected payment methods
        PaymentMethod::whereIn('id', $paymentMethodIds)->update(['is_active' => 1]);

        return redirect()->route('accounting.payment_settings')->with('success', 'Payment methods updated successfully.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return redirect()->route('accounting.payment_settings')->with('success', 'Payment method deleted successfully.');
    }
}
