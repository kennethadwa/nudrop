<x-app-layout>
    <x-slot name="header">
        <x-accounting-header />
    </x-slot>

    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-8 lg:px-12 xl:px-16">
            <div class="rounded-lg bg-white p-6 shadow">
                <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-xl font-bold text-gray-800">Transaction Reports</h2>
                    <a href="{{ route('accounting.pickup_requests.create') }}"
                        class="inline-block rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
                        New Transaction
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="mb-4">
                    <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for transaction.."
                        class="w-full rounded border border-gray-300 p-2">
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table id="pickupTable" class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-600">
                            <tr>
                                <th class="px-4 py-2">Transaction No</th>
                                <th class="px-4 py-2">Reference No</th>
                                <th class="px-4 py-2">User</th>
                                <th class="px-4 py-2">Documents</th>
                                <th class="px-4 py-2">Amount</th>
                                <th class="px-4 py-2">Verified By</th>
                                <th class="px-4 py-2">Verified At</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($transactions as $transaction)
                                <tr class="transition hover:bg-gray-50">
                                    <td class="px-4 py-3 font-mono text-blue-600">
                                        <a href="javascript:void(0);"
                                            onclick="showTransactionDetails({{ $transaction->id }})">
                                            {{ $transaction->transaction_no }}
                                        </a>
                                    </td>
                                    <td class="border px-4 py-3 font-mono text-blue-600">
                                        <a href="javascript:void(0);"
                                            onclick="showTransactionDetails({{ $transaction->id }})">
                                            {{ $transaction->pickupRequest->reference_no ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td class="border px-4 py-3">
                                        {{ $transaction->pickupRequest->user->name ?? 'Unknown' }}
                                    </td>
                                    <td class="border px-4 py-3">
                                        @if ($transaction->pickupRequest && $transaction->pickupRequest->document)
                                            <div>{{ $transaction->pickupRequest->document->document_name }}</div>
                                        @else
                                            <div>No document available</div>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-3">₱{{ number_format($transaction->amount ?? 0, 2) }}</td>
                                    <td class="border px-4 py-3">{{ $transaction->verifier->name ?? '—' }}</td>
                                    <td class="border px-4 py-3">{{ $transaction->verified_at ?? '—' }}</td>
                                    <td class="border px-4 py-3">
                                        <a href="{{ route('transaction.print', $transaction->id) }}"
                                            class="rounded-sm bg-indigo-700 px-5 py-3 text-xs font-medium text-white hover:bg-indigo-800">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-6 text-center text-gray-500">No transactions
                                        found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-6">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function searchTable() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById('searchInput');
            filter = input.value.toUpperCase();
            table = document.getElementById('pickupTable');
            tr = table.getElementsByTagName('tr');

            for (i = 1; i < tr.length; i++) {
                tr[i].style.display = 'none';
                td = tr[i].getElementsByTagName('td');
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = '';
                            break;
                        }
                    }
                }
            }
        }
    </script>
</x-app-layout>

<script>
    function showTransactionDetails(transactionId) {
        // Make an AJAX request to get the transaction details based on the ID
        fetch(`/transaction-details/${transactionId}`)
            .then(response => response.json())
            .then(data => {
                // Show SweetAlert with transaction details and table format
                Swal.fire({
                    title: 'Transaction Details',
                    html: `
                        <table style="width:100%; text-align:left; border-collapse:collapse;">
                            <tr><td style="padding:10px 0;"><strong>Transaction No:</strong></td><td>${data.transaction_no}</td></tr>
                            <tr><td style="padding:10px 0;"><strong>Reference No:</strong></td><td>${data.reference_no}</td></tr>
                            <tr><td style="padding:10px 0;"><strong>User:</strong></td><td>${data.user_name}</td></tr>
                            <tr><td style="padding:10px 0;"><strong>Documents:</strong></td><td>${data.document_name}</td></tr>
                            <tr><td style="padding:10px 0;"><strong>Amount:</strong></td><td>₱${data.amount}</td></tr>
                            <tr><td style="padding:10px 0;"><strong>Verified By:</strong></td><td>${data.verified_by}</td></tr>
                            <tr><td style="padding:10px 0;"><strong>Verified At:</strong></td><td>${data.verified_at}</td></tr>
                        </table>
                    `,
                    icon: 'info',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'bg-white shadow-xl rounded-lg p-6 max-w-lg mx-auto',
                        title: 'text-xl font-semibold text-gray-900 mb-4',
                        content: 'text-gray-700',
                        confirmButton: 'bg-blue-500 text-white hover:bg-blue-600 rounded-lg px-6 py-2 text-sm'
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching transaction details:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Could not fetch transaction details.',
                    icon: 'error',
                    confirmButtonText: 'Close',
                    customClass: {
                        confirmButton: 'bg-red-500 text-white hover:bg-red-600 rounded-lg px-6 py-2 text-sm'
                    }
                });
            });
    }
</script>
