<h2 class="text-xl font-semibold leading-tight text-white">
    {{ __('Accounting Office') }}
</h2>
<div class="mt-4">
    <x-nav-link :href="route('accounting.dashboard')" :active="request()->routeIs('accounting.dashboard')" id="dashboard"
        class="mr-5 text-sm font-medium text-white hover:text-yellow-300">Dashboard</x-nav-link>

    <x-nav-link :href="route('accounting.pickup_requests.index')" :active="request()->routeIs('accounting.pickup_requests.*')" id="pickup_requests"
        class="mr-5 text-sm font-medium text-white hover:text-yellow-300">Pickup Requests</x-nav-link>

    <x-nav-link :href="route('transactions.index')" id="transactions"
        class="mr-5 text-sm font-medium text-white hover:text-yellow-300">Transaction Reports</x-nav-link>

    <x-nav-link :href="route('accounting.payment_settings')" :active="request()->routeIs('accounting.payments')" id="payments"
        class="mr-5 text-sm font-medium text-white hover:text-yellow-300">Payment Settings</x-nav-link>

    <x-nav-link :href="route('accounting.archive')" id="archive"
        class="mr-5 text-sm font-medium text-white hover:text-yellow-300">Archive</x-nav-link>


</div>



<script>
    const links = document.querySelectorAll('.active-link');

    function removeActiveClass() {
        links.forEach(link => {
            link.classList.remove('text-yellow-300');
        });
    }

    links.forEach(link => {
        link.addEventListener('click', function() {
            removeActiveClass();
            this.classList.add('text-yellow-300');
        });
    });

    // Get full pathname (e.g., "/accounting/pickup_requests")
    const path = window.location.pathname;

    if (path.includes('/accounting/pickup_requests')) {
        document.getElementById('pickup_requests')?.classList.add('text-yellow-300');
    } else if (path.includes('/accounting/payments')) {
        document.getElementById('payments')?.classList.add('text-yellow-300');
    } else if (path === '/accounting') {
        document.getElementById('dashboard')?.classList.add('text-yellow-300');
    }
</script>
