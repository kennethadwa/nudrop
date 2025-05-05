<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Transaction Report</title>
        <style>
            body {
                font-family: DejaVu Sans, sans-serif;
                font-size: 12px;
            }

            h2 {
                margin-bottom: 20px;
            }

            p {
                margin: 2px 0;
            }
        </style>
    </head>

    <body>
        <h2>Transaction Report</h2>

        <p><strong>Transaction No:</strong> {{ $transaction->transaction_no }}</p>
        <p><strong>Reference No:</strong> {{ $transaction->pickupRequest->reference_no ?? 'N/A' }}</p>
        <p><strong>User:</strong> {{ $transaction->pickupRequest->user->name ?? 'Unknown' }}</p>
        <p><strong>Documents:</strong>
            {{ $transaction->transactionItems->pluck('document.document_name')->join(', ') }}
        </p>
        <p><strong>Amount:</strong> ₱{{ number_format($transaction->amount ?? 0, 2) }}</p>
        <p><strong>Payment Method:</strong> {{ $transaction->payment->paymentMethod->method ?? '—' }}</p>
        <p><strong>Paid At:</strong> {{ $transaction->payment->paid_at ?? '—' }}</p>
        <p><strong>Pickup Date:</strong> {{ $transaction->pickupRequest->pickup_date ?? '—' }}</p>
        <p><strong>Remarks:</strong> {{ $transaction->pickupRequest->remarks ?? '—' }}</p>
        <p><strong>Verification Status:</strong>
            {{ $transaction->payment->is_verified ? 'Verified' : 'Not Verified' }}
        </p>
        <p><strong>Verification Remarks:</strong> {{ $transaction->payment->verification_remarks ?? '—' }}</p>
        <p><strong>Verified By:</strong> {{ $transaction->verifier->name ?? '—' }}</p>
        <p><strong>Verified At:</strong> {{ $transaction->verified_at ?? '—' }}</p>
        <p><strong>Created At:</strong> {{ $transaction->created_at }}</p>
    </body>

</html>
