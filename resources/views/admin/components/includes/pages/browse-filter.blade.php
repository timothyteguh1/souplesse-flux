<div class="card-body border border-dashed border-start-0 border-end-0">
    <form wire:submit="$refresh">
        {{ $slot }}
    </form>
</div>
