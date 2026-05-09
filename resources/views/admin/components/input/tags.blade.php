@props([
    'id' => \Illuminate\Support\Str::random(5),
    'name',
    'defer' => true,
    'options' => [],
    'showError' => 'true',
    'placeholder' => '- Pilih -',
    'parent' => '',
])

{{-- format-ignore-start --}}
@if ($defer)
    <div x-data="{ model: @entangle($name) }" x-init="() => {
        function selectMatchKeyword(params, data) {
            // If there are no search terms, return all of the data
            if ($.trim(params.term) === '') {
                return data
            }

            // Do not display the item if there is no 'text' property
            if (typeof data.text === 'undefined') {
                return null
            }

            // `params.term` should be the term that is used for searching
            // `data.text` is the text that is displayed for the data object

            terms = params.term.split(' ')

            for (var i = 0; i < terms.length; i++) {
                if (data.text.toUpperCase().indexOf(terms[i].toUpperCase()) == -1) {
                    return null
                }
            }

            return data
        }

        obj = $('#{{ $id }}').not('.select2-hidden-accessible').select2({
            allowClear: true,
            matcher: selectMatchKeyword,
            multiple: true,
        })

        obj.on('change', (event) => {
            var obj2 = $('#{{ $id }}').select2('data');
            values = obj2.map(x => x.id);

            if (values.length > 0) {
                $('#select2-{{ $id }}-container').show();
            } else {
                $('#select2-{{ $id }}-container').hide();
            }
        })

        obj.val(model).change();

        obj.on('select2:select', (event) => {
            var obj2 = $('#{{ $id }}').select2('data');
            values = obj2.map(x => x.id);

            if (values.length > 0) {
                model = values;
            } else {
                model = [];
            }
        });

        obj.on('select2:unselect', (event) => {
            var obj2 = $('#{{ $id }}').select2('data');
            values = obj2.map(x => x.id);

            if (values.length > 0) {
                model = values;
            } else {
                model = [];
            }
        });

        obj.on('select2:open', () => {
            document.querySelector('#{{ $id }}').focus();
        });

        $watch('model', (newValue) => {
            $('#{{ $id }}').val(newValue).change();
        });
    }" wire:ignore>
    @else
        <div x-data="{ model: @entangle($name).live }" x-init="() => {
            function selectMatchKeyword(params, data) {
                // If there are no search terms, return all of the data
                if ($.trim(params.term) === '') {
                    return data
                }

                // Do not display the item if there is no 'text' property
                if (typeof data.text === 'undefined') {
                    return null
                }

                // `params.term` should be the term that is used for searching
                // `data.text` is the text that is displayed for the data object

                terms = params.term.split(' ')

                for (var i = 0; i < terms.length; i++) {
                    if (data.text.toUpperCase().indexOf(terms[i].toUpperCase()) == -1) {
                        return null
                    }
                }

                return data
            }

            obj = $('#{{ $id }}').not('.select2-hidden-accessible').select2({
                allowClear: true,
                matcher: selectMatchKeyword,
                multiple: true,
            })

            obj.on('change', (event) => {
                var obj2 = $('#{{ $id }}').select2('data');
                values = obj2.map(x => x.id);

                if (values.length > 0) {
                    $('#select2-{{ $id }}-container').show();
                } else {
                    $('#select2-{{ $id }}-container').hide();
                }
            })

            obj.val(model).change();

            obj.on('select2:select', (event) => {
                var obj2 = $('#{{ $id }}').select2('data');
                values = obj2.map(x => x.id);

                if (values.length > 0) {
                    model = values;
                } else {
                    model = [];
                }
            });

            obj.on('select2:unselect', (event) => {
                var obj2 = $('#{{ $id }}').select2('data');
                values = obj2.map(x => x.id);

                if (values.length > 0) {
                    model = values;
                } else {
                    model = [];
                }
            });

            obj.on('select2:open', () => {
                document.querySelector('#{{ $id }}').focus();
            });

            $watch('model', (newValue) => {
                $('#{{ $id }}').val(newValue).change();
            });
        }" wire:ignore>
@endif

<select id="{{ $id }}" data-placeholder="{{ $placeholder }}"
    {{ $attributes->merge(['class' => 'form-control select2' . ($errors->has($name) ? ' is-invalid' : '')]) }}
    multiple="multiple">
    @foreach ($options as $key => $value)
        <option value="{{ $key }}">
            {!! str_replace('--', '&mdash;', $value) !!}
        </option>
    @endforeach
</select>
</div>
{{-- format-ignore-end --}}

@if ($showError == 'true')
    @error($name)
        <span class="invalid-feedback d-block">
            {{ $message }}
        </span>
    @enderror
@endif

@pushonce('before-styles')
    <link href="{{ asset('assets/libs/select2/select2@4.1.0-rc.min.css') }}" rel="stylesheet" />
@endpushonce

@pushonce('after-scripts')
    <script src="{{ asset('assets/libs/select2/select2@4.1.0-rc.min.js') }}"></script>
@endpushonce
