@props([
    'id' => \Illuminate\Support\Str::random(5),
    'name',
    'label' => '',
    'value',
    'defer' => true,
    'inline' => false,
    'formCheckClass' => true,
])

<div @class([
    'form-check' => $formCheckClass,
    'form-check-inline' => $inline,
])>
    <input
        type="radio"
        name="{{ $name }}"
        id="{{ $id }}"
        {{ $attributes->merge(['class' => 'form-check-input' . ($errors->has($name) ? ' is-invalid' : '')]) }}
        @if ($defer == 'true')
            wire:model="{{ $name }}"
        @else
            wire:model.live="{{ $name }}"
        @endif
        value="{{ $value }}"
    />

    <label class="form-check-label" for="{{ $id }}">{{ $label }}</label>
</div>

@error($name)
    <span class="invalid-feedback">
        {{ $message }}
    </span>
@enderror
