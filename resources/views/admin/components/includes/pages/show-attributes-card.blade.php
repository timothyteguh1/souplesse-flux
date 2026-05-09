@props([
    'obj',
    'title',
    'addButton' => true,
    'editButton' => true,
    'deleteButton' => true,
    'showActionButtons' => true,
])

<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center flex-wrap gap-2">
            <div class="flex-grow-1">
                <h6 class="card-title mb-0">
                    <i class="ri-file-mark-fill align-middle me-1"></i>
                    Data
                    @isset($title)
                        {{ $title }}
                    @endisset
                </h6>
            </div>

            @if ($showActionButtons)
                <div class="flex-shrink-0">
                    <div class="hstack text-nowrap gap-2">
                        <div class="btn-group">
                            <button
                                type="button"
                                class="btn btn-primary dropdown-toggle"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                            >
                                <i class="ri-apps-fill align-middle me-1"></i>
                                Action
                            </button>
                            <div class="dropdown-menu" style="">
                                @if ($addButton && $obj->canCreate())
                                    <a class="dropdown-item" href="{{ $obj->getRouteCreate() }}">
                                        <i class="ri-file-add-line label-icon align-middle fs-16 me-2"></i>
                                        Tambah
                                    </a>
                                @endif

                                @if ($editButton && $obj->canEdit())
                                    <a class="dropdown-item" href="{{ $obj->getRouteEdit() }}">
                                        <i class="ri-pencil-line label-icon align-middle fs-16 me-2"></i>
                                        Ubah
                                    </a>
                                @endif

                                @if ($deleteButton && $obj->canDelete())
                                    <span
                                        role="button"
                                        class="dropdown-item"
                                        wire:click="$dispatch('triggerDelete', {id: '{{ $obj->id }}'})"
                                    >
                                        <i class="ri-delete-bin-line label-icon align-middle fs-16 me-2"></i>
                                        Hapus
                                    </span>
                                @endif

                                @if (isset($actions))
                                    {{ $actions }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="card-body">
        @if (isset($body))
            {{ $body }}
            {{ $slot }}
        @else
            <div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <tbody>
                            {{ $slot }}
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
<!--end card-->
