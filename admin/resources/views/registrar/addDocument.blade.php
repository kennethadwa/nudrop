<x-app-layout>
    <x-slot name="header">
        <div>
            <x-registrar-header />
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="rounded-6xl mx-auto max-w-4xl bg-white p-10 shadow-lg">

            <div class="text-start-xl mb-8 font-bold text-gray-800">
                <h2 class="text-xl font-bold text-gray-900">Add New Document</h2>
                <p class="mt-2 text-sm text-gray-500">Fill-up the document details below</p>
            </div>

            <form action="{{ route('store_document') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="document_name" class="mb-2 block text-sm font-medium text-gray-700">Document Name</label>
                    <input type="text" name="document_name" id="document_name"
                        class="w-full rounded-lg border-gray-300 p-3 text-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required>
                </div>

                <div>
                    <label for="fee" class="mb-2 block text-sm font-medium text-gray-700">Fee</label>
                    <input type="number" name="fee" id="fee"
                        class="w-full rounded-lg border-gray-300 p-3 text-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required>
                </div>

                <div class="flex justify-end gap-4 pt-4">
                    <button type="submit"
                        class="flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Add Document
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
