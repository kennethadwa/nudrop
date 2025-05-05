<x-guest-layout>
    <form method="POST" action="{{ route('staff.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Full Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')"
                required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="mt-1 block w-full" type="password"
                name="password_confirmation" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-4">
            <x-input-label for="roles" :value="__('Roles')" />
            <select name="roles" id="roles" class="mt-1 block w-full" required>
                <option value="0">Admin</option>
                <option value="1">Accounting Staff</option>
                <option value="2">Registrar Staff</option>
            </select>
            <x-input-error :messages="$errors->get('roles')" class="mt-2" />
        </div>

        <!-- Profile Picture -->
        <div class="mt-4">
            <x-input-label for="profile_picture" :value="__('Profile Picture')" />
            <input id="profile_picture" class="mt-1 block w-full" type="file" name="profile_picture"
                accept="image/*">
            <x-input-error :messages="$errors->get('profile_picture')" class="mt-2" />
        </div>

        <!-- Submit -->
        <div class="mt-4 flex items-center justify-end">
            <a class="text-sm text-gray-600 underline hover:text-gray-900" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register Staff') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
