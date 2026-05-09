<div>
    <x-admin::includes.alert-messages />

    <form wire:submit="submit">
        <div class="row">
            @foreach ($lists as $list)
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0 avatar-sm">
                        <div class="avatar-title bg-light text-primary rounded-3 fs-18">
                            <i class="ri-smartphone-line"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        @if ($list->id == session()->getId())
                            <h6 class="text-success">This Session</h6>
                        @else
                            <h6>Other Session</h6>
                        @endif

                        <p class="text-muted mb-0">
                            <span>IP Address: {{ $list->ip_address }}</span>
                            <span class="mx-2">|</span>
                            <span>
                                Last Activity: {{ \Carbon\Carbon::parse($list->last_activity)->diffForHumans() }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <span role="button" class="btn btn-sm btn-warning" wire:click="logout('{{ $list->id }}')">
                            Logout
                        </span>
                    </div>
                </div>
            @endforeach

            <div class="border-top pt-3">
                <div class="float-end">
                    <span role="button" class="btn btn-danger" wire:click="logoutAll">All Logout</span>
                </div>
            </div>
        </div>
        <!--end row-->
    </form>
</div>
