@props(['name', 'defer' => 'true', 'showError' => 'true', 'placeholder' => ''])

<input
    @if (!empty($placeholder)) placeholder="{{ $placeholder }}" @endif
    x-data="{ value: @entangle($name) }"
    x-init="
        flatpickr($el, {
            mode: 'range',
            allowInput: true,
            disableMobile: 'true',
            dateFormat: '{{ App\Utilities\Constants\Const_Date::DATE_FORMAT_JS }}',
            locale: {
                weekdays: {
                    shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    longhand: [
                        'Minggu',
                        'Senin',
                        'Selasa',
                        'Rabu',
                        'Kamis',
                        'Jumat',
                        'Sabtu',
                    ],
                },
                months: {
                    shorthand: [
                        'Jan',
                        'Feb',
                        'Mar',
                        'Apr',
                        'Mei',
                        'Jun',
                        'Jul',
                        'Agu',
                        'Sep',
                        'Okt',
                        'Nov',
                        'Des',
                    ],
                    longhand: [
                        'Januari',
                        'Februari',
                        'Maret',
                        'April',
                        'Mei',
                        'Juni',
                        'Juli',
                        'Agustus',
                        'September',
                        'Oktober',
                        'November',
                        'Desember',
                    ],
                },
                firstDayOfWeek: 1,
                ordinal: function () {
                    return ''
                },
                time_24hr: true,
                rangeSeparator: ' - ',
            },
        })
    "
    type="text"
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

@pushonce('before-styles')
    <link href="{{ asset('assets/admin/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpushonce

@pushonce('after-scripts')
    <script src="{{ asset('assets/admin/libs/flatpickr/flatpickr.min.js') }}"></script>
@endpushonce
