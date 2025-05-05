<?php

namespace App\Http\Controllers;
use App\Models\PickUpRequest;
use App\Models\User; // Import User model
use App\Models\Document; // Import Document model
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function accounting_index()
    {
        $requests = PickUpRequest::with(['user', 'document'])->latest()->get();
        return view('accounting.pickup_request', compact('requests'));
    }

    public function create()
    {
        // Get all users and documents for the create form
        $users = User::all();
        $documents = Document::all();
        return view('accounting.add_new_request', compact('users', 'documents'));
    }

    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'user_id' => 'required',
            'document_id' => 'required',
            'pickup_date' => 'nullable|date',
            'remarks' => 'nullable|string',
        ]);

        // Generate a unique reference number for the pickup request
        $referenceNo = 'PICKUP-' . strtoupper(uniqid());

        // Create a new pickup request
        PickUpRequest::create([
            'reference_no' => $referenceNo,
            'user_id' => $request->user_id,
            'document_id' => $request->document_id,
            'pickup_date' => $request->pickup_date,
            'remarks' => $request->remarks,
            'is_paid' => 'paid', // Default value for is_paid
        ]);

        // Redirect to the pickup requests index page with a success message
        return redirect()->route('pickup_requests.index')->with('success', 'Pickup request created successfully.');
    }

    public function edit($id)
    {
        // Find the pickup request by ID
        $pickupRequest = PickUpRequest::findOrFail($id);

        // Get all users and documents for the edit form
        $users = User::all();
        $documents = Document::all();

        return view('account.edit_pickup_request', compact('pickupRequest', 'users', 'documents'));
    }

    public function update(Request $request, $id)
    {
        // Validate incoming request data
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'document_id' => 'required|exists:documents,id',
            'status' => 'required|string',
            'pickup_date' => 'nullable|date',
            'is_paid' => 'required|in:paid,unpaid',
            'remarks' => 'nullable|string',
        ]);

        // Find the pickup request and update with validated data
        $pickupRequest = PickUpRequest::findOrFail($id);
        $pickupRequest->update([
            'user_id' => $request->user_id,
            'document_id' => $request->document_id,
            'status' => $request->status,
            'pickup_date' => $request->pickup_date,
            'is_paid' => $request->is_paid,
            'remarks' => $request->remarks,
        ]);

        // Redirect to the index page with success message
        return redirect()->route('pickup_requests.index')->with('success', 'Pickup request updated successfully.');
    }

    public function destroy($id)
    {
        // Find and delete the pickup request
        $pickupRequest = PickUpRequest::findOrFail($id);
        $pickupRequest->delete();

        // Redirect to the index page with success message
        return redirect()->route('pickup_requests.index')->with('success', 'Pickup request deleted.');
    }
}