<x-admin::layouts.app>
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">@yield('title')</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            {{ $slot }}
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
</x-admin::layouts.app>
