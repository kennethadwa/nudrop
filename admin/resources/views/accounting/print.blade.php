<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Transaction Report</title>
        <style>
            body {
                font-family: DejaVu Sans, sans-serif;
                font-size: 12px;
                margin: 20px;
            }

            h2 {
                text-align: center;
                margin-bottom: 30px;
            }

            .report-table {
                width: 100%;
                border-collapse: collapse;
            }

            .report-table th,
            .report-table td {
                border: 1px solid #444;
                padding: 8px 10px;
                text-align: left;
            }

            .report-table th {
                background-color: #f2f2f2;
            }

            .section-title {
                margin-top: 25px;
                margin-bottom: 10px;
                font-weight: bold;
                font-size: 14px;
            }

            .no-border {
                border: none !important;
            }

            .info-table {
                width: 100%;
                border-collapse: collapse;
            }

            .info-table td {
                padding: 4px 8px;
            }

            .info-table td.label {
                font-weight: bold;
                width: 35%;
                vertical-align: top;
            }
        </style>
    </head>

    <body>
        <h2>NU DROP Transaction Report</h2>

        <table class="info-table">
            <tr>
                <td class="label">Transaction No:</td>
                <td>{{ $transaction->transaction_no }}</td>
            </tr>
            <tr>
                <td class="label">Reference No:</td>
                <td>{{ $transaction->pickupRequest->reference_no ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">User:</td>
                <td>{{ $transaction->pickupRequest->user->name ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <td class="label">Pickup Date:</td>
                <td>{{ $transaction->pickupRequest->pickup_date ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Remarks:</td>
                <td>{{ $transaction->pickupRequest->remarks ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Created At:</td>
                <td>{{ $transaction->created_at }}</td>
            </tr>
        </table>

        <p class="section-title">Documents</p>
        <table class="report-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Document Name</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaction->transactionItems as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->document->document_name }}</td>
                        <td>₱{{ number_format($item->amount ?? 0, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center;">No documents found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <p class="section-title">Payment Details</p>
        <table class="info-table">
            <tr>
                <td class="label">Total Amount:</td>
                <td>₱{{ number_format($transaction->amount ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Payment Method:</td>
                <td>{{ $transaction->payment->paymentMethod->name ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Paid At:</td>
                <td>{{ $transaction->payment->paid_at ?? '—' }}</td>
            </tr>
        </table>

        <p class="section-title">Verification</p>
        <table class="info-table">
            <tr>
                <td class="label">Status:</td>
                <td>{{ $transaction->payment->is_verified ? 'Verified' : 'Not Verified' }}</td>
            </tr>
            <tr>
                <td class="label">Remarks:</td>
                <td>{{ $transaction->payment->verification_remarks ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Verified By:</td>
                <td>{{ $transaction->verifier->name ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Verified At:</td>
                <td>{{ $transaction->verified_at ?? '—' }}</td>
            </tr>
        </table>
    </body>

</html>
