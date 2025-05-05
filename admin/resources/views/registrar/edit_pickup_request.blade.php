<x-app-layout>
    <x-slot name="header">
        <div>
            <x-registrar-header />
        </div>
    </x-slot>

    <div class="bg-gray-50 py-12">
        <div class="max-w-9xl mx-auto sm:px-8 lg:px-12 xl:px-16">

            <div class="rounded-lg bg-gray-50 p-6">
                <div class="mb-4 flex items-center justify-start">
                    <h2 class="mb-6 border-b pb-2 text-xl font-bold text-blue-700">
                        {{ $pickupRequest->reference_no ?? 'No Reference' }}
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <form method="POST" action="{{ route('pickup_requests.update', $pickupRequest->id) }}"
                        class="space-y-6">
                        @csrf
                        @method('PUT')

                        <table class="w-full table-auto text-left text-gray-700">
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="w-1/3 p-4 font-semibold">User</td>
                                    <td class="p-4">{{ $pickupRequest->user->name }}</td>
                                </tr>
                                <tr>
                                    <td class="p-4 font-semibold">Status</td>
                                    <td class="p-4 font-semibold">
                                        {{ \Illuminate\Support\Str::title($pickupRequest->status) }}</td>
                                </tr>
                                <tr>
                                    <td class="p-4 font-semibold">Paid Status</td>
                                    <td class="p-4">
                                        @if ($pickupRequest->is_paid == 1)
                                            <span
                                                class="rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700">Paid</span>
                                        @else
                                            <span
                                                class="rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-700">Not
                                                Paid</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-4 font-semibold">Document</td>
                                    <td class="p-4">{{ $pickupRequest->document->document_name }}</td>
                                </tr>
                                <tr>
                                    <td class="p-4 font-semibold">Pickup Date</td>
                                    <td class="p-4">{{ $pickupRequest->pickup_date }}</td>
                                </tr>
                                <tr>
                                    <td class="p-4 font-semibold">Verification Status</td>
                                    <td class="p-4">
                                        @if ($pickupRequest->payment && $pickupRequest->payment->is_verified)
                                            <span
                                                class="rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700">Verified</span>
                                        @else
                                            <span
                                                class="rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-700">Not
                                                Verified</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-4 font-semibold">Remarks</td>
                                    <td class="p-4">
                                        <textarea name="remarks" id="remarks" rows="4"
                                            class="block w-full resize-none rounded-md border border-gray-300 bg-white p-3 text-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="Enter your remarks here...">{{ old('remarks', $pickupRequest->remarks) }}</textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-8 flex justify-end">
                            <div class="w-full md:w-1/3">
                                <label for="status" class="mb-2 block text-lg font-semibold text-gray-900">Request
                                    Status</label>
                                <select name="status" id="status"
                                    class="block w-full rounded-md border border-gray-300 bg-white p-3 text-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="pending"
                                        {{ old('status', $pickupRequest->status) == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="on process"
                                        {{ old('status', $pickupRequest->status) == 'on process' ? 'selected' : '' }}>On
                                        Process</option>
                                    <option value="ready for pickup"
                                        {{ old('status', $pickupRequest->status) == 'ready for pickup' ? 'selected' : '' }}>
                                        Ready for Pickup</option>
                                    <option value="completed"
                                        {{ old('status', $pickupRequest->status) == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="cancelled"
                                        {{ old('status', $pickupRequest->status) == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                            </div>


                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="mt-4 rounded-md bg-green-600 px-8 py-3 font-semibold text-white shadow transition hover:bg-green-700 md:mt-6">
                                Update Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
