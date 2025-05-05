<x-app-layout>
    <x-slot name="header">
        <div>
            <x-accounting-header />
        </div>
    </x-slot>

    <div class="bg-gray-100 py-12">
        <div class="max-w-9xl mx-auto sm:px-8 lg:px-12 xl:px-16">
            <div class="rounded-2xl bg-white p-8 shadow-lg">

                <div class="flex justify-start">
                    <h2 class="mb-4 text-xl font-semibold text-gray-900">Request Information</h2>
                </div>

                <form action="{{ route('accounting.pickup_requests.update', $pickupRequest->id) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        <!-- User -->
                        <div>
                            <label for="user_id" class="block font-medium text-gray-700">User</label>
                            <select name="user_id" id="user_id" class="mt-1 block w-full rounded border-gray-300">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ $pickupRequest->user_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Document -->
                        <div>
                            <label for="document_id" class="block font-medium text-gray-700">Document</label>
                            <select name="document_id" id="document_id"
                                class="mt-1 block w-full rounded border-gray-300">
                                @foreach ($documents as $document)
                                    <option value="{{ $document->id }}"
                                        {{ $pickupRequest->document_id == $document->id ? 'selected' : '' }}>
                                        {{ $document->document_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block font-medium text-gray-700">Status</label>
                            <input type="text" name="status" id="status" value="{{ $pickupRequest->status }}"
                                class="mt-1 block w-full rounded border-gray-300">
                        </div>

                        <!-- Pickup Date -->
                        <div>
                            <label for="pickup_date" class="block font-medium text-gray-700">Pickup Date</label>
                            <input type="date" name="pickup_date" id="pickup_date"
                                value="{{ $pickupRequest->pickup_date }}"
                                class="mt-1 block w-full rounded border-gray-300">
                        </div>

                        <!-- Payment Status -->
                        <div>
                            <label for="is_paid" class="block font-medium text-gray-700">Payment Status</label>
                            <select name="is_paid" id="is_paid" class="mt-1 block w-full rounded border-gray-300">
                                <option value="paid" {{ $pickupRequest->is_paid == 'paid' ? 'selected' : '' }}>Paid
                                </option>
                                <option value="unpaid" {{ $pickupRequest->is_paid == 'unpaid' ? 'selected' : '' }}>
                                    Unpaid</option>
                            </select>
                        </div>

                        <!-- Remarks -->
                        <div>
                            <label for="remarks" class="block font-medium text-gray-700">Remarks</label>
                            <textarea name="remarks" id="remarks" rows="3" class="mt-1 block w-full rounded border-gray-300">{{ $pickupRequest->remarks }}</textarea>
                        </div>

                        <!-- Verification Status -->
                        @php
                            $payment = $pickupRequest->payments->first();
                        @endphp

                        @if ($payment)
                            <div>
                                <label for="is_verified" class="block font-medium text-gray-700">Verification
                                    Status</label>
                                <select name="is_verified" id="is_verified"
                                    class="mt-1 block w-full rounded border-gray-300">
                                    <option value="0" {{ $payment->is_verified == 0 ? 'selected' : '' }}>Not
                                        Verified</option>
                                    <option value="1" {{ $payment->is_verified == 1 ? 'selected' : '' }}>Verified
                                    </option>
                                </select>
                            </div>

                            <!-- Verification Remarks -->
                            <div>
                                <label for="verification_remarks" class="block font-medium text-gray-700">Verification
                                    Remarks</label>
                                <textarea name="verification_remarks" id="verification_remarks" rows="3"
                                    class="mt-1 block w-full rounded border-gray-300">{{ $payment->verification_remarks }}</textarea>
                            </div>

                            <!-- Proof of Payment -->
                            <div class="md:col-span-2">
                                <label for="proof_of_payment" class="block font-medium text-gray-700">Proof of Payment
                                    (Upload New to Replace)</label>
                                <input type="file" name="proof_of_payment" id="proof_of_payment"
                                    class="mt-1 block w-full rounded border-gray-300">

                                @if ($payment->proof_of_payment)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $payment->proof_of_payment) }}" target="_blank"
                                            class="text-blue-600 hover:underline">View Current Proof</a>
                                    </div>
                                @endif
                            </div>
                        @endif

                    </div>

                    <div class="flex justify-end pt-6">
                        <button type="submit" class="rounded bg-blue-600 px-6 py-2 text-white hover:bg-blue-700">
                            Update Pickup Request
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
