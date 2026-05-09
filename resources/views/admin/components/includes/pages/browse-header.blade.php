@props([
    'model',
    'createButton' => true,
    'importButton' => false,
    'exportExcel' => true,
    'pagination' => true,
])

<div class="card-header border-0">
    <div class="d-flex align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0 flex-grow-1">
            <div class="d-flex gap-2 flex-wrap">
                @if ($createButton)
                    @can($model::permissionCreate())
                        <a href="{{ $model::routeCreate() }}" class="btn btn-primary">
                            <i class="ri-add-line align-bottom me-1"></i>
                            Tambah
                        </a>
                    @endcan
                @endif

                @if ($importButton)
                    @can($model::permissionCreate())
                        <a href="{{ $model::routeImport() }}" class="btn btn-success">
                            <i class="mdi mdi-upload align-bottom me-1"></i>
                            Import
                        </a>
                    @endcan
                @endif

                {{ $slot }}
            </div>
        </h5>
        <div class="flex-shrink-0 gap-3 flex-column">
            @isset($actions)
                {{ $actions }}
            @endisset

            @if ($exportExcel)
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
                            wire:target="exportExcel,exportPdf"
                        ></i>
                        <span
                            class="spinner-border flex-shrink-0 me-1 align-bottom"
                            role="status"
                            wire:loading.delay
                            wire:target="exportExcel,exportPdf"
                        ></span>
                        Export
                    </button>
                    <div class="dropdown-menu">
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
            @endif

            @if ($pagination)
                <div class="btn-group">
                    <select class="form-select" wire:model.live="perPage">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="1000">1,000</option>
                        <option value="10000">10,000</option>
                    </select>
                </div>
            @endif
        </div>
    </div>
</div>
