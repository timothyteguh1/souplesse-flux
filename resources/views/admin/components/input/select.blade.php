@props(['name', 'defer' => 'true', 'options' => [], 'placeholder' => '- Pilih -', 'showError' => 'true'])

<select
    id="{{ $name }}"
    {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}
    @if ($defer == 'true')
        wire:model="{{ $name }}"
    @else
        wire:model.live="{{ $name }}"
    @endif
>
    @foreach ($options as $key => $value)
        <option value="{{ $key }}">
            {!! str_replace('--', '&mdash;', $value) !!}
        </option>
    @endforeach
</select>

@if ($showError == 'true')
    @error($name)
        <span class="invalid-feedback">
            {{ $message }}
        </span>
    @enderror
@endif
