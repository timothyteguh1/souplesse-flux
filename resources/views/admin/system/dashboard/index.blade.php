<div>
    @section('title', 'Dashboard')

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div
                        class="d-flex flex-column justify-content-center align-items-center"
                        style="min-height: 80vh !important"
                    >
                        <h4 class="fs-16 mb-1">Welcome {{ auth()->user()->name }}</h4>
                    </div>
                    <!-- end card header -->
                </div>
            </div>
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
</div>
