<x-app-layout>
    <x-slot name="header">
        <x-user-management-header />
    </x-slot>

    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-12 xl:px-16">

            <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">

                <div class="mb-6 flex justify-between">
                    <h1 class="text-xl font-semibold text-gray-800">User Management</h1>
                    <a href="{{ route('user.create') }}"
                        class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        New User
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-100 uppercase text-gray-600 mb-6">
                            <tr>
                                <th class="px-4 py-2">User ID</th>
                                <th class="px-4 py-2">Profile Picture</th>
                                <th class="px-4 py-2">Name</th>
                                <th class="px-4 py-2">Email</th>
                                <th class="px-4 py-2">Address</th>
                                <th class="px-4 py-2">Contact Number</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($users as $user)
                                <tr>
                                    <td class="px-4 py-2">{{ $user->id }}</td>
                                    <td class="px-4 py-2">
                                        @if ($user->profile_picture)
                                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile"
                                                class="h-10 w-10 rounded-full object-cover">
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">{{ $user->name }}</td>
                                    <td class="px-4 py-2">{{ $user->email }}</td>
                                    <td class="px-4 py-2">{{ $user->address }}</td>
                                    <td class="px-4 py-2">{{ $user->contact_number }}</td>
                                    <td class="flex space-x-2 px-4 py-2">
                                        <a href="{{ route('user.edit', $user->id) }}"
                                            class="flex items-center gap-2 rounded bg-blue-500 px-3 py-1.5 text-white shadow-sm shadow-black transition hover:bg-blue-600">Edit</a>
                                        <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                            class="delete-user-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="flex items-center gap-2 rounded bg-red-500 px-3 py-1.5 text-white shadow-sm shadow-black transition hover:bg-red-600">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-2 text-center text-gray-500">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

@if (session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            background: '#35408F',
            color: '#fff',
            text: @json(session('success')),
            confirmButtonColor: '#11CB65',
            confirmButtonText: 'OK'
        });
    </script>
@endif
<script>
    document.querySelectorAll('.delete-user-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                background: '#35408F',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
