@props([
    'value' => 0,
    'min' => 0,
    'max' => 100,
    'percent' => true,
    'size' => '',
])

<div class="progress {{ $size }}">
    <div
        class="progress-bar"
        role="progressbar"
        style="width: {{ $value }}%"
        aria-valuenow="{{ $value }}"
        aria-valuemin="{{ $min }}"
        aria-valuemax="{{ $value }}"
    >
        {{ $value }}@if ($percent)%
        @endif
    </div>
</div>
