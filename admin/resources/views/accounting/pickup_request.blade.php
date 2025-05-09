<x-app-layout>
    <x-slot name="header">
        <div>
            <x-accounting-header />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-8 lg:px-12 xl:px-16">

            <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Pickup Requests</h3>
                    <a href="{{ route('accounting.pickup_requests.create') }}"
                        class="rounded bg-blue-600 px-4 py-2 text-sm text-white shadow-sm shadow-black hover:bg-blue-700">New
                        Request</a>
                </div>

                <!-- Search Bar -->
                <div class="mb-4">
                    <input type="text" id="searchInput" onkeyup="searchTable()"
                        placeholder="Search for paid requests..." class="w-full rounded border border-gray-300 p-2">
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 bg-white" id="pickupTable">
                        <thead>
                            <tr class="bg-gray-100 text-left text-xs font-semibold uppercase text-gray-600">
                                <th class="border px-4 py-2">Reference No</th>
                                <th class="border px-4 py-2">User</th>
                                <th class="border px-4 py-2">Document</th>
                                <th class="border px-4 py-2">Amount</th>
                                <th class="border px-4 py-2">Payment Method</th>
                                <th class="border px-4 py-2">Paid At</th>
                                <th class="border px-4 py-2">Verification Status</th>
                                <th class="border px-4 py-2 text-center">Actions</th>
                            </tr>
                            <tr>
                                <!-- One search box per column (skip 'Actions') -->
                                <th class="border px-2 py-1"><input type="text" onkeyup="columnSearch(0)"
                                        placeholder="Search Ref#" class="w-full border p-1 text-xs" /></th>
                                <th class="border px-2 py-1"><input type="text" onkeyup="columnSearch(1)"
                                        placeholder="Search User" class="w-full border p-1 text-xs" /></th>
                                <th class="border px-2 py-1"><input type="text" onkeyup="columnSearch(2)"
                                        placeholder="Search Doc" class="w-full border p-1 text-xs" /></th>
                                <th class="border px-2 py-1"><input type="text" onkeyup="columnSearch(3)"
                                        placeholder="Search Amount" class="w-full border p-1 text-xs" /></th>
                                <th class="border px-2 py-1"><input type="text" onkeyup="columnSearch(4)"
                                        placeholder="Search Method" class="w-full border p-1 text-xs" /></th>
                                <th class="border px-2 py-1"><input type="text" onkeyup="columnSearch(5)"
                                        placeholder="Search Date" class="w-full border p-1 text-xs" /></th>
                                <th class="border px-2 py-1"><input type="text" onkeyup="columnSearch(6)"
                                        placeholder="Search Status" class="w-full border p-1 text-xs" /></th>
                                <th class="border px-2 py-1"></th>
                            </tr>
                        </thead>


                        <tbody>
                            @forelse($requests as $request)
                                <tr class="text-sm" data-id="{{ $request->id }}">
                                    <td class="border px-4 py-2">
                                        <a href="javascript:void(0);" class="text-blue-600"
                                            onclick="showRequestDetails({{ $request->id }})">
                                            {{ $request->reference_no ?? '—' }}
                                        </a>
                                    </td>
                                    <td class="border px-4 py-2">{{ $request->user->name ?? 'N/A' }}</td>
                                    <td class="border px-4 py-2">{{ $request->document->document_name ?? 'N/A' }}</td>
                                    <td class="border px-4 py-2">
                                        {{ $request->payments->first()->amount ? number_format($request->payments->first()->amount, 2) : '—' }}
                                    </td>
                                    <td class="border px-4 py-2">
                                        @if ($request->payments->first()->payment_method->name == 'Gcash')
                                            <img src="{{ asset('images/gcash.png') }}" alt="Gcash logo"
                                                class="inline-block h-8 w-10 object-cover">
                                        @elseif($request->payments->first()->payment_method->name == 'Paymaya')
                                            <img src="{{ asset('images/paymaya.png') }}" alt="Paymaya logo"
                                                class="inline-block h-8 w-10 object-cover">
                                        @else
                                            <!-- No image for other payment methods -->
                                        @endif
                                        {{ $request->payments->first()->payment_method->name ?? 'N/A' }}
                                    </td>


                                    <td class="border px-4 py-2">
                                        {{ $request->payments->first()->paid_at ? \Carbon\Carbon::parse($request->payments->first()->paid_at)->format('Y-m-d h:i A') : '—' }}
                                    </td>

                                    <td class="border px-4 py-2">
                                        @php
                                            $payment = $request->payments->first();
                                        @endphp
                                        @if ($payment && $payment->is_verified)
                                            <span class="font-semibold text-green-600">
                                                <i class="fas fa-check mr-1"></i> Verified
                                            </span>
                                        @else
                                            <span class="font-semibold text-red-500">
                                                <i class="fas fa-times mr-1"></i> Not Verified
                                            </span>
                                        @endif
                                    </td>


                                    <td class="border px-4 py-2 text-center">
                                        <div class="flex flex-col items-center justify-center gap-2 sm:flex-row">

                                            <!-- View Button -->
                                            <a href="{{ route('accounting.pickup_requests.show', $request->id) }}"
                                                class="flex items-center gap-2 rounded bg-green-500 px-3 py-1.5 text-white shadow-sm shadow-black transition hover:bg-green-600">
                                                Verify
                                            </a>


                                            <!-- Edit Button -->
                                            <a href="{{ route('accounting.pickup_requests.edit', $request->id) }}"
                                                class="flex items-center gap-2 rounded bg-blue-500 px-3 py-1.5 text-white shadow-sm shadow-black transition hover:bg-blue-600">
                                                Edit
                                            </a>

                                            <!-- Delete Button -->
                                            <form id="delete-form-{{ $request->id }}"
                                                action="{{ route('accounting.destroy', $request->id) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete({{ $request->id }})"
                                                    class="flex items-center gap-2 rounded bg-red-500 px-3 py-1.5 text-white shadow-sm shadow-black transition hover:bg-red-600">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="8" class="border px-4 py-2 text-center text-gray-500">No pickup
                                        requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-6">
                        {{ $requests->links() }}
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


