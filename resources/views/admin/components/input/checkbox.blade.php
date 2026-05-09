@props([
    'name',
    'label' => '',
    'value',
    'defer' => true,
    'inline' => false,
    'formCheckClass' => true,
    'disabled' => false,
    'size' => 'fs-15',
])

<div @class([
    $size,
    'form-check' => $formCheckClass,
    'form-check-inline' => $inline,
])>
    <input
        type="checkbox"
        class="form-check-input"
        {{ $attributes->merge(['class' => 'form-checkbox' . ($errors->has($name) ? ' is-invalid' : '')]) }}
        @if ($defer == 'true')
            wire:model="{{ $name }}"
        @else
            wire:model.live="{{ $name }}"
        @endif
        value="{{ $value }}"
        id="id-{{ $name }}"
        @if($disabled) disabled @endif
    />
    @if (filled($label))
        <label class="form-check-label fw-normal" for="id-{{ $name }}">{{ $label }}</label>
    @endif
</div>

@error($name)
    <span class="invalid-feedback">
        {{ $message }}
    </span>
@enderror
