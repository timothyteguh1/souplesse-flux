@props(['name', 'defer' => true, 'showError' => 'true', 'prependText' => null, 'appendText' => null])

{{-- format-ignore-start --}}
@if ($prependText || $appendText)
    <div class="input-group">
@endif

@if ($prependText)
    <span class="input-group-text align-items-start">{{ $prependText }}</span>
@endif

@if ($defer)
    <input type="text"
        {{ $attributes->merge(['class' => 'form-control ' . ($errors->has($name) ? ' is-invalid' : '')]) }}
        wire:model="{{ $name }}" />
@else
    <input type="text"
        {{ $attributes->merge(['class' => 'form-control ' . ($errors->has($name) ? ' is-invalid' : '')]) }}
        wire:model.live="{{ $name }}" />
@endif

@if ($appendText)
    <span class="input-group-text align-items-start">{{ $appendText }}</span>
@endif

@if ($prependText || $appendText)
    </div>
@endif
{{-- format-ignore-end --}}

@if ($showError == 'true')
    @error($name)
        <span class="invalid-feedback">
            {{ $message }}
        </span>
    @enderror
@endif
