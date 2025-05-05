<h2 class="text-xl font-semibold leading-tight text-white">
    {{ __('Registrar Office') }}
</h2>
<div class="mt-4">
    <x-nav-link :href="route('registrar.dashboard')" :active="request()->routeIs('registrar.dashboard   ')"
        class="mr-5 text-sm font-medium text-white hover:text-yellow-300">Dashboard</x-nav-link>

    <x-nav-link :href="route('documents')" :active="request()->routeIs('documents')"
        class="mr-5 text-sm font-medium text-white hover:text-yellow-300">Documents</x-nav-link>

    <x-nav-link :href="route('pickup_requests.index')" :active="request()->routeIs('pickup_requests.*')"
        class="mr-5 text-sm font-medium text-white hover:text-yellow-300">Pickup Requests</x-nav-link>

    <x-nav-link :href="route('registrar.archive')" :active="request()->is('archive')" {{-- change this to your actual route --}}
        class="mr-5 text-sm font-medium text-white hover:text-yellow-300">Archive</x-nav-link>
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

    // Set active class based on the current page
    const currentRoute = window.location.pathname.split('/').pop(); // Get the current route (page)

    // Add the active class to the appropriate link based on the current route
    if (currentRoute === 'registrar') {
        document.getElementById('dashboard').classList.add('text-yellow-300');
    } else if (currentRoute === 'documents') {
        document.getElementById('documents').classList.add('text-yellow-300');
    } else if (currentRoute === 'pickup_requests') {
        document.getElementById('pickup').classList.add('text-yellow-300');
    } else if (currentRoute === 'delivery') {
        document.getElementById('delivery').classList.add('text-yellow-300');
    }
</script>
