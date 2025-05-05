<x-app-layout>
    <x-slot name="header">
        <div>
            <x-accounting-header />
        </div>
    </x-slot>

    <div class="bg-gray-50 py-12">
        <div class="mx-auto max-w-5xl rounded-xl bg-white p-10 shadow-lg">

            <h2 class="mb-6 text-xl font-semibold text-gray-900">Edit Payment Method</h2>

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

            <!-- Form to edit payment method -->
            <form method="POST" action="{{ route('accounting.payment_methods.update', $paymentMethod->id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Is Active -->
                <div class="mb-4">
                    <label for="is_active" class="block text-sm font-medium text-gray-600">Active</label>
                    <input type="checkbox" name="is_active" id="is_active" class="form-checkbox text-green-600"
                        {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}>
                    @error('is_active')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Payment Method Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-600">Payment Method</label>
                    <input type="text" name="name" id="name"
                        class="mt-1 w-full rounded-md border-gray-300 p-2 shadow-sm"
                        value="{{ old('name', $paymentMethod->name) }}" required>
                    @error('name')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Payment Method Number -->
                <div class="mb-4">
                    <label for="payment_number" class="block text-sm font-medium text-gray-600"> Account Number</label>
                    <input type="text" name="payment_number" id="payment_number"
                        class="mt-1 w-full rounded-md border-gray-300 p-2 shadow-sm"
                        value="{{ old('payment_number', $paymentMethod->payment_number) }}" required>
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
                    <!-- Display existing image -->
                    @if ($paymentMethod->payment_image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $paymentMethod->payment_image) }}"
                                alt="{{ $paymentMethod->name }}" class="w-22 h-20">
                        </div>
                    @endif
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="rounded-md bg-blue-500 px-6 py-2 text-white shadow-md hover:bg-blue-600">Update
                        Information</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
