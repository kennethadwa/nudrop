<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold leading-tight text-white">
                {{ __('Delivery Management') }}
            </h2>
            <div class="mt-4">
                {{-- Links with active class --}}
                <a href="#" id="dashboard"
                    class="active-link mr-5 text-sm font-medium text-white hover:text-yellow-300">Dashboard</a>
                <a href="#" id="documents" class="mr-5 text-sm font-medium text-white hover:text-yellow-300">Document
                    Fee</a>
                <a href="#" id="requests" class="mr-5 text-sm font-medium text-white hover:text-yellow-300">In
                    Transit</a>
                <a href="#" id="requests"
                    class="mr-5 text-sm font-medium text-white hover:text-yellow-300">Completed Deliveries</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200 bg-white p-6">
                    <!-- User Management Content -->
                    <h3 class="text-lg font-medium">Manage Users</h3>
                    <p class="mt-2 text-sm text-gray-600">Here you can manage user accounts, roles, and permissions.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Get all the links
        const links = document.querySelectorAll('.text-white');

        // Function to remove active class from all links
        function removeActiveClass() {
            links.forEach(link => {
                link.classList.remove('text-yellow-300');
            });
        }

        // Add event listeners to each link
        links.forEach(link => {
            link.addEventListener('click', function() {
                removeActiveClass();
                link.classList.add('text-yellow-300');
            });
        });

        // Set "Documents" link as default active
        document.getElementById('dashboard').classList.add('text-yellow-300');
    </script>
</x-app-layout>
