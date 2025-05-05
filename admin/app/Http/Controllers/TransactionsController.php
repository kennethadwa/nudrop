<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionsController extends Controller
{
    public function index()
{
    $transactions = Transaction::with([
        'pickupRequest.user',
        'pickupRequest.document',
        'verifier'
    ])->paginate(10);

    return view('accounting.transactions', compact('transactions'));
}


    public function store(Request $request)
    {
        $request->validate([
            'pickup_request_id' => 'required|exists:pickup_requests,id',
            'items' => 'required|array',
            'items.*.document_id' => 'required|exists:document,id',
            'items.*.amount' => 'required|numeric|min:0',
        ]);

        $totalAmount = collect($request->items)->sum('amount');

        $transaction = Transaction::create([
            'pickup_request_id' => $request->pickup_request_id,
            'amount' => $totalAmount,
            'transaction_date' => now(),
            'verified_by' => auth('staff')->id(),
            'verified_at' => now(),
            'transaction_no' => 'TXN-' . strtoupper(Str::random(10)),
        ]);

        foreach ($request->items as $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'document_id' => $item['document_id'],
                'amount' => $item['amount'],
            ]);
        }

        return redirect()->route('accounting.transactions')->with('success', 'Transaction recorded successfully.');
    }

    public function show($id)
    {
        $transaction = Transaction::with([
            'pickupRequest.user',
            'pickupRequest.document',
            'verifier',
            'transactionItems.document'
        ])->findOrFail($id);

        return view('accounting.transaction_details', compact('transaction'));
    }

    // Optional: Add a print method if you're linking to it
    public function print($id)
{
    $transaction = Transaction::with([
        'pickupRequest.user',
        'pickupRequest.document',
        'verifier',
        'transactionItems.document',
        'payment.paymentMethod'
    ])->findOrFail($id);

    $pdf = Pdf::loadView('accounting.print', compact('transaction'));

    return $pdf->download('transaction-report.pdf');
}

public function showTransactionDetails($id)
{
    $transaction = Transaction::with('pickupRequest', 'pickupRequest.user', 'pickupRequest.document', 'verifier')
        ->findOrFail($id);

    return response()->json([
        'transaction_no' => $transaction->transaction_no,
        'reference_no' => $transaction->pickupRequest->reference_no ?? 'N/A',
        'user_name' => $transaction->pickupRequest->user->name ?? 'Unknown',
        'document_name' => $transaction->pickupRequest->document->document_name ?? 'No document available',
        'amount' => number_format($transaction->amount, 2),
        'verified_by' => $transaction->verifier->name ?? '—',
        'verified_at' => $transaction->verified_at ?? '—'
    ]);
}


}
