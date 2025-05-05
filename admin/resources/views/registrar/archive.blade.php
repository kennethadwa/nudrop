<x-app-layout>
    <x-slot name="header">
        <div>
            <x-registrar-header />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-8 lg:px-12 xl:px-16">
            <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Recently Deleted Requests</h2>

                @if ($archivedRequests->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm text-gray-700">
                            <thead class="bg-gray-100 uppercase text-gray-700">
                                <tr>
                                    <th class="px-4 py-2">Reference No</th>
                                    <th class="px-4 py-2">User</th>
                                    <th class="px-4 py-2">Document</th>
                                    <th class="px-4 py-2">Status</th>
                                    <th class="px-4 py-2">Pickup Date</th>
                                    <th class="px-4 py-2">Payment</th>
                                    <th class="px-4 py-2">Remarks</th>
                                    <th class="px-4 py-2">Deleted At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($archivedRequests as $request)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $request->reference_no }}</td>
                                        <td class="px-4 py-2">{{ $request->user->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-2">{{ $request->document->document_name ?? 'N/A' }}</td>
                                        <td class="px-4 py-2">{{ $request->status ?? 'N/A' }}</td>
                                        <td class="px-4 py-2">{{ $request->pickup_date }}</td>
                                        <td class="border px-4 py-2">{{ $request->is_paid == 1 ? 'Paid' : 'Unpaid' }}
                                        </td>
                                        <td class="px-4 py-2">{{ $request->remarks ?? 'N/A' }}</td>
                                        <td class="px-4 py-2">{{ $request->deleted_at }}</td>
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
