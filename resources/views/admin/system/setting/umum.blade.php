<div>
    @section('title', $menuTitle)

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <x-admin::includes.alert-messages />
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                {{ $menuTitle }}
                            </h6>
                        </div>

                        <form wire:submit="submit">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-12"></div>
                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <x-admin::buttons.app-update />
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end col -->
            </div>
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
</div>
