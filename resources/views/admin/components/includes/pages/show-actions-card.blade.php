@props([
    'obj',
    'addButton' => true,
    'editButton' => true,
    'deleteButton' => true,
])

<div class="card">
    <div class="card-header">
        <h6 class="card-title mb-0">
            <i class="ri-fire-fill align-middle me-1"></i>
            Actions
        </h6>
    </div>

    <div class="card-body">
        @if ($addButton)
            @can($obj->getPermissionCreate())
                <div class="mb-2">
                    <a
                        href="{{ $obj->getRouteCreate() }}"
                        class="btn btn-primary btn-label w-100 waves-effect waves-light"
                    >
                        <i class="ri-file-add-line label-icon align-middle fs-16 me-2"></i>
                        Tambah
                    </a>
                </div>
            @endcan
        @endif

        @if ($editButton)
            @can($obj->getPermissionEdit())
                <div class="mb-2">
                    <a
                        href="{{ $obj->getRouteEdit() }}"
                        class="btn btn-warning btn-label w-100 waves-effect waves-light"
                    >
                        <i class="ri-pencil-line label-icon align-middle fs-16 me-2"></i>
                        Ubah
                    </a>
                </div>
            @endcan
        @endif

        @if ($addButton)
            @can($obj->getPermissionDelete())
                <div class="mb-2">
                    <span
                        role="button"
                        class="btn btn-danger btn-label w-100 waves-effect waves-light"
                        wire:click="$dispatch('triggerDelete', {id: '{{ $obj->id }}'})"
                    >
                        <i class="ri-delete-bin-line label-icon align-middle fs-16 me-2"></i>
                        Hapus
                    </span>
                </div>
            @endcan
        @endif

        {{ $slot }}
    </div>
</div>
<!--end card-->
