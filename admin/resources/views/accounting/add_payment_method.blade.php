<x-app-layout>
    <x-slot name="header">
        <div>
            <x-accounting-header />
        </div>
    </x-slot>

    <div class="bg-gray-50 py-12">
        <div class="mx-auto max-w-5xl rounded-xl bg-white p-10 shadow-lg">

            <h2 class="mb-6 text-2xl font-semibold text-gray-700">Add New Payment Method</h2>

            <!-- Success message using SweetAlert -->
            @if (session('success'))
                <script>
                    Swal.fire({
                        title: 'Success!',
                        text: '{{ session('success') }}',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                </script>
            @endif

            <!-- Form to add new payment method -->
            <form method="POST" action="{{ route('accounting.payment_methods.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Payment Method Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-600">Payment Method</label>
                    <input type="text" name="name" id="name"
                        class="mt-1 w-full rounded-md border-gray-300 p-2 shadow-sm" value="{{ old('name') }}"
                        required>
                    @error('name')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Payment Number -->
                <div class="mb-4">
                    <label for="payment_number" class="block text-sm font-medium text-gray-600">Account Number</label>
                    <input type="text" name="payment_number" id="payment_number"
                        class="mt-1 w-full rounded-md border-gray-300 p-2 shadow-sm" value="{{ old('payment_number') }}"
                        required>
                    @error('payment_number')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Payment Image -->
                <div class="mb-4">
                    <label for="payment_image" class="block text-sm font-medium text-gray-600">Payment Image</label>
                    <input type="file" name="payment_image" id="payment_image" accept="image/*"
                        class="mt-1 w-full rounded-md border-gray-300 p-2 shadow-sm">
                    @error('payment_image')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="rounded-md bg-blue-500 px-6 py-2 text-white shadow-md hover:bg-blue-600">Save Payment
                        Method</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
