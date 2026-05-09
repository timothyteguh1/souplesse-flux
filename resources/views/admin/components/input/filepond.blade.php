@props(['name', 'uploadedFiles' => null, 'uploadedFilesName' => null, 'defer' => true, 'showError' => 'true'])

@if (! $uploadedFilesName)
    @php
        $uploadedFilesName = $name;
    @endphp
@endif

<div
    wire:ignore
    x-data
    x-init="() => {
    let pond = FilePond.create($refs.{{ $attributes->get('ref') ?? 'input' }}, {
        credits: false,
        allowMultiple: {{ $attributes->has('multiple') ? 'true' : 'false' }},
        allowImagePreview: {{ $attributes->has('allowImagePreview') ? 'true' : 'false' }},
        imagePreviewMaxHeight: {{ $attributes->has('imagePreviewMaxHeight') ? $attributes->get('imagePreviewMaxHeight') : '256' }},
        allowFileTypeValidation: {{ $attributes->has('allowFileTypeValidation') ? 'true' : 'false' }},
        acceptedFileTypes: {!! $attributes->get('acceptedFileTypes') ?? 'null' !!},
        allowFileSizeValidation: {{ $attributes->has('allowFileSizeValidation') ? 'true' : 'false' }},
        maxFileSize: {!! $attributes->has('maxFileSize') ? "'" . $attributes->get('maxFileSize') . "'" : 'null' !!},
        server: {
            process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                @this.upload('{{ $name }}', file, load, error, progress)
            },
            revert: (filename, load) => {
                @this.removeUpload('{{ $name }}', filename, load)
            },
            remove: (source, load, error) => {
                // Should somehow send `source` to server so server can remove the file with this source
                @this.set('{{ $uploadedFilesName }}', null);

                // Can call the error method if something is wrong, should exit after
                error('oh my goodness');

                // Should call the load method when done, no parameters required
                load();
            },
            load: async (source, load) => {
                let response = await fetch(source)
                let blob = await response.blob()

                load(blob)
            },
        },
        files: {{ !is_null($uploadedFiles) && count($uploadedFiles) > 0 ? json_encode($uploadedFiles) : 'null' }},
    });
}"
>
    <input type="file" x-ref="{{ $attributes->get('ref') ?? 'input' }}" wire:model="{{ $name }}" />
</div>

@if ($showError == 'true')
    @error($name)
    <span class="invalid-feedback d-block">
            {{ $message }}
        </span>
    @enderror
@endif

@pushonce('after-styles')
    <link href="{{ asset('assets/admin/libs/filepond/filepond.min.css') }}" rel="stylesheet" />
    <link
        href="{{ asset('assets/admin/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css') }}"
        rel="stylesheet"
    />
@endpushonce

@pushonce('after-scripts')
    <script src="{{ asset('assets/admin/libs/filepond/filepond.min.js') }}"></script>
    <script
        src="{{ asset('assets/admin/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}"></script>
    <script
        src="{{ asset('assets/admin/libs/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}"></script>
    <script
        src="{{ asset('assets/libs/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js') }}"></script>

    <script>
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        FilePond.registerPlugin(FilePondPluginFileValidateSize);
        FilePond.registerPlugin(FilePondPluginImagePreview);
    </script>
@endpushonce
