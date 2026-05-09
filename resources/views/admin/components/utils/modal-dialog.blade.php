@props(['size' => '', 'id' => '', 'scripts' => false])

<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
    {{ $slot }}
</div>

@push('after-scripts')
    <script>
        window.addEventListener('close{{ $id }}', (event) => {
            $('#{{ $id }}').modal('hide');
        });

        window.addEventListener('show{{ $id }}', (event) => {
            $('#{{ $id }}').modal('show');
        });

        $(document).ready(function () {
            $('#{{ $id }}').on('hidden.bs.modal', function () {
                Livewire.dispatch('forcedClose{{ $id }}');
            });
        });
    </script>

    @if ($scripts)
        {{ $scripts }}
    @endif
@endpush
