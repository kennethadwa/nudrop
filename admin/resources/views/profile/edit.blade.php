<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-white">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>

    <!-- SweetAlert2 Script for success messages -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Success alert after updating profile
        @if (session('profileUpdated'))
            Swal.fire({
                title: 'Success!',
                text: '{{ session('profileUpdated') }}',
                icon: 'success',
                confirmButtonText: 'Okay'
            });
        @endif

        // Success alert after deleting account
        @if (session('accountDeleted'))
            Swal.fire({
                title: 'Account Deleted',
                text: '{{ session('accountDeleted') }}',
                icon: 'warning',
                confirmButtonText: 'Okay'
            });
        @endif
    </script>
</x-app-layout>
