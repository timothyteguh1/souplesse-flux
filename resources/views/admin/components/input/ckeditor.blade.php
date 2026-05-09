@props(['name', 'defer' => true, 'showError' => 'true', 'placeholder' => ''])

<div wire:ignore>
    <textarea wire:model="{{ $name }}" class="form-control" id="{{ $name }}"></textarea>
</div>
{{-- <div wire:ignore> --}}
{{-- <textarea --}}
{{-- wire:key="{{ $name }}" --}}
{{-- x-ref="{{ $name }}" --}}
{{-- x-data --}}
{{-- x-init=" --}}
{{-- ClassicEditor.create($refs.{{ $name }}) --}}
{{-- .then( function(editor) --}}
{{-- { --}}
{{-- editor.model.document.on('change:data', () => { --}}
{{-- $dispatch('input', editor.getData()) --}}
{{-- }) --}}

{{-- }) --}}
{{-- .catch( error => { --}}
{{-- console.error( error ); --}}
{{-- } ); --}}

{{-- " --}}
{{-- {{ $attributes }}> --}}

{{-- </textarea> --}}
{{-- </div> --}}

@pushonce('after-styles')
    <style>
        /* Tweaking the editable area for better readability. */
        .ck-editor .ck-editor__editable {
            padding: 2em 2em 1em;
            overflow: auto;
            height: 200px;
        }

        /* https://github.com/ckeditor/ckeditor5/issues/903 */
        .ck-editor .ck-content > :first-child {
            margin-top: 0;
        }

        .ck.ck-content ul,
        .ck.ck-content ul li {
            list-style-type: disc;
        }

        .ck.ck-content ul {
            /* Default user agent stylesheet, you can change it to your needs. */
            padding-left: 40px;
        }

        .ck.ck-content ol,
        .ck.ck-content ol li {
            list-style-type: decimal;
        }

        .ck.ck-content ol {
            /* Default user agent stylesheet, you can change it to your needs. */
            padding-left: 40px;
        }

        /*
             * Override the width of the table set by Bootstrap content styles.
             * See: https://github.com/ckeditor/ckeditor5/issues/3253.
             */
        .ck-content .table {
            width: auto;
        }
    </style>
@endpushonce

@pushonce('after-scripts')
    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/35.4.0/classic/ckeditor.js"></script> --}}

    <script>
        ClassicEditor
            .create(document.querySelector('#{{ $name }}'), {
                htmlSupport: {
                    allow: [{
                        name: /.*/,
                        attributes: true,
                        classes: true,
                        styles: true
                    }]
                }

            })
            .then(editor => {
                editor.model.document.on('change:data', () => {
                    @this.set('{{ $name }}', editor.getData());
                    {{-- $('#{{ $name }}').val(editor.getData()); --}}
                })
            })
            .catch(error => {
                console.error(error);
            });
    </script>

    {{-- <script> --}}
    {{-- ClassicEditor --}}
    {{-- .create(document.querySelector('#{{ $name }}')) --}}
    {{-- .then(editor => { --}}
    {{-- document.querySelector("#submit").addEventListener("click", () => { --}}
    {{-- const textareaValue = $("#message").data("message"); --}}
    {{-- eval(textareaValue).set("message", editor.getData()); --}}
    {{-- // @this.set('message', editor.getData()); --}}
    {{-- }); --}}
    {{-- }) --}}
    {{-- .catch(error => { --}}
    {{-- console.error(error); --}}
    {{-- }); --}}

    {{-- </script> --}}
@endpushonce
