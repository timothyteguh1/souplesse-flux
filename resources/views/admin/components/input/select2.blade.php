@props([
    'id',
    'name',
    'defer' => true,
    'disabled' => false,
    'options' => [],
    'showError' => 'true',
    'placeholder' => '- Pilih -',
    'parent' => '',
    'ajax' => false,
    'allowClear' => true,
    'modalId',
    'modalForm' => '',
    'modalAction' => 'refreshInfo',
    'modalIsRender' => true,
])

@php
    $id = $id ?? $name;
    // fixed id, because it's make responsive a mess
$id = $id == 'status' ? 'statuss' : $id;
    $modalId = $modalId ?? $id;
@endphp

{{-- format-ignore-start --}}
<div class="input-group flex-nowrap">
    <div @class([
        'flex-grow-1',
        'select2-modal' => $modalForm,
        'select2-invalid' => $errors->has($name),
    ])>
        @if ($defer)
            <div x-data="{ value: @entangle($name) }" x-init="() => {
                function selectMatchKeyword(params, data) {
                    // If there are no search terms, return all of the data
                    if ($.trim(params.term) === '') {
                        return data;
                    }
            
                    // Do not display the item if there is no 'text' property
                    if (typeof data.text === 'undefined') {
                        return null;
                    }
            
                    // `params.term` should be the term that is used for searching
                    // `data.text` is the text that is displayed for the data object
            
                    terms = params.term.split(' ');
            
                    for (var i = 0; i < terms.length; i++) {
                        if (data.text.toUpperCase().indexOf(terms[i].toUpperCase()) == -1) {
                            return null;
                        }
                    }
            
                    return data;
                }
            
                obj = $('#{{ $id }}').not('.select2-hidden-accessible')
                    .select2({
                        allowClear: {{ $allowClear ? 'true' : 'false' }},
                        matcher: selectMatchKeyword,
                        @if($parent)
                        dropdownParent: $('#{{ $parent }}'),
                        @endif
                    });
            
                obj.val(value).change();
            
                obj.on('select2:select', (event) => {
                    value = event.target.value;
                });
            
                obj.on('select2:unselect', (event) => {
                    value = null;
                });
            
                obj.on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
            
                    @if($ajax)
                    $('input.select2-search__field').on('input', function(e) {
                        $wire.call('ajax_{{ $id }}', $(this).val());
                    });
                    @endif
                });
            
                Livewire.on('refresh_dropdown_{{ $id }}', parameters => {
                    let items = [];
                    $.each(parameters[0].options, function(key, item) {
                        items.push({
                            text: item.replaceAll('--', '&mdash;'),
                            id: key
                        });
                    });
            
                    $('#{{ $id }}')
                        .empty()
                        .select2({
                            allowClear: {{ $allowClear ? 'true' : 'false' }},
                            matcher: selectMatchKeyword,
                            @if($placeholder)
                            placeholder: '{{ $placeholder }}',
                            @endif
                            @if($parent)
                            dropdownParent: $('#{{ $parent }}'),
                            @endif
                            data: items
                        })
                        .val(parameters.value ?? null).change();
                });
            
                Livewire.on('set_value_dropdown_{{ $id }}', parameters => {
                    $('#{{ $id }}').val(parameters).change();
                });
            
                $watch('value', (newValue) => {
                    $('#{{ $id }}').val(newValue).change();
                });
            }" wire:ignore>
            @else
                <div x-data="{ value: @entangle($name).live }" x-init="() => {
                    function selectMatchKeyword(params, data) {
                        // If there are no search terms, return all of the data
                        if ($.trim(params.term) === '') {
                            return data;
                        }
                
                        // Do not display the item if there is no 'text' property
                        if (typeof data.text === 'undefined') {
                            return null;
                        }
                
                        // `params.term` should be the term that is used for searching
                        // `data.text` is the text that is displayed for the data object
                
                        terms = params.term.split(' ');
                
                        for (var i = 0; i < terms.length; i++) {
                            if (data.text.toUpperCase().indexOf(terms[i].toUpperCase()) == -1) {
                                return null;
                            }
                        }
                
                        return data;
                    }
                
                    obj = $('#{{ $id }}').not('.select2-hidden-accessible')
                        .select2({
                            allowClear: {{ $allowClear ? 'true' : 'false' }},
                            matcher: selectMatchKeyword,
                            @if($parent)
                            dropdownParent: $('#{{ $parent }}'),
                            @endif
                        });
                
                    obj.val(value).change();
                
                    obj.on('select2:select', (event) => {
                        value = event.target.value;
                    });
                
                    obj.on('select2:unselect', (event) => {
                        value = null;
                    });
                
                    obj.on('select2:open', () => {
                        document.querySelector('.select2-search__field').focus();
                    });
                
                    Livewire.on('refresh_dropdown_{{ $id }}', parameters => {
                        let items = [];
                        $.each(parameters[0].options, function(key, item) {
                            items.push({
                                text: item.replace('--', '&mdash;'),
                                id: key
                            });
                        });
                
                        $('#{{ $id }}')
                            .empty()
                            .select2({
                                allowClear: {{ $allowClear ? 'true' : 'false' }},
                                matcher: selectMatchKeyword,
                                @if($placeholder)
                                placeholder: '{{ $placeholder }}',
                                @endif
                                @if($parent)
                                dropdownParent: $('#{{ $parent }}'),
                                @endif
                                data: items
                            })
                            .val(parameters.value ?? null).change();
                    });
                
                    Livewire.on('set_value_dropdown_{{ $id }}', parameters => {
                        $('#{{ $id }}').val(parameters).change();
                    });
                
                    $watch('value', (newValue) => {
                        $('#{{ $id }}').val(newValue).change();
                    });
                }" wire:ignore>
        @endif
        <select id="{{ $id }}" data-placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => 'form-control select2' . ($errors->has($name) ? ' is-invalid' : '')]) }}
            @if ($disabled) disabled @endif>
            @foreach ($options as $key => $value)
                <option value="{{ $key }}">
                    {!! str_replace('--', '&mdash;', $value) !!}
                </option>
            @endforeach
        </select>
    </div>
</div>

@if ($modalForm)
    <span class="btn btn-primary" role="button" tabindex="0"
        wire:click="$dispatchTo('{{ $modalForm }}', '{{ $modalAction }}', { params: { form_id: 'Modal{{ $modalId }}' } })"
        wire:keydown.enter="$dispatchTo('{{ $modalForm }}', '{{ $modalAction }}')">
        <i class="mdi mdi-plus-circle"></i>
    </span>
@endif
</div>
{{-- format-ignore-end --}}

@if ($showError == 'true')
    @error($name)
        <span class="invalid-feedback d-block">
            {{ $message }}
        </span>
    @enderror
@endif

@if ($modalForm && $modalIsRender)
    <div wire:ignore>
        <x-admin::utils.modal-dialog :id="'Modal' . $modalId">
            @livewire($modalForm)
        </x-admin::utils.modal-dialog>
    </div>
@endif

@pushonce('before-styles')
    <link href="{{ asset('assets/libs/select2/select2@4.1.0-rc.min.css') }}" rel="stylesheet" />
@endpushonce

@pushonce('after-scripts')
    <script src="{{ asset('assets/libs/select2/select2@4.1.0-rc.min.js') }}"></script>
@endpushonce
