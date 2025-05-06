<h2 class="text-xl font-semibold leading-tight text-white">
    {{ __('User Management') }}
</h2>
<div class="mt-4">
    <x-nav-link href="{{ url('admin/user-management/user-accounts') }}"
        class="active-link mr-5 text-sm font-medium text-white hover:text-yellow-300" id="user-accounts">User
        Account</x-nav-link>

    <x-nav-link href="{{ url('/admin/registrar-staff-accounts') }}"
        class="active-link mr-5 text-sm font-medium text-white hover:text-yellow-300"
        id="registrar-office-staff">Registrar Office Staff</x-nav-link>

    <x-nav-link href="{{ url('/admin/accounting-staff') }}"
        class="active-link mr-5 text-sm font-medium text-white hover:text-yellow-300"
        id="accounting-office-staff">Accounting Office Staff</x-nav-link>

    <x-nav-link href="{{ url('/admin/admin-account') }}"
        class="active-link mr-5 text-sm font-medium text-white hover:text-yellow-300" id="admin-accounts">Admin
        Account</x-nav-link>

    <x-nav-link href="{{ url('admin/user-management/archive') }}"
        class="active-link mr-5 text-sm font-medium text-white hover:text-yellow-300"
        id="archived-accounts">Archive</x-nav-link>
</div>

<script>
    const links = document.querySelectorAll('.active-link');

    // Function to remove the active class
    function removeActiveClass() {
        links.forEach(link => {
            link.classList.remove('text-yellow-300');
        });
    }

    // Function to highlight the active link based on the URL
    function highlightBasedOnURL() {
        const path = window.location.pathname;

        // Check the current path and highlight the corresponding link
        if (path.includes('user-accounts')) {
            document.getElementById('user-accounts')?.classList.add('text-yellow-300');
        } else if (path.includes('registrar-office-staff')) {
            document.getElementById('registrar-office-staff')?.classList.add('text-yellow-300');
        } else if (path.includes('accounting-office-staff')) {
            document.getElementById('accounting-office-staff')?.classList.add('text-yellow-300');
        } else if (path.includes('admin-accounts')) {
            document.getElementById('admin-accounts')?.classList.add('text-yellow-300');
        } else if (path.includes('archive')) {
            document.getElementById('archived-accounts')?.classList.add('text-yellow-300');
        }
    }

    // Add event listener for click on each link
    links.forEach(link => {
        link.addEventListener('click', function() {
            removeActiveClass();
            this.classList.add('text-yellow-300');
        });
    });

    // On page load, highlight the active link based on the current URL
    highlightBasedOnURL();
</script>
