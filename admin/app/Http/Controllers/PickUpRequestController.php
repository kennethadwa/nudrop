<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PickUpRequest;
use App\Models\User;
use App\Models\Document; 
use App\Models\PaymentsModel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\TransactionItem;


class PickUpRequestController extends Controller
{
    public function index()
{
    $requests = PickUpRequest::with(['user', 'document', 'payment'])
        ->whereHas('payment', function ($query) {
            $query->where('is_verified', 1);
        })
        ->latest()
        ->paginate(10);

    return view('registrar.pickup_request', compact('requests'));
}


    public function create()
    {
        // Get all users and documents for the create form
        $users = User::all();
        $documents = Document::all();
        return view('registrar.add_new_request', compact('users', 'documents'));
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

    public function registrarShow($id)
{
    $pickupRequest = PickupRequest::with(['user', 'document', 'payment'])->findOrFail($id);

    return response()->json($pickupRequest);
}


    public function edit($id)
    {
        // Find the pickup request by ID
        $pickupRequest = PickUpRequest::findOrFail($id);

        // Get all users and documents for the edit form
        $users = User::all();
        $documents = Document::all();

        return view('registrar.edit_pickup_request', compact('pickupRequest', 'users', 'documents'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'status' => 'required|string',
    ]);

    $pickupRequest = PickUpRequest::findOrFail($id);
    $pickupRequest->update([
        'status' => $request->status,
    ]);

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

    public function registrar_archive()
{
    $archivedRequests = PickUpRequest::onlyTrashed()
        ->with(['user', 'document', 'payment'])
        ->latest('deleted_at')
        ->paginate(10);

    return view('registrar.archive', compact('archivedRequests'));
}













    
    //Accounting

    public function accounting_index()
{
    // Retrieve all pickup requests with related user, document, payment, and payment_method details
    $requests = PickUpRequest::with(['user', 'document', 'payments', 'payments.payment_method'])->latest()->paginate(10);
    return view('accounting.pickup_request', compact('requests'));
}

    public function accounting_create()
{
    // Get all users, documents, and payment methods for the create form
    $users = User::all();
    $documents = Document::all();
    $paymentMethods = PaymentMethod::all(); // Fetch all payment methods

    return view('accounting.add_new_request', compact('users', 'documents', 'paymentMethods'));
}


    public function accounting_store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required',
        'document_id' => 'required',
        'pickup_date' => 'nullable|date',
        'remarks' => 'nullable|string',
        'payment_method_id' => 'required|integer',
        'is_verified' => 'required|boolean',
        'verification_remarks' => 'nullable|string',
        'proof_of_payment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'request_type' => 'required|string',
        'amount' => 'required|numeric',
    ]);

    $referenceNo = 'PICKUP-' . strtoupper(uniqid());

    $pickupRequest = PickUpRequest::create([
        'reference_no' => $referenceNo,
        'user_id' => $validated['user_id'],
        'document_id' => $validated['document_id'],
        'pickup_date' => $validated['pickup_date'],
        'remarks' => $validated['remarks'],
        'status' => 'pending',
        'is_paid' => 1,
        'request_date' => now(),
        'is_verified' => $validated['is_verified'],
        'verification_remarks' => $validated['verification_remarks'],
    ]);

    $filePath = null;
    if ($request->hasFile('proof_of_payment')) {
        $filePath = $request->file('proof_of_payment')->store('proofs', 'public');
    }

    $payment = PaymentsModel::create([
        'user_id' => $validated['user_id'],
        'pickup_request_id' => $pickupRequest->id,
        'request_type' => $validated['request_type'],
        'amount' => $validated['amount'],
        'payment_method_id' => $validated['payment_method_id'],
        'paid_at' => now(),
        'proof_of_payment' => $filePath,
        'is_verified' => $validated['is_verified'],
        'verification_remarks' => $validated['verification_remarks'],
    ]);

    $transactionNo = 'TRNSC-' . strtoupper(uniqid());

    $transactionData = [
        'pickup_request_id' => $pickupRequest->id,
        'amount' => $validated['amount'],
        'payment_id' => $payment->id,
        'transaction_no' => $transactionNo,
        'verified_by' => null,
        'verified_at' => null,
    ];

    if ($validated['is_verified']) {
        $transactionData['verified_by'] = auth('staff')->id();
        $transactionData['verified_at'] = now();
    }

    $transaction = Transaction::create($transactionData);

    TransactionItem::create([
        'transaction_id' => $transaction->id,
        'document_id' => $validated['document_id'],
        'amount' => $validated['amount'],
    ]);

    return redirect()->route('accounting.pickup_requests.index')->with('success', 'Pickup request created successfully.');
}

      public function show($id)
{
    $pickupRequest = PickUpRequest::with(['user', 'document', 'payments.payment_method'])->findOrFail($id);
    return view('accounting.verify_pickup_request', compact('pickupRequest'));
}

