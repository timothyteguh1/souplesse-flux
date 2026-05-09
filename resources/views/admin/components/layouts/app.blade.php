<x-admin::layouts.base>
    <!-- Begin page -->
    <div id="layout-wrapper">
        <x-admin::includes.topbar />
        <x-admin::includes.sidebar />

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            {{ $slot }}

            <x-admin::includes.footer-app />
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    @push('after-styles')
        @livewireStyles
        <link href="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/libs/printjs/printjs.min.css') }}" rel="stylesheet" />
        <x-admin::includes.style-plugins />
    @endpush

    @push('before-scripts')
        <x-admin::includes.settings />
    @endpush

    @push('after-scripts')
        <script src="{{ asset('assets/admin/js/app.js') }}"></script>
        <script src="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.js') }}"></script>
        <script src="{{ asset('assets/libs/printjs/printjs.min.js') }}"></script>
        @livewireScripts
        @livewireChartsScripts
        <x-admin::includes.script-plugins />
    @endpush
</x-admin::layouts.base>
