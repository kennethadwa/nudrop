<x-app-layout>
    <x-slot name="header">
        <div>
            <x-accounting-header />
        </div>
    </x-slot>

    <div class="bg-gray-100 py-12">
        <div class="max-w-9xl mx-auto sm:px-8 lg:px-12 xl:px-16">

            <div class="flex flex-col gap-4 md:flex-row">
                <!-- Pickup Request Info -->
                <div class="flex-1 rounded bg-gray-100 p-4">
                    <h2 class="mb-6 border-b pb-2 text-2xl font-semibold text-gray-800">Pickup Request Details</h2>
                    <div class="overflow-x-auto">
                        <table class="mb-8 w-full table-auto border border-gray-300">
                            <tbody class="divide-y divide-gray-300">
                                <tr>
                                    <td class="w-1/3 p-3 font-medium text-gray-700">Reference No</td>
                                    <td class="p-3">{{ $pickupRequest->reference_no }}</td>
                                </tr>
                                <tr>
                                    <td class="p-3 font-medium text-gray-700">User</td>
                                    <td class="p-3">{{ $pickupRequest->user->name }}</td>
                                </tr>
                                <tr>
                                    <td class="p-3 font-medium text-gray-700">Document</td>
                                    <td class="p-3">{{ $pickupRequest->document->document_name }}</td>
                                </tr>
                                <tr>
                                    <td class="p-3 font-medium text-gray-700">Pickup Date</td>
                                    <td class="p-3">{{ $pickupRequest->pickup_date }}</td>
                                </tr>
                                <tr>
                                    <td class="p-3 font-medium text-gray-700">Remarks</td>
                                    <td class="p-3">{{ $pickupRequest->remarks ?? 'None' }}</td>
                                </tr>
                                <tr>
                                    <td class="p-3 font-medium text-gray-700">Status</td>
                                    <td class="p-3">{{ $pickupRequest->status }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="flex-1 rounded bg-gray-100 p-4">
                    <h2 class="mb-6 border-b pb-2 text-2xl font-semibold text-gray-800">
                        Payment Details</h2>
                    @php $payment = $pickupRequest->payments->first(); @endphp
                    @if ($payment)
                        <div class="overflow-x-auto">
                            <table class="mb-6 w-full table-auto border border-gray-300">
                                <tbody class="divide-y divide-gray-300">
                                    <tr>
                                        <td class="w-1/3 p-3 font-medium text-gray-700">Request Type</td>
                                        <td class="p-3">{{ $payment->request_type }}</td>
                                    </tr>
                                    <tr>
                                        <td class="p-3 font-medium text-gray-700">Amount</td>
                                        <td class="p-3">₱{{ number_format($payment->amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="p-3 font-medium text-gray-700">Payment Method</td>
                                        <td class="p-3">{{ $payment->payment_method->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="p-3 font-medium text-gray-700">Paid At</td>
                                        <td class="p-3">{{ $payment->paid_at }}</td>
                                    </tr>
                                    <tr>
                                        <td class="p-3 font-medium text-gray-700">Verification Remarks</td>
                                        <td class="p-3">{{ $payment->verification_remarks ?? 'None' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="p-3 font-medium text-gray-700">Proof of Payment</td>
                                        <td class="p-3">
                                            @if ($payment->proof_of_payment)
                                                <a href="{{ asset('storage/' . $payment->proof_of_payment) }}"
                                                    class="text-blue-600 hover:underline" target="_blank">View File</a>
                                            @else
                                                <span class="italic text-gray-500">No file uploaded</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="italic text-gray-600">No payment details found.</p>
                    @endif
                </div>

            </div>
            <!-- Verification Form -->
            <form action="{{ route('accounting.verify', $pickupRequest->payments->first()->id) }}" method="POST"
                class="space-y-4">
                @csrf
                @method('PATCH')
                <div class="flex justify-end">
                    <div class="mt-5 w-full max-w-xs">
                        <label for="is_verified" class="mb-1 block font-medium text-gray-700">Verification
                            Status</label>
                        <select name="is_verified" id="is_verified"
                            class="w-full rounded border-gray-300 shadow-sm shadow-gray-500 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="0" {{ $payment && $payment->is_verified == 0 ? 'selected' : '' }}>Not
                                Verified</option>
                            <option value="1" {{ $payment && $payment->is_verified == 1 ? 'selected' : '' }}>
                                Verified
                            </option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-end">
                    <button type="submit"
                        class="rounded bg-green-600 px-4 py-2 text-white shadow-sm shadow-gray-900 transition hover:bg-green-700">
                        Submit Verification
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
