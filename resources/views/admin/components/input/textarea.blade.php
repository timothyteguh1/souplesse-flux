@props(['name', 'defer' => true, 'rows' => 3, 'showError' => 'true', 'prependText' => null, 'appendText' => null])

{{-- format-ignore-start --}}
@if ($prependText || $appendText)
    <div class="input-group">
        @endif

        @if ($prependText)
            <span class="input-group-text align-items-start">{{ $prependText }}</span>
        @endif

        <textarea {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}
                  @if ($defer == 'true') wire:model="{{ $name }}"
                  @else
                      wire:model.live="{{ $name }}" @endif
                  rows="{{ $rows }}"></textarea>

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
