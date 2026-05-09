<button class="btn btn-secondary w-100 btn-load" type="submit">
    <span class="d-flex align-items-center">
        <span class="flex-grow-1 me-2">
            <i class="ri-eye-fill me-1 align-bottom"></i>
            Lihat
        </span>
        <span class="spinner-border flex-shrink-0" role="status" wire:loading.delay wire:target="$refresh"></span>
    </span>
</button>
