<x-app-layout>
    <x-slot name="header">
        <div>
            <x-accounting-header />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-8 lg:px-12 xl:px-16">
            <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                <h2 class="mb-4 text-xl font-semibold">Archived Pick-Up Requests</h2>

                @if ($archivedRequests->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm text-gray-700">
                            <thead class="bg-gray-100 uppercase text-gray-700">
                                <tr>
                                    <th class="px-4 py-2">Reference No</th>
                                    <th class="px-4 py-2">User</th>
                                    <th class="px-4 py-2">Document</th>
                                    <th class="px-4 py-2">Pickup Date</th>
                                    <th class="px-4 py-2">Remarks</th>
                                    <th class="px-4 py-2">Amount</th>
                                    <th class="px-4 py-2">Payment Method</th>
                                    <th class="px-4 py-2">Verification Status</th>
                                    <th class="px-4 py-2">Proof of Payment</th>
                                    <th class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($archivedRequests as $request)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $request->reference_no }}</td>
                                        <td class="px-4 py-2">{{ $request->user->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-2">{{ $request->document->document_name ?? 'N/A' }}</td>
                                        <td class="px-4 py-2">{{ $request->pickup_date }}</td>
                                        <td class="px-4 py-2">{{ $request->remarks ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2">
                                            {{ $request->payments->first()->amount ? number_format($request->payments->first()->amount, 2) : '—' }}
                                        </td>
                                        <td class="px-4 py-2">
                                            {{ $request->payments->first()->payment_method->name ?? 'N/A' }}</td>
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
                                        <td class="border px-4 py-2">
                                            @if ($payment->proof_of_payment)
                                                <a href="{{ asset('storage/' . $payment->proof_of_payment) }}"
                                                    class="text-blue-600 hover:underline" target="_blank">View File</a>
                                            @else
                                                <span class="italic text-gray-500">No file uploaded</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2">
                                            <div class="flex gap-2">
                                                <form action="{{ route('accounting.restore', $request->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirmAction(this, 'restore', {
        reference_no: '{{ $request->reference_no }}',
        user: '{{ $request->user->name ?? 'N/A' }}',
        document: '{{ $request->document->document_name ?? 'N/A' }}',
        pickup_date: '{{ $request->pickup_date }}',
        remarks: '{{ $request->remarks ?? 'N/A' }}',
        amount: '{{ $request->payments->first()->amount ? number_format($request->payments->first()->amount, 2) : '—' }}',
        payment_method: '{{ $request->payments->first()->payment_method->name ?? 'N/A' }}',
        verification: '{{ $request->payments->first() && $request->payments->first()->is_verified ? 'Verified' : 'Not Verified' }}'
    });">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="rounded bg-green-500 p-2 text-white hover:bg-green-600">
                                                        Restore
                                                    </button>
                                                </form>
                                                <form action="{{ route('accounting.forceDelete', $request->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirmAction(this, 'delete', {
        reference_no: '{{ $request->reference_no }}',
        user: '{{ $request->user->name ?? 'N/A' }}',
        document: '{{ $request->document->document_name ?? 'N/A' }}',
        pickup_date: '{{ $request->pickup_date }}',
        remarks: '{{ $request->remarks ?? 'N/A' }}',
        amount: '{{ $request->payments->first()->amount ? number_format($request->payments->first()->amount, 2) : '—' }}',
        payment_method: '{{ $request->payments->first()->payment_method->name ?? 'N/A' }}',
        verification: '{{ $request->payments->first() && $request->payments->first()->is_verified ? 'Verified' : 'Not Verified' }}'
    });">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="rounded bg-red-500 p-2 text-white hover:bg-red-600">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $archivedRequests->links() }}
                    </div>
                @else
                    <p class="text-gray-500">No archived pickup requests found.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    function confirmAction(form, actionType, requestData) {
        const verifyStatus = requestData.verification.trim().toLowerCase();
        const isVerified = verifyStatus === '1' || verifyStatus === 'verified';
        const verifyColor = isVerified ? 'green' : 'red';

        Swal.fire({
            title: `Are you sure you want to ${actionType} this request?`,
            html: `
                <table style="width:100%;text-align:left;border-collapse:collapse;">
                    <tr><td style="padding:10px 0;"><strong>Reference No:</strong></td><td>${requestData.reference_no}</td></tr>
                    <tr><td style="padding:10px 0;"><strong>User:</strong></td><td>${requestData.user}</td></tr>
                    <tr><td style="padding:10px 0;"><strong>Document:</strong></td><td>${requestData.document}</td></tr>
                    <tr><td style="padding:10px 0;"><strong>Pickup Date:</strong></td><td>${requestData.pickup_date}</td></tr>
                    <tr><td style="padding:10px 0;"><strong>Remarks:</strong></td><td>${requestData.remarks}</td></tr>
                    <tr><td style="padding:10px 0;"><strong>Amount:</strong></td><td>${requestData.amount}</td></tr>
                    <tr><td style="padding:10px 0;"><strong>Payment Method:</strong></td><td>${requestData.payment_method}</td></tr>
                    <tr><td style="padding:10px 0;"><strong>Verification:</strong></td><td><span style="color:${verifyColor};font-weight:bold;">${requestData.verification}</span></td></tr>
                </table>
            `,
            icon: actionType === 'restore' ? 'question' : 'warning',
            showCancelButton: true,
            confirmButtonColor: actionType === 'restore' ? '#28a745' : '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, ${actionType}`,
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });

        return false; // prevent default submit
    }
</script>
