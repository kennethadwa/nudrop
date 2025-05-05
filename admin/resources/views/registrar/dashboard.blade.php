<x-app-layout>
    <x-slot name="header">
        <div>
            <x-registrar-header />
        </div>
    </x-slot>

    <div class="bg-gray-100 py-12">
        <div class="max-w-9xl mx-auto sm:px-8 lg:px-12 xl:px-16">
            <div class="rounded-lg bg-white p-6 shadow-lg">

                {{-- Responsive Cards --}}
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-6 xl:grid-cols-6">


                    <!-- Card 1 - Total Available Documents -->
                    <div
                        class="transform rounded-xl bg-[#35408F] p-6 text-white shadow-lg transition duration-300 hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-semibold">Total Documents</h2>
                                <p class="mt-2 text-2xl font-bold">{{ $total_documents > 0 ? $total_documents : 0 }}</p>
                            </div>
                            <div class="text-4xl">
                                <i class="bi bi-folder-fill"></i>
                            </div>
                        </div>
                    </div>


                    <!-- Card 2 - Total Requests -->
                    <div
                        class="transform rounded-xl bg-[#35408F] p-6 text-white shadow-lg transition duration-300 hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-semibold">Verified Requests</h2>
                                <p class="mt-2 text-2xl font-bold">{{ $registrar_verified_requests ?? 0 }}</p>
                            </div>
                            <div class="text-4xl">
                                <i class="bi bi-file-earmark-text-fill"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 - In Pending Requests -->
                    <div
                        class="transform rounded-xl bg-[#35408F] p-6 text-white shadow-lg transition duration-300 hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-semibold">Pending Requests</h2>
                                <p class="mt-2 text-2xl font-bold">{{ $in_transit ?? 0 }}</p>
                            </div>
                            <div class="text-4xl">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                        </div>
                    </div>


                    <!-- Card 4 - On Process Requests -->
                    <div
                        class="transform rounded-xl bg-[#35408F] p-6 text-white shadow-lg transition duration-300 hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-semibold">On Process Requests</h2>
                                <p class="mt-2 text-2xl font-bold">{{ $in_transit ?? 0 }}</p>
                            </div>
                            <div class="text-4xl">
                                <i class="bi bi-gear-fill"></i>

                            </div>
                        </div>
                    </div>

                    <!-- Card 5 - Ready to Claim Requests -->
                    <div
                        class="transform rounded-xl bg-[#35408F] p-6 text-white shadow-lg transition duration-300 hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-semibold">Ready for Pickup</h2>
                                <p class="mt-2 text-2xl font-bold">{{ $ready_to_claim ?? 0 }}</p>
                            </div>
                            <div class="text-4xl">
                                <i class="bi bi-bag-check-fill"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Card 6 - Completed Requests -->
                    <div
                        class="transform rounded-xl bg-[#35408F] p-6 text-white shadow-lg transition duration-300 hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-semibold">Completed Requests</h2>
                                <p class="mt-2 text-2xl font-bold">{{ $completed ?? 0 }}</p>
                            </div>
                            <div class="text-4xl">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Monthly Overview --}}
                <div class="mt-10">
                    <h2 class="mb-4 text-2xl font-bold text-gray-800">📅 Request Overview</h2>
                    <div class="rounded-xl bg-white p-4 shadow-md">
                        <canvas id="monthlyOverviewChart" width="400" height="200"></canvas>
                    </div>
                </div>

                {{-- Weekly Overview --}}
                <div class="mt-10">
                    <h2 class="mb-4 text-2xl font-bold text-gray-800">📆 Most Requested</h2>
                    <div class="rounded-xl bg-white p-4 shadow-md">
                        <canvas id="weeklyOverviewChart" width="400" height="200"></canvas>
                    </div>
                </div>

                {{-- Recent Transactions --}}
                <div class="mt-10">
                    <h2 class="mb-4 text-2xl font-bold text-gray-800">🧾 Recent Transactions</h2>
                    <div class="overflow-x-auto rounded-xl bg-white shadow-md">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Transaction ID</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Date</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4">123456</td>
                                    <td class="whitespace-nowrap px-6 py-4">2023-10-01</td>
                                    <td class="whitespace-nowrap px-6 py-4">₱100.00</td>
                                </tr>
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4">123457</td>
                                    <td class="whitespace-nowrap px-6 py-4">2023-10-02</td>
                                    <td class="whitespace-nowrap px-6 py-4">₱150.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
