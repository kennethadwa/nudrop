<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentsModel;
use App\Models\PickupRequest;
use App\Models\PaymentMethod;
use App\Models\User;

class PaymentsController extends Controller
{
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'pickup_request_id' => 'required|exists:pickup_requests,id',
            'request_type' => 'required|in:pickup,delivery',
            'amount' => 'required|numeric',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'paid_at' => 'nullable|date',
        ]);

        $payment = PaymentsModel::create($validated);

        return response()->json([
            'message' => 'Payment created successfully',
            'payment' => $payment
        ], 201);
    }

    public function index()
    {
        $payments = PaymentsModel::with(['user', 'pickupRequest', 'paymentMethod'])->latest()->get();

        return view('accounting.payments', compact('payments'));
    }

    public function show($id)
    {
        $payment = PaymentsModel::with(['user', 'pickupRequest', 'paymentMethod'])->findOrFail($id);
        return response()->json($payment);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'pickup_request_id' => 'required|exists:pickup_requests,id',
            'request_type' => 'required|in:pickup,delivery',
            'amount' => 'required|numeric',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'paid_at' => 'nullable|date',
        ]);

        $payment = PaymentsModel::findOrFail($id);
        $payment->update($validated);

        return response()->json([
            'message' => 'Payment updated successfully',
            'payment' => $payment
        ]);
    }

    public function destroy($id)
    {
        $payment = PaymentsModel::findOrFail($id);
        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully']);
    }
}
