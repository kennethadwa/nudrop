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
                        <img id="profileImage"
                            src="{{ $staff->profile_picture ? asset('storage/' . $staff->profile_picture) : 'https://via.placeholder.com/150' }}"
                            alt="Profile" class="h-32 w-32 rounded-full object-cover border-4 border-blue-600">

                        <!-- Edit Profile Picture Button -->
                        <label for="profile_picture"
                            class="absolute bottom-0 right-0 bg-blue-500 text-white p-2 rounded-full cursor-pointer hover:bg-blue-600">
                            <i class="fas fa-camera"></i> Edit
                        </label>
                    </div>
                    <h2 class="mt-4 text-xl font-semibold text-gray-800">{{ $staff->name }}</h2>
                    <p class="text-sm text-gray-600">{{ $staff->email }}</p>
                </div>

                <form action="{{ route('admin.update', $staff->id) }}" method="POST" enctype="multipart/form-data"
                    class="w-full">
                    @csrf
                    @method('PUT')

                    <!-- Full Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $staff->name) }}"
                            class="mt-1 w-full rounded-md border border-gray-300 shadow-sm p-2 focus:border-blue-500 focus:ring-blue-500" />
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $staff->email) }}"
                            class="mt-1 w-full rounded-md border border-gray-300 shadow-sm p-2 focus:border-blue-500 focus:ring-blue-500" />
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password"
                            class="mt-1 w-full rounded-md border border-gray-300 shadow-sm p-2 focus:border-blue-500 focus:ring-blue-500" />
                        <p class="text-sm text-gray-500">Leave blank to keep the current password.</p>
                        @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div class="mb-4">
                        <label for="roles" class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="roles" id="roles"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="0" {{ $staff->role == 0 ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('roles')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Profile Picture Upload (Hidden) -->
                    <input type="file" name="profile_picture" id="profile_picture" class="hidden"
                        onchange="previewImage(event)">

                    <!-- Submit -->
                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 transition">Update
                            Information</button>
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
</x-app-layout>
