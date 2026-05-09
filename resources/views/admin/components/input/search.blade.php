@props(['name', 'defer' => true, 'showError' => 'true'])

<input
    type="search"
    inputmode="search"
    {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}
    @if ($defer == 'true')
        wire:model="{{ $name }}"
    @else
        wire:model.live="{{ $name }}"
    @endif
/>

@if ($showError == 'true')
    @error($name)
        <span class="invalid-feedback">
            {{ $message }}
        </span>
    @enderror
@endif
