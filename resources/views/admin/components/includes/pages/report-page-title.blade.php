<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">@yield('title')</h4>

            <div class="page-title-right">
                <div class="btn-group">
                    <button
                        type="button"
                        class="btn btn-info dropdown-toggle btn-load"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <i
                            class="mdi mdi-download me-1 align-bottom"
                            wire:loading.remove
                            wire:target="exportPrint,exportExcel,exportPdf"
                        ></i>
                        <span
                            class="spinner-border flex-shrink-0 me-1 align-bottom"
                            role="status"
                            wire:loading.delay
                            wire:target="exportPrint,exportExcel,exportPdf"
                        ></span>
                        Export
                    </button>
                    <div class="dropdown-menu">
                        <span role="button" class="dropdown-item" wire:click="exportPrint">
                            <i class="ri-printer-fill"></i>
                            Print
                        </span>
                        <span role="button" class="dropdown-item" wire:click="exportExcel">
                            <i class="mdi mdi-file-excel"></i>
                            Excel
                        </span>

                        <span role="button" class="dropdown-item" wire:click="exportPdf">
                            <i class="mdi mdi-file-pdf-box"></i>
                            PDF
                        </span>
                    </div>
                </div>
                <!-- /btn-group -->
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
