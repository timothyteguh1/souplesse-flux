@props([
    'minHeight' => '250px',
    'maxHeight' => '250px',
    'autoHide' => false,
])

<div
    data-simplebar
    data-simplebar-auto-hide="{{ $autoHide ? 'true' : 'false' }}"
    style="min-height: {{ $maxHeight }}; max-height: {{ $maxHeight }}"
    class="px-3 simplebar-scrollable-y"
>
    <div class="simplebar-wrapper" style="margin: 0px -16px">
        <div class="simplebar-height-auto-observer-wrapper">
            <div class="simplebar-height-auto-observer"></div>
        </div>
        <div class="simplebar-mask">
            <div class="simplebar-offset" style="right: 0px; bottom: 0px">
                <div
                    class="simplebar-content-wrapper"
                    tabindex="0"
                    role="region"
                    aria-label="scrollable content"
                    style="height: auto; overflow: hidden scroll"
                >
                    <div class="simplebar-content" style="padding: 0px 16px">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
        <div class="simplebar-placeholder" style="width: 595px; height: 321px"></div>
    </div>
    <div class="simplebar-track simplebar-horizontal" style="visibility: hidden">
        <div class="simplebar-scrollbar simplebar-visible" style="width: 0px; display: none"></div>
    </div>
    <div class="simplebar-track simplebar-vertical" style="visibility: visible">
        <div
            class="simplebar-scrollbar simplebar-visible"
            style="height: 150px; transform: translate3d(0px, 0px, 0px); display: block"
        ></div>
    </div>
</div>
