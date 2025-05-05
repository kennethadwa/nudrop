<x-app-layout>
    <x-slot name="header">
        <x-accounting-header />
    </x-slot>

    <section class="bg-gray-100 py-12">
        <div class="max-w-9xl mx-auto sm:px-8 lg:px-12 xl:px-16">

            <!-- Header -->
            <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Payment Methods</h2>
                    <p class="text-sm text-gray-500">Manage your active payment options</p>
                </div>
                <a href="{{ route('accounting.payment_methods.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Payment Method
                </a>
            </div>

            <!-- Payment Methods Table -->
            <div class="overflow-x-auto rounded-lg border">
                <table class="w-full min-w-[800px] divide-y divide-gray-200 text-sm text-gray-700">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-4 text-left font-semibold">Activate</th>
                            <th class="p-4 text-left font-semibold">Image</th>
                            <th class="p-4 text-left font-semibold">Payment Method</th>
                            <th class="p-4 text-left font-semibold">Account Number</th>
                            <th class="p-4 text-center font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($paymentMethods as $paymentMethod)
                            <tr class="hover:bg-gray-50">
                                <td class="p-4">
                                    <form method="POST"
                                        action="{{ route('accounting.toggle_payment_method', $paymentMethod->id) }}"
                                        id="toggle-form-{{ $paymentMethod->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="checkbox"
                                            onchange="document.getElementById('toggle-form-{{ $paymentMethod->id }}').submit();"
                                            name="is_active" value="1"
                                            {{ $paymentMethod->is_active ? 'checked' : '' }}
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                    </form>
                                </td>
                                <td class="p-4">
                                    @if ($paymentMethod->payment_image)
                                        <img src="{{ asset('storage/' . $paymentMethod->payment_image) }}"
                                            alt="{{ $paymentMethod->name }}" class="h-10 w-12 rounded-md object-cover">
                                    @else
                                        <span class="text-gray-400">No Image</span>
                                    @endif
                                </td>
                                <td class="p-4 font-medium">{{ $paymentMethod->name }}</td>
                                <td class="p-4">{{ $paymentMethod->payment_number }}</td>
                                <td class="flex justify-center gap-4 p-4">
                                    <!-- Edit -->
                                    <a href="{{ route('accounting.edit_payment_method', $paymentMethod->id) }}"
                                        class="inline-flex items-center rounded-md bg-blue-500 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-600">
                                        Edit
                                    </a>

                                    <!-- Delete -->
                                    <button type="button" onclick="confirmDelete({{ $paymentMethod->id }})"
                                        class="inline-flex items-center rounded-md bg-red-500 px-4 py-2 text-xs font-semibold text-white hover:bg-red-600">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-400">
                                    No Payment Methods Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Delete Forms -->
            @foreach ($paymentMethods as $paymentMethod)
                <form id="delete-form-{{ $paymentMethod->id }}"
                    action="{{ route('accounting.payment_methods.destroy', $paymentMethod->id) }}" method="POST"
                    class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach

        </div>
    </section>
</x-app-layout>

<!-- SweetAlert for Success -->
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

<!-- SweetAlert for Delete Confirmation -->
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the payment method.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
