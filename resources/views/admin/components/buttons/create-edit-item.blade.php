@props([
    'action' => 'editItem',
    'label' => 'Edit Item',
])

<span
    class="btn btn-warning w-100 btn-load"
    wire:click="{{ $action }}"
    role="button"
    tabindex="0"
    wire:keydown.enter="{{ $action }}"
>
    <span class="d-flex align-items-center">
        <span class="flex-grow-1 me-2">
            <i class="ri-pencil-fill me-1 align-bottom"></i>
            {{ $label }}
        </span>
        <span class="spinner-border flex-shrink-0" role="status" wire:loading.delay wire:target="$refresh"></span>
    </span>
</span>
