<x-app-layout>
    <x-slot name="header">
        <div>
            <x-accounting-header />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-8 lg:px-12 xl:px-16">
            <div class="overflow-hidden bg-white p-6 shadow-sm shadow-gray-500 sm:rounded-lg">
                <h3 class="mb-4 text-lg font-semibold">New Pickup Request</h3>

                <form id="pickup-request-form" action="{{ route('accounting.pickup_requests.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="flex flex-col gap-4 lg:flex-row">
                        <!-- Pickup Request Form -->
                        <div class="w-full lg:w-1/2">
                            <!-- User -->
                            <div class="mb-4">
                                <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                                <select id="user_id" name="user_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 text-black shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Document -->
                            <div class="mb-4">
                                <label for="document_id"
                                    class="block text-sm font-medium text-gray-700">Document</label>
                                <select id="document_id" name="document_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    @foreach ($documents as $document)
                                        <option value="{{ $document->id }}" data-fee="{{ $document->fee }}">
                                            {{ $document->document_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Pickup Date -->
                            <div class="mb-4">
                                <label for="pickup_date" class="block text-sm font-medium text-gray-700">Pickup
                                    Date</label>
                                <input type="date" id="pickup_date" name="pickup_date"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                                    required>
                            </div>

                            <!-- Remarks -->
                            <div class="mb-4">
                                <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                                <textarea id="remarks" name="remarks" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                                    placeholder="Enter any additional notes...">{{ old('remarks') }}</textarea>
                            </div>

                            <!-- Request Type -->
                            <div class="mb-4">
                                <label for="request_type" class="block text-sm font-medium text-gray-700">Request
                                    Type</label>
                                <input type="text" id="request_type" name="request_type" value="pickup"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                                    readonly>
                            </div>
                        </div>

                        <!-- Payment Form -->
                        <div class="w-full lg:w-1/2">
                            <!-- Amount -->
                            <div class="mb-4">
                                <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                                <input type="number" id="amount" name="amount" step="0.01" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                            </div>

                            <!-- Payment Method -->
                            <div class="mb-4">
                                <label for="payment_method_id" class="block text-sm font-medium text-gray-700">Payment
                                    Method</label>
                                <select id="payment_method_id" name="payment_method_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    @foreach ($paymentMethods as $paymentMethod)
                                        <option value="{{ $paymentMethod->id }}"
                                            {{ old('payment_method_id', isset($pickupRequest) ? $pickupRequest->payment_method_id : '') == $paymentMethod->id ? 'selected' : '' }}>
                                            {{ $paymentMethod->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>



                            <!-- Is Verified -->
                            <div class="mb-4">
                                <label for="is_verified" class="block text-sm font-medium text-gray-700">Verification
                                    Status</label>
                                <select id="is_verified" name="is_verified"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    <option value="0" selected>Not Verified</option>
                                    <option value="1">Verified</option>
                                </select>
                            </div>

                            <!-- Verification Remarks -->
                            <div class="mb-4">
                                <label for="verification_remarks"
                                    class="block text-sm font-medium text-gray-700">Verification Remarks</label>
                                <textarea id="verification_remarks" name="verification_remarks" rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                                    placeholder="Leave remarks for verification..."></textarea>
                            </div>

                            <!-- Proof of Payment -->
                            <div class="mb-4">
                                <label for="proof_of_payment" class="block text-sm font-medium text-gray-700">Proof of
                                    Payment</label>
                                <input type="file" id="proof_of_payment" name="proof_of_payment"
                                    accept="image/jpeg,image/png,application/pdf"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                            class="rounded bg-green-500 px-6 py-2 text-white shadow-sm shadow-gray-900 hover:bg-green-600">Create
                            Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const documentSelect = document.getElementById('document_id');
        const amountInput = document.getElementById('amount');

        documentSelect.addEventListener('change', function() {
            const selectedOption = documentSelect.options[documentSelect.selectedIndex];
            const fee = selectedOption.getAttribute('data-fee');
            amountInput.value = fee || '';
        });

        // Auto-fill fee if pre-selected
        documentSelect.dispatchEvent(new Event('change'));
    });
</script>


<script>
    document.getElementById('pickup-request-form').addEventListener('submit', function(e) {
        e.preventDefault(); // prevent immediate form submission

        const form = this;

        // Collect values
        const user = form.user_id.options[form.user_id.selectedIndex].text;
        const documentName = form.document_id.options[form.document_id.selectedIndex].text;
        const pickupDate = form.pickup_date.value;
        const remarks = form.remarks.value || "None";
        const amount = form.amount.value;
        const paymentMethod = form.payment_method_id.options[form.payment_method_id.selectedIndex].text;
        const isVerified = form.is_verified.options[form.is_verified.selectedIndex].text;
        const verificationRemarks = form.verification_remarks.value || "None";

        // Show SweetAlert confirmation
        Swal.fire({
            title: 'Confirm Request Submission',
            html: `
                <div style="overflow-x:auto">
                    <table style="width:100%;border-collapse:collapse;text-align:left;">
                        <tr>
                            <th style="padding:8px;border-bottom:1px solid #ddd;">Field</th>
                            <th style="padding:8px;border-bottom:1px solid #ddd;">Details</th>
                        </tr>
                        <tr><td style="padding:8px;">User</td><td style="padding:8px;">${user}</td></tr>
                        <tr><td style="padding:8px;">Document</td><td style="padding:8px;">${documentName}</td></tr>
                        <tr><td style="padding:8px;">Pickup Date</td><td style="padding:8px;">${pickupDate}</td></tr>
                        <tr><td style="padding:8px;">Remarks</td><td style="padding:8px;">${remarks}</td></tr>
                        <tr><td style="padding:8px;">Amount</td><td style="padding:8px;">₱${amount}</td></tr>
                        <tr><td style="padding:8px;">Payment Method</td><td style="padding:8px;">${paymentMethod}</td></tr>
                        <tr><td style="padding:8px;">Verified</td><td style="padding:8px;">${isVerified}</td></tr>
                        <tr><td style="padding:8px;">Verification Remarks</td><td style="padding:8px;">${verificationRemarks}</td></tr>
                    </table>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-check"></i> Yes, Create Request',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'swal-wide',
                confirmButton: 'bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded',
                cancelButton: 'bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
