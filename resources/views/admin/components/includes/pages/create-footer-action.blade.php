@props([
    'createButton' => true,
    'confirmationButton' => false,
    'multipleButton' => false,
    'modalButton' => false,
])

<div class="card-footer text-end">
    {{ $slot }}

    @if ($createButton)
        <span class="btn btn-primary btn-load waves-effect waves-light" role="button" tabindex="0"
            wire:loading.attr="disabled" wire:loading.class="disabled"
            wire:target="submitDefault,submitAndCreate,submitAndShow,submitAndBackToIndex,submitConfirmation,submitModal"
            wire:click="submitDefault" wire:keydown.enter="submitDefault" x-data
            @keyup.ctrl.s.window="$wire.submitDefault()">
            <i class="ri-add-line align-bottom me-1"></i>
            Simpan
            <span class="spinner-border flex-shrink-0 ms-1 align-bottom" role="status" wire:loading.delay
                wire:target="submitDefault,submitAndCreate,submitAndShow,submitAndBackToIndex,submitConfirmation,submitModal"></span>
        </span>
    @endif

    @if ($confirmationButton)
        <span class="btn btn-primary btn-load waves-effect waves-light" role="button" tabindex="0"
            wire:loading.attr="disabled" wire:loading.class="disabled"
            wire:target="submitDefault,submitAndCreate,submitAndShow,submitAndBackToIndex,submitConfirmation,submitModal"
            wire:click="submitConfirmation" wire:keydown.enter="submitConfirmation" x-data
            @keyup.ctrl.s.window="$wire.submitConfirmation()">
            <i class="ri-add-line align-bottom me-1"></i>
            Simpan
            <span class="spinner-border flex-shrink-0 ms-1 align-bottom" role="status" wire:loading.delay
                wire:target="submitDefault,submitAndCreate,submitAndShow,submitAndBackToIndex,submitConfirmation,submitModal"></span>
        </span>
    @endif

    @if ($modalButton)
        <span class="btn btn-primary btn-load waves-effect waves-light" role="button" tabindex="0"
            wire:loading.attr="disabled" wire:loading.class="disabled"
            wire:target="submitDefault,submitAndCreate,submitAndShow,submitAndBackToIndex,submitConfirmation,submitModal"
            wire:click="submitModal" wire:keydown.enter="submitModal" x-data @keyup.ctrl.s.window="$wire.submitModal()">
            <i class="ri- align-bottom me-1"></i>
            Bayar
        </span>
    @endif

    @if ($multipleButton)
        <div class="btn-group">
            <span role="button" class="btn btn-primary btn-load" wire:click="submitDefault">
                <span class="spinner-border flex-shrink-0 me-1 align-bottom" role="status" wire:loading.delay
                    wire:target="submitDefault,submitAndCreate,submitAndShow,submitAndBackToIndex"></span>
                Tambah
            </span>
            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
            <div class="dropdown-menu">
                <span role="button" class="dropdown-item" wire:click="submitAndCreate">Tambah & Buat Baru</span>
                <div class="dropdown-divider"></div>
                <span role="button" class="dropdown-item" wire:click="submitAndShow">Tambah & Lihat Detail</span>
                <div class="dropdown-divider"></div>
                <span role="button" class="dropdown-item" wire:click="submitAndBackToIndex">
                    Tambah & ke Halaman List
                </span>
            </div>
        </div>
        <!-- /btn-group -->
    @endif
</div>
