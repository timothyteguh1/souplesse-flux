<div class="card-footer text-end">
    {{-- <div class="btn-group"> --}}
    {{-- <span role="button" class="btn btn-primary btn-load" wire:click="submitDefault"> --}}
    {{-- <span class="spinner-border flex-shrink-0 me-1 align-bottom" role="status" --}}
    {{-- wire:loading.delay --}}
    {{-- wire:target="submitDefault,submitAndCreate,submitAndShow,submitAndBackToIndex" --}}
    {{-- ></span> --}}
    {{-- Ubah --}}
    {{-- </span> --}}
    {{-- <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" --}}
    {{-- data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button> --}}
    {{-- <div class="dropdown-menu"> --}}
    {{-- <span role="button" class="dropdown-item" wire:click="submitAndCreate">Ubah & Buat Baru</span> --}}
    {{-- <div class="dropdown-divider"></div> --}}
    {{-- <span role="button" class="dropdown-item" wire:click="submitAndShow">Ubah & Lihat Detail</span> --}}
    {{-- <div class="dropdown-divider"></div> --}}
    {{-- <span role="button" class="dropdown-item" --}}
    {{-- wire:click="submitAndBackToIndex">Ubah & ke Halaman List</span> --}}
    {{-- </div> --}}
    {{-- </div><!-- /btn-group --> --}}

    <span
        class="btn btn-primary btn-load waves-effect waves-light"
        role="button"
        tabindex="0"
        wire:click="submitDefault"
        wire:keydown.enter="submitDefault"
    >
        <i class="ri-pencil-line align-bottom me-1"></i>
        Simpan
        <span
            class="spinner-border flex-shrink-0 ms-1 align-bottom"
            role="status"
            wire:loading.delay
            wire:target="submitDefault,submitAndCreate,submitAndShow,submitAndBackToIndex"
        ></span>
    </span>

    {{ $slot }}
</div>