@if (session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            background: '#35408F',
            color: '#fff',
            text: @json(session('success')),
            confirmButtonColor: '#11CB65',
            confirmButtonText: 'OK'
        });
    </script>
@endif


<script>
    function confirmDelete(docId) {
        const row = document.querySelector(`#delete-form-${docId}`).closest('tr');
        const referenceNo = row.cells[0].innerText;
        const user = row.cells[1].innerText;
        const documentName = row.cells[2].innerText;
        const amount = row.cells[3].innerText;
        const paymentMethod = row.cells[4].innerText;
        const paidAt = row.cells[5].innerText;
        const verify = row.cells[6].innerText;

        const verifyStatus = verify.trim().toLowerCase();
        const isVerified = verifyStatus === '1' || verifyStatus === 'true' || verifyStatus === 'verified';
        const verifyColor = isVerified ? 'green' : 'red';

        Swal.fire({
            title: 'Confirm Deletion',
            html: `
                <p class="mb-5">Please review the following details:</p>
                <table style="width:100%;text-align:left;border-collapse:collapse;">
                    <tr><td style="padding:10px 0;"><strong>Reference No:</strong></td><td>${referenceNo}</td></tr>
                    <tr><td style="padding:10px 0;"><strong>User:</strong></td><td>${user}</td></tr>
                    <tr><td style="padding:10px 0;"><strong>Document:</strong></td><td>${documentName}</td></tr>
                    <tr><td style="padding:10px 0;"><strong>Amount:</strong></td><td>${amount}</td></tr>
                    <tr><td style="padding:10px 0;"><strong>Payment Method:</strong></td><td>${paymentMethod}</td></tr>
                    <tr><td style="padding:10px 0;"><strong>Paid At:</strong></td><td>${paidAt}</td></tr>
                    <tr><td style="padding:10px 0;"><strong>Verification:</strong></td><td><span style="color:${verifyColor};font-weight:bold;">${verify}</span></td></tr>
                </table>
                <br><strong class="text-red-600">This action cannot be undone.</strong>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            background: '#fefefe',
            color: '#333',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + docId).submit();
            }
        });
    }
</script>



<script>
    function showRequestDetails(requestId) {
        const row = document.querySelector(`#pickupTable tr[data-id='${requestId}']`);

        const referenceNo = row.querySelector('td:nth-child(1)').innerText;
        const user = row.querySelector('td:nth-child(2)').innerText;
        const documentName = row.querySelector('td:nth-child(3)').innerText;
        const amount = row.querySelector('td:nth-child(4)').innerText;
        const paymentMethod = row.querySelector('td:nth-child(5)').innerText;
        const paidAt = row.querySelector('td:nth-child(6)').innerText;
        const verify = row.querySelector('td:nth-child(7)').innerText;


        const verifyStatus = verify.trim().toLowerCase();
        const isVerified = verifyStatus === '1' || verifyStatus === 'verified';
        const verifyColor = isVerified ? 'green' : 'red';

        Swal.fire({
            title: 'Request Details',
            html: `
                <table style="width:100%;text-align:left;border-collapse:collapse;">
                    <tr><td style="padding:10px 0;"><strong>Reference No:</strong></td><td>${referenceNo}</td></tr>
                    <tr><td style="padding:10px 0;"><strong>User:</strong></td><td>${user}</td></tr>
                    <tr><td style="padding:10px 0;"><strong>Document:</strong></td><td>${documentName}</td></tr>
                    <tr><td style="padding:10px 0;"><strong>Amount:</strong></td><td>${amount}</td></tr>
                    <tr><td style="padding:10px 0;"><strong>Payment Method:</strong></td><td>${paymentMethod}</td></tr>
                    <tr><td style="padding:10px 0;"><strong>Paid At:</strong></td><td>${paidAt}</td></tr>
                    <tr><td style="padding:10px 0;"><strong>Verification:</strong></td><td><span style="color:${verifyColor};font-weight:bold;">${verify}</span></td></tr>
                </table>
            `,
            icon: 'info',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK',
        });
    }
</script>


<script>
    function columnSearch(colIndex) {
        var input, filter, table, tr, td, i, txtValue;
        table = document.getElementById("pickupTable");
        tr = table.getElementsByTagName("tr");
        input = tr[1].getElementsByTagName("input")[colIndex]; // 2nd row inputs
        filter = input.value.toUpperCase();

        for (i = 2; i < tr.length; i++) { // start from third row (index 2)
            td = tr[i].getElementsByTagName("td")[colIndex];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
