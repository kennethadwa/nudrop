@php
    $role = Auth::guard('staff')->user()->roles;
@endphp

<nav x-data="{ open: false }" class="border-b border-yellow-300 bg-[#35408F] shadow">
    <!-- Primary Navigation Menu -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex">
                <!-- Logo -->
                <div class="flex shrink-0 items-center">
                    <a href="{{ route('admin.dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                    <h2 class="mx-2 font-semibold text-white">NU DROP</h2>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if ($role == 0)
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('user.management')" :active="request()->routeIs('user_management')">
                            {{ __('User Management') }}
                        </x-nav-link>

                        <x-nav-link :href="route('accounting.dashboard')" :active="request()->routeIs('accounting.dashboard')">
                            {{ __('Accounting Office') }}
                        </x-nav-link>

                        <x-nav-link :href="route('registrar.dashboard')" :active="request()->routeIs('registrar.dashboard')">
                            {{ __('Registrar Office') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:ms-6 sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium text-white transition hover:text-yellow-300 focus:outline-none">
                            <div class="relative">
                                <img src="{{ Auth::guard('staff')->user()->profile_picture ? asset('storage/' . Auth::guard('staff')->user()->profile_picture) : asset('images/default-profile.png') }}"
                                    alt="Profile Picture"
                                    class="h-10 w-10 rounded-full border-2 border-white object-cover shadow-md">
                                <span
                                    class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white bg-green-500 shadow-md"></span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <span class="text-sm font-semibold text-white">
                                    {{ Auth::guard('staff')->user()->name }}
                                </span>
                                <svg class="h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.23 7.21a.75.75 0 011.06.02L10 10.586l3.71-3.355a.75.75 0 111.04 1.08l-4.25 3.85a.75.75 0 01-1.04 0l-4.25-3.85a.75.75 0 01.02-1.06z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('staff.logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('staff.login')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="space-y-1 pb-3 pt-2">
            @if ($role == 0)
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('user.management')" :active="request()->routeIs('user_management')">
                    {{ __('User Management') }}
                </x-responsive-nav-link>
            @endif

            @if ($role == 1)
                <x-responsive-nav-link :href="route('accounting.dashboard')" :active="request()->routeIs('accounting.dashboard')">
                    {{ __('Accounting Office') }}
                </x-responsive-nav-link>
            @endif

            @if ($role == 2)
                <x-responsive-nav-link :href="route('registrar.dashboard')" :active="request()->routeIs('registrar.dashboard')">
                    {{ __('Registrar Office') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="border-t border-gray-200 pb-1 pt-4">
            <div class="px-4">
                <div class="text-base font-medium text-white">{{ Auth::guard('staff')->user()->name }}</div>
                <div class="text-sm font-medium text-white">{{ Auth::guard('staff')->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
