<x-app-layout>
    <x-slot name="header">
        <x-user-management-header />
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white p-6 shadow-lg sm:rounded-lg flex flex-col items-center">

                <!-- Profile Header Section -->
                <div class="flex flex-col items-center mb-8">
                    <div class="relative">
                        <!-- Profile Picture -->
                        <img id="profileImage" src="https://via.placeholder.com/150" alt="Profile"
                            class="h-32 w-32 rounded-full object-cover border-4 border-blue-600">

                        <!-- Edit Profile Picture Button -->
                        <label for="profile_picture"
                            class="absolute bottom-0 right-0 bg-blue-500 text-white p-2 rounded-full cursor-pointer hover:bg-blue-600">
                            <i class="fas fa-camera"></i> Add Profile
                        </label>
                    </div>
                </div>

                <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data" class="w-full">
                    @csrf

                    <!-- Full Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        <p class="text-sm text-gray-500">Leave blank if you don't want to change the password.</p>
                    </div>

                    <!-- Address -->
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <input type="text" name="address" id="address" value="{{ old('address') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('address')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Contact Number -->
                    <div class="mb-4">
                        <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact
                            Number</label>
                        <input type="text" name="contact_number" id="contact_number"
                            value="{{ old('contact_number') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('contact_number')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Profile Picture Upload (Hidden) -->
                    <div class="mb-4">
                        <input type="file" name="profile_picture" id="profile_picture"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:py-2 file:px-4 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100"
                            style="display: none;" onchange="previewImage(event)">
                        @error('profile_picture')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end mt-6">
                        <button type="submit"
                            class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Save User
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <script>
        // Function to preview the image before uploading
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('profileImage');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

    <!-- ✅ SweetAlert script (already in your layout) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '{{ session('error') }}',
                timer: 4000,
                showConfirmButton: false
            });
        @endif
    </script>

    <!-- ✅ Add this part next -->
    @if ($errors->any())
        <script>
            let errorMessages = '';
            @foreach ($errors->all() as $error)
                errorMessages += '{{ $error }}\n';
            @endforeach

            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: errorMessages,
                confirmButtonText: 'OK'
            });
        </script>
    @endif

</x-app-layout>
