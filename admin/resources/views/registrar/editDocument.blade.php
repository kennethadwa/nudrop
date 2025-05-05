<x-app-layout>
    <x-slot name="header">
        <div>
            <x-registrar-header />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="rounded-6xl mx-auto max-w-4xl bg-white p-10 shadow-lg">
            <div class="mb-10 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900">Document Information</h2>
                <div class="text-gray-900">
                    <i class="fas fa-edit text-2xl"></i>
                </div>
            </div>

            <form action="{{ route('documents.update', $document->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="document_name" class="block text-sm font-medium text-gray-900">Document Name</label>
                    <div class="flex items-center rounded-lg bg-white shadow-sm">
                        <i class="fas fa-file-signature px-3 text-gray-900"></i>
                        <input type="text" name="document_name" id="document_name"
                            value="{{ old('document_name', $document->document_name) }}"
                            class="w-full rounded-lg border-gray-300 p-3 text-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="fee" class="block text-sm font-medium text-gray-900">Fee</label>
                    <div class="flex items-center rounded-lg bg-white shadow-sm">
                        <i class="fas fa-dollar-sign px-4 text-gray-900"></i>
                        <input type="number" name="fee" id="fee" value="{{ old('fee', $document->fee) }}"
                            class="w-full rounded-lg border-gray-300 p-3 text-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-3 py-3 font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i> Update Information
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
