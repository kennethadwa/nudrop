<x-app-layout>
    <x-slot name="header">
        <div>
            <x-registrar-header />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                <h3 class="text-lg font-semibold">New Pickup Request</h3>

                <form action="{{ route('pickup_requests.store') }}" method="POST">
                    @csrf

                    <div class="mt-4">
                        <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                        <select id="user_id" name="user_id"
                            class="mt-1 block w-full rounded-md border-gray-300 text-black shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                            required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4">
                        <label for="document_id" class="block text-sm font-medium text-gray-700">Document</label>
                        <select id="document_id" name="document_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                            required>
                            @foreach ($documents as $document)
                                <option value="{{ $document->id }}">{{ $document->document_name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="mt-4">
                        <label for="pickup_date" class="block text-sm font-medium text-gray-700">Pickup Date</label>
                        <input type="date" id="pickup_date" name="pickup_date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                    </div>

                    <div class="mt-4">
                        <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                        <textarea id="remarks" name="remarks" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                            placeholder="Enter any additional notes...">{{ old('remarks') }}</textarea>
                    </div>


                    <div class="mt-4">
                        <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Create
                            Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
