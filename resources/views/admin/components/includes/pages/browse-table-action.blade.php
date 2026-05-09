@props([
    'obj',
    'viewButton' => true,
    'editButton' => true,
    'deleteButton' => true,
])

<div class="dropdown">
    <button
        class="btn btn-soft-secondary btn-sm dropdown"
        type="button"
        data-bs-toggle="dropdown"
        aria-expanded="false"
    >
        <i class="ri-more-fill align-middle"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        @if ($viewButton && $obj->canShow())
            <li>
                <a class="dropdown-item" href="{{ $obj->getRouteShow() }}">
                    <i class="ri-eye-fill align-bottom me-2 text-muted"></i>
                    Lihat Detail
                </a>
            </li>
        @endif

        @if ($editButton && $obj->canEdit())
            <li>
                <a class="dropdown-item" href="{{ $obj->getRouteEdit() }}">
                    <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                    Ubah
                </a>
            </li>
        @endif

        @if ($deleteButton && $obj->canDelete())
            <li>
                <span
                    role="button"
                    class="dropdown-item"
                    wire:click="$dispatch('triggerDelete', {id: '{{ $obj->id }}'})"
                >
                    <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                    Hapus
                </span>
            </li>
        @endif

        {{ $slot }}
    </ul>
</div>
