@props(['dismissable' => true, 'type' => 'success', 'ariaLabel' => __('Close')])

<div
    {{ $attributes->merge(['class' => 'fade show alert-label-icon rounded-label alert alert-' . $type . ($dismissable ? ' alert-dismissible' : '')]) }}
    role="alert"
>
    <i
        @class([
            'label-icon',
            'ri-user-smile-line' => $type === 'primary',
            'ri-notification-off-line' => $type === 'secondary',
            'ri-check-double-line' => $type === 'success',
            'ri-error-warning-line' => $type === 'danger',
            'ri-alert-line' => $type === 'warning',
            'ri-airplay-line' => $type === 'info',
            'ri-mail-line' => $type === 'light',
            'ri-refresh-line' => $type === 'dark',
        ])
    ></i>

    {{ $slot }}
    @if ($dismissable)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ $ariaLabel }}"></button>
    @endif
</div>
