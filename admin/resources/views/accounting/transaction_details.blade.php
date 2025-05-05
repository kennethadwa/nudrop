<x-app-layout>
    <x-slot name="header">
        <div>
            <x-accounting-header />
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="rounded bg-white p-6 shadow">
                <h2 class="mb-4 text-xl font-semibold">Transaction Details</h2>

                <div class="mb-4">
                    <strong>Transaction No:</strong> {{ $transaction->transaction_no }}<br>
                    <strong>Pickup Request:</strong> {{ $transaction->pickupRequest->id }} —
                    {{ $transaction->pickupRequest->student->full_name ?? 'Unknown' }}<br>
                    <strong>Payment Reference:</strong> {{ $transaction->payment->reference_no ?? 'N/A' }}<br>
                    <strong>Payment Total:</strong> ₱{{ number_format($transaction->payment->amount, 2) }}<br>
                    <strong>Verified By:</strong> {{ $transaction->verifier->full_name ?? 'Not yet verified' }}<br>
                    <strong>Completed At:</strong> {{ $transaction->completed_at ?? 'Not completed yet' }}
                </div>

                <hr class="my-4">

                <h3 class="mb-2 text-lg font-semibold">Documents Requested</h3>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-4 py-2 text-left">Document</th>
                                <th class="border px-4 py-2 text-left">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaction->transactionItems as $item)
                                <tr>
                                    <td class="border px-4 py-2">{{ $item->document->name ?? 'Unknown Document' }}</td>
                                    <td class="border px-4 py-2">₱{{ number_format($item->amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    <a href="{{ route('accounting.transactions.index') }}"
                        class="rounded bg-gray-600 px-4 py-2 text-white hover:bg-gray-700">
                        ← Back to Transactions
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
