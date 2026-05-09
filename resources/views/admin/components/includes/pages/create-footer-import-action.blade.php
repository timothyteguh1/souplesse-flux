<div class="card-footer text-end">
    {{ $slot }}

    <span
        class="btn btn-soft-success btn-load waves-effect waves-light"
        role="button"
        tabindex="0"
        wire:click="downloadTemplate"
        wire:keydown.enter="downloadTemplate"
    >
        <i class="ri-file-excel-2-fill align-bottom me-1"></i>
        Download template
        <span
            class="spinner-border flex-shrink-0 ms-1 align-bottom"
            role="status"
            wire:loading.delay
            wire:target="downloadTemplate"
        ></span>
    </span>

    <span
        class="btn btn-primary btn-load waves-effect waves-light"
        role="button"
        tabindex="0"
        wire:click="submitDefault"
        wire:keydown.enter="submitDefault"
    >
        <i class="mdi mdi-upload align-bottom me-1"></i>
        Import
        <span
            class="spinner-border flex-shrink-0 ms-1 align-bottom"
            role="status"
            wire:loading.delay
            wire:target="submitDefault,submitAndCreate,submitAndShow,submitAndBackToIndex"
        ></span>
    </span>
</div>
