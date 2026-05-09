@props([
    'value' => null,
    'isNumber' => false,
    'prependText' => null,
    'appendText' => null,
])

{{-- format-ignore-start --}}
@if ($prependText || $appendText)
    <div class="input-group">
@endif

@if ($prependText)
    <span class="input-group-text align-items-start">{{ $prependText }}</span>
@endif

<input type="text" {{ $attributes->merge(['class' => 'form-control ']) }}
    value="{{ $isNumber ? _number($value) : $value }}" disabled />

@if ($appendText)
    <span class="input-group-text align-items-start">{{ $appendText }}</span>
@endif

@if ($prependText || $appendText)
    </div>
@endif
{{-- format-ignore-end --}}
