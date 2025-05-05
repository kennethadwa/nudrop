<x-app-layout>
    <x-slot name="header">
        <div>
            <x-accounting-header />
        </div>
    </x-slot>

    <div class="bg-gray-100 py-12">
        <div class="max-w-9xl mx-auto sm:px-8 lg:px-12 xl:px-16">
            <div class="rounded-lg bg-white p-6 shadow-lg">

                {{-- Responsive Cards --}}
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4">
                    <!-- Card 1 - Total Requests -->
                    <div
                        class="transform rounded-xl bg-[#35408F] p-6 text-white shadow-lg transition duration-300 hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-semibold">Total Requests</h2>
                                <p class="mt-2 text-2xl font-bold">{{ $total_requests ?? 0 }}</p>
                            </div>
                            <div class="text-4xl">
                                <i class="bi bi-file-earmark-text-fill"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 - Not Verified Requests -->
                    <div
                        class="transform rounded-xl bg-[#35408F] p-6 text-white shadow-lg transition duration-300 hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-semibold">Not Verified Requests</h2>
                                <p class="mt-2 text-2xl font-bold">{{ $not_verified_requests ?? 0 }}</p>
                            </div>
                            <div class="text-4xl">
                                <i class="bi bi-x-circle-fill"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 - Verified Requests -->
                    <div
                        class="transform rounded-xl bg-[#35408F] p-6 text-white shadow-lg transition duration-300 hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-semibold">Verified Requests</h2>
                                <p class="mt-2 text-2xl font-bold">{{ $verified_requests ?? 0 }}</p>
                            </div>
                            <div class="text-4xl">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4 - Weekly Revenue -->
                    <div
                        class="transform rounded-xl bg-[#35408F] p-6 text-white shadow-lg transition duration-300 hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-semibold">Weekly Revenue</h2>
                                <p class="mt-2 text-2xl font-bold">₱{{ number_format($weekly_revenue ?? 0, 2) }}</p>
                            </div>
                            <div class="text-4xl">
                                <i class="bi bi-bar-chart-fill"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Card 5 - Monthly Revenue -->
                    <div
                        class="transform rounded-xl bg-[#35408F] p-6 text-white shadow-lg transition duration-300 hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-semibold">Monthly Revenue</h2>
                                <p class="mt-2 text-2xl font-bold">₱{{ number_format($monthly_revenue ?? 0, 2) }}</p>
                            </div>
                            <div class="text-4xl">
                                <i class="bi bi-bar-chart-line-fill"></i>
                            </div>
                        </div>
                    </div>


                    <!-- Card 6 - Annual Revenue -->
                    <div
                        class="transform rounded-xl bg-[#35408F] p-6 text-white shadow-lg transition duration-300 hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-semibold">Annual Revenue</h2>
                                <p class="mt-2 text-2xl font-bold">₱{{ number_format($annual_revenue ?? 0, 2) }}</p>
                            </div>
                            <div class="text-4xl">
                                <i class="bi bi-bar-chart-fill"></i>
                            </div>
                        </div>
                    </div>


                </div>


                {{-- Weekly Overview --}}
                <div class="mt-10">
                    <h2 class="mb-4 text-2xl font-bold text-gray-800">📆 Weekly Revenue Overview</h2>
                    <div class="rounded-xl bg-white p-4 shadow-md">
                        {!! $weekly_chart->container() !!}
                    </div>
                </div>

                {{-- Monthly Overview --}}
                <div class="mt-10">
                    <h2 class="mb-4 text-2xl font-bold text-gray-800">📅 Montly Revenue Overview</h2>
                    <div class="rounded-xl bg-white p-4 shadow-md">
                        {!! $monthly_chart->container() !!}
                    </div>
                </div>

                {{-- Annual Overview --}}
                <div class="mt-10">
                    <h2 class="mb-4 text-2xl font-bold text-gray-800">📅 Annual Revenue Overview</h2>
                    <div class="rounded-xl bg-white p-4 shadow-md">
                        {!! $annual_chart->container() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>

    @stack('scripts')

    {{-- Ensure the chart scripts are loaded --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include the Chart.js library -->

    {!! $weekly_chart->script() !!} <!-- Render Weekly Chart -->
    {!! $monthly_chart->script() !!} <!-- Render Monthly Chart -->
    {!! $annual_chart->script() !!} <!-- Render Annual Chart -->
</x-app-layout>
