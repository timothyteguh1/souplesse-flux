<x-admin::layouts.base>
    <div class="align-items-center auth-page-wrapper d-flex justify-content-center min-vh-100 py-5">
        <!-- auth page content -->
        <div class="auth-page-content">
            {{ $slot }}
        </div>
        <!-- end auth page content -->

        <x-admin::includes.footer-auth />
    </div>
    <!-- end auth-page-wrapper -->
</x-admin::layouts.base>
