<x-app-layout>
    <x-slot name="header">
        <div>
            <x-registrar-header />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-8 lg:px-12 xl:px-16">

            <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Pickup Requests</h3>
                </div>

                <!-- Search Bar -->
                <div class="mb-4">
                    <input type="text" id="searchInput" onkeyup="searchTable()"
                        placeholder="Search for pickup requests..." class="w-full rounded border border-gray-300 p-2">
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 bg-white" id="pickupTable">
                        <thead>
                            <tr class="bg-gray-100 text-left text-xs font-semibold uppercase text-gray-600">
                                <th class="border px-4 py-2">Reference No</th>
                                <th class="border px-4 py-2">User</th>
                                <th class="border px-4 py-2">Document</th>
                                <th class="border px-4 py-2">Status</th>
                                <th class="border px-4 py-2">Pickup Date</th>
                                <th class="border px-4 py-2">Payment</th>
                                <th class="border px-4 py-2">Remarks</th>
                                <th class="border px-4 py-2">Verification Status</th>
                                <th class="border px-4 py-2 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $request)
                                <tr class="text-sm">
                                    <td class="border px-4 py-2">
                                        <a href="javascript:void(0)" onclick="showRequestDetails({{ $request->id }})"
                                            class="text-blue-600 hover:underline">
                                            {{ $request->reference_no ?? '—' }}
                                        </a>
                                    </td>
                                    <td class="border px-4 py-2">{{ $request->user->name ?? 'N/A' }}</td>
                                    <td class="border px-4 py-2">{{ $request->document->document_name ?? 'N/A' }}</td>
                                    <td class="border px-4 py-2">{{ ucwords($request->status) }}</td>
                                    <td class="border px-4 py-2">{{ $request->pickup_date ?? '—' }}</td>
                                    <td class="border px-4 py-2">{{ $request->is_paid == 1 ? 'Paid' : 'Unpaid' }}</td>
                                    <td class="border px-4 py-2">{{ ucfirst($request->remarks ?? '---') }}</td>
                                    <td class="border px-4 py-2 text-center">
                                        <span
                                            class="{{ $request->payment && $request->payment->is_verified ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold' }}">
                                            {{ $request->payment && $request->payment->is_verified ? 'Verified' : 'Not Verified' }}
                                        </span>
                                    </td>



                                    <td class="border px-4 py-2">
                                        <div class="flex flex-col items-center justify-center gap-2 sm:flex-row">
                                            <a href="{{ route('pickup_requests.edit', $request->id) }}"
                                                class="flex items-center gap-2 rounded bg-blue-500 px-3 py-1.5 text-white shadow-sm shadow-black transition hover:bg-blue-600">
                                                Update
                                            </a>
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
        const status = row.cells[3].innerText;
        const pickupDate = row.cells[4].innerText;
        const payment = row.cells[5].innerText;
        const remarks = row.cells[6].innerText;
        const verification = row.cells[7].innerText;

        const isVerified = verification.trim().toLowerCase() === 'verified';
        const verifyColor = isVerified ? 'text-green-600' : 'text-red-600';

        Swal.fire({
            title: '<span class="text-xl font-semibold">Confirm Deletion</span>',
            html: `
                <div class="text-md text-left space-y-4">
                    <p class="mb-4">Please review the following details:</p>
                    <div class="grid grid-cols-2 gap-y-2">
                        <div><strong>Reference No:</strong></div><div>${referenceNo}</div>
                        <div><strong>User:</strong></div><div>${user}</div>
                        <div><strong>Document:</strong></div><div>${documentName}</div>
                        <div><strong>Status:</strong></div><div>${status}</div>
                        <div><strong>Pickup Date:</strong></div><div>${pickupDate}</div>
                        <div><strong>Payment:</strong></div><div>${payment}</div>
                        <div><strong>Remarks:</strong></div><div>${remarks}</div>
                        <div><strong>Verification:</strong></div><div class="${verifyColor} font-bold">${verification}</div>
                    </div>
                    <p class="mt-4 font-medium text-red-600 text-center">This action cannot be undone.</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#3b82f6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            background: '#fefefe',
            color: '#1f2937',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + docId).submit();
            }
        });
    }
</script>



<script>
    function showRequestDetails(id) {
        fetch(`/registrar/pickup-requests/${id}`)
            .then(response => response.json())
            .then(data => {
                const referenceNo = data.reference_no;
                const user = data.user?.name ?? 'N/A';
                const documentName = data.document?.document_name ?? 'N/A';
                const status = data.status ?? 'N/A';
                const pickupDate = data.pickup_date ?? 'Not set';
                const payment = data.is_paid == 1 ? 'Paid' : 'Unpaid';
                const remarks = data.remarks ?? 'None';
                const isVerified = data.payment?.is_verified ? 'Verified' : 'Not Verified';

                const verifyColor = data.payment?.is_verified ? 'text-green-600' : 'text-red-600';

                Swal.fire({
                    title: '<span class="text-xl font-semibold">Request Details</span>',
                    html: `
                        <div class="text-md text-left space-y-4">
                            <div class="grid grid-cols-2 gap-y-4">
                                <div><strong>Reference No:</strong></div><div>${referenceNo}</div>
                                <div><strong>User:</strong></div><div>${user}</div>
                                <div><strong>Document:</strong></div><div>${documentName}</div>
                                <div><strong>Status:</strong></div><div>${status}</div>
                                <div><strong>Pickup Date:</strong></div><div>${pickupDate}</div>
                                <div><strong>Payment:</strong></div><div>${payment}</div>
                                <div><strong>Remarks:</strong></div><div>${remarks}</div>
                                <div><strong>Verification Status:</strong></div><div class="${verifyColor} font-bold">${isVerified}</div>
                            </div>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                    background: '#fefefe',
                    color: '#1f2937',
                });
            })
            .catch(error => {
                Swal.fire('Error', 'Could not fetch request details.', 'error');
                console.error(error);
            });
    }
</script>