public function verify(Request $request, $id)
{
    // Validate the input
    $request->validate([
        'is_verified' => 'required|boolean',
    ]);

    // Find the payment and update its verification status
    $payment = PaymentsModel::findOrFail($id);
    $oldStatus = $payment->is_verified;
    $payment->is_verified = $request->is_verified;
    $payment->save();

    // Find the related transaction for the payment
    $transaction = Transaction::where('payment_id', $payment->id)->first();

    if ($transaction) {
        // Check if the verification status has been changed to "verified"
        if ($request->is_verified == 1 && $oldStatus == 0) {
            // If verification status is now 1 (verified), update the transaction
            $transaction->update([
                'verified_by' => auth('staff')->id(),  // Store the staff's ID who verified
                'verified_at' => now(),  // Store the current time
            ]);
        } elseif ($request->is_verified == 0) {
            // If verification status is now 0 (not verified), remove the verified information
            $transaction->update([
                'verified_by' => null,  // Set verified_by to null
                'verified_at' => null,  // Set verified_at to null
            ]);
        }
    }

    return redirect()->route('accounting.pickup_requests.index')->with('success', 'Payment verification updated.');
}


    public function accounting_edit($id)
{
    // Find the pickup request by ID
    $pickupRequest = PickUpRequest::findOrFail($id);

    // Get all users, documents, and payment methods for the edit form
    $users = User::all();
    $documents = Document::all();
    $paymentMethods = PaymentMethod::all(); // Fetch all payment methods

    return view('accounting.edit_pickup_request', compact('pickupRequest', 'users', 'documents', 'paymentMethods'));
}

    public function accounting_update(Request $request, $id)
{
    // Validate incoming request data
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'document_id' => 'required|exists:documents,id',
        'status' => 'required|string',
        'pickup_date' => 'nullable|date',
        'is_paid' => 'required|in:paid,unpaid',
        'remarks' => 'nullable|string',
        'is_verified' => 'nullable|boolean', // Optionally update verification status
        'verification_remarks' => 'nullable|string', // Optionally update verification remarks
        'proof_of_payment' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:10240', // Validate the proof_of_payment file if present
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

    // Find the payment record linked to this pickup request
    $payment = PaymentsModel::where('pickup_request_id', $pickupRequest->id)->first();

    if ($payment) {
        // Check if proof_of_payment is uploaded
        $proof_of_payment_path = $payment->proof_of_payment; // Default to the existing proof of payment

        if ($request->hasFile('proof_of_payment')) {
            // If a new file is uploaded, store it in the 'payment_proofs' directory
            $proof_of_payment_path = $request->file('proof_of_payment')->store('payment_proofs', 'public');
        }

        // Update the payment record with the new verification status, remarks, and proof of payment if needed
        $payment->update([
            'is_verified' => $request->is_verified ?? $payment->is_verified, // Update verification status if provided
            'verification_remarks' => $request->verification_remarks ?? $payment->verification_remarks, // Update remarks if provided
            'proof_of_payment' => $proof_of_payment_path, // Update proof of payment if a new file was uploaded
        ]);
    }

    // Redirect to the index page with success message
    return redirect()->route('accounting.pickup_requests.index')->with('success', 'Pickup request and payment updated successfully.');
}



    public function accounting_destroy($id)
{
    $pickupRequest = PickUpRequest::findOrFail($id);

    $payment = PaymentsModel::where('pickup_request_id', $pickupRequest->id)->first();

    if ($payment) {
        $transaction = Transaction::where('payment_id', $payment->id)->first();

        if ($transaction) {
            // Soft delete transaction items
            TransactionItem::where('transaction_id', $transaction->id)->get()->each->delete();

            // Soft delete the transaction
            $transaction->delete();
        }

        // Soft delete the payment
        $payment->delete();
    }

    // Soft delete the pickup request
    $pickupRequest->delete();

    return redirect()->route('accounting.pickup_requests.index')->with('success', 'Pickup request and related records soft deleted.');
}


// Display archived pick-up requests
    public function accounting_archive()
{
    $archivedRequests = PickUpRequest::withTrashed()
        ->whereNotNull('deleted_at')
        ->with(['user', 'document', 'payments', 'payments.payment_method'])
        ->latest()
        ->paginate(10);

    return view('accounting.archive', compact('archivedRequests'));
}


    // Restore a soft-deleted request
    public function accounting_restore($id)
{
    $pickupRequest = PickUpRequest::onlyTrashed()->findOrFail($id);
    $pickupRequest->restore();

    // Restore related payment
    $payment = PaymentsModel::onlyTrashed()->where('pickup_request_id', $pickupRequest->id)->first();
    if ($payment) {
        $payment->restore();

        // Restore related transaction
        $transaction = Transaction::onlyTrashed()->where('payment_id', $payment->id)->first();
        if ($transaction) {
            $transaction->restore();

            // Restore related transaction items
            TransactionItem::onlyTrashed()->where('transaction_id', $transaction->id)->restore();
        }
    }

    return redirect()->route('accounting.archive')->with('success', 'Request and related records restored successfully.');
}


    // Permanently delete a request
    public function accounting_forceDelete($id)
    {
        $request = PickupRequest::onlyTrashed()->findOrFail($id);
        
        // If there's a proof of payment file, you might want to delete it from storage
        if ($request->proof_of_payment) {
            \Storage::delete($request->proof_of_payment);
        }

        $request->forceDelete();

        return redirect()->route('accounting.archive')->with('success', 'Request permanently deleted.');
    }

}