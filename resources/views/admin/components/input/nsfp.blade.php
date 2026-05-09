@props(['name', 'defer' => true, 'showError' => 'true', 'prependText' => null, 'appendText' => null])

{{-- format-ignore-start --}}
@if ($prependText || $appendText)
    <div class="input-group">
@endif

@if ($prependText)
    <span class="input-group-text align-items-start">{{ $prependText }}</span>
@endif

@if ($defer)
    <input x-data="{ value: @entangle($name) }" x-init="() => {
        $el.__cleave = new Cleave($el, {
            blocks: [3, 2, 8],
            delimiters: ['-', '.'],
        });

        $el.__cleave.setRawValue(value);
    }" x-on:blur="value = $el.__cleave.getRawValue()"
        x-effect="if($el.__cleave) $el.__cleave.setRawValue(value)"
        {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }} />
@else
    <input x-data="{ value: @entangle($name).live }" x-init="() => {
        $el.__cleave = new Cleave($el, {
            blocks: [3, 2, 8],
            delimiters: ['-', '.'],
        });

        $el.__cleave.setRawValue(value);
    }" x-on:blur="value = $el.__cleave.getRawValue()"
        x-effect="if($el.__cleave) $el.__cleave.setRawValue(value)"
        {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }} />
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

@pushonce('before-styles')
    <script defer src="{{ asset('assets/js/cleave.js_1.6.0_cleave.min.js') }}"></script>
@endpushonce
