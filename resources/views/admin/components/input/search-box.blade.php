@props(['name', 'defer' => true, 'showError' => 'true'])

<div class="search-box">
    <input
        type="search"
        inputmode="search"
        {{ $attributes->merge(['class' => 'form-control search' . ($errors->has($name) ? ' is-invalid' : '')]) }}
        @if ($defer == 'true')
            wire:model="{{ $name }}"
        @else
            wire:model.live="{{ $name }}"
        @endif
    />
    <i class="ri-search-line search-icon"></i>
</div>

@if ($showError == 'true')
    @error($name)
        <span class="invalid-feedback">
            {{ $message }}
        </span>
    @enderror
@endif
