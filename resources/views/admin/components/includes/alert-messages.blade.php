@props([
    'showFieldErrors' => true,
])

@isset($showFieldErrors)
    @if ($showFieldErrors === true && isset($errors) && $errors->any())
        @php
            $messages = [];
        @endphp

        @foreach ($errors->keys() as $key)
            @if (! str_starts_with($key, 'flash_'))
                @php
                    $messages[] = $errors->get($key)[0];
                @endphp
            @endif
        @endforeach

        @if (count($messages) > 0)
            <x-admin::utils.alert type="danger" class="header-message">
                @foreach ($messages as $message)
                    {{ $message }}
                    <br />
                @endforeach
            </x-admin::utils.alert>
        @endif
    @endif
@endisset

@isset($forceShowFieldErrors)
    @if (count($forceShowFieldErrors) > 0 && isset($errors) && $errors->any())
        @php
            $messages = [];
        @endphp

        @foreach ($errors->keys() as $key)
            @if (! str_starts_with($key, 'flash_') && in_array($key, $forceShowFieldErrors))
                @php
                    $messages[] = $errors->get($key)[0];
                @endphp
            @endif
        @endforeach

        @if (count($messages) > 0)
            <x-admin::utils.alert type="danger" class="header-message">
                @foreach ($messages as $message)
                    {{ $message }}
                    <br />
                @endforeach
            </x-admin::utils.alert>
        @endif
    @endif
@endisset

@if (session()->get('flash_success'))
    <x-admin::utils.alert type="success" class="header-message">
        {{ session()->get('flash_success') }}
    </x-admin::utils.alert>
@endif

@if (session()->get('flash_warning'))
    <x-admin::utils.alert type="warning" class="header-message">
        {{ session()->get('flash_warning') }}
    </x-admin::utils.alert>
@endif

@if (session()->get('flash_info') || session()->get('flash_message'))
    <x-admin::utils.alert type="info" class="header-message">
        {{ session()->get('flash_info') }}
    </x-admin::utils.alert>
@endif

@if (session()->get('flash_danger'))
    <x-admin::utils.alert type="danger" class="header-message">
        {{ session()->get('flash_danger') }}
    </x-admin::utils.alert>
@endif

@if (session()->get('status'))
    <x-admin::utils.alert type="success" class="header-message">
        {{ session()->get('status') }}
    </x-admin::utils.alert>
@endif

@if (session()->get('resent'))
    <x-admin::utils.alert type="success" class="header-message">
        A fresh verification link has been sent to your email address.
    </x-admin::utils.alert>
@endif

@if (session()->get('verified'))
    <x-admin::utils.alert type="success" class="header-message">
        Thank you for verifying your e-mail address.
    </x-admin::utils.alert>
@endif

@error('flash_danger')
    <x-admin::utils.alert type="danger" class="header-message">
        {{ $message }}
    </x-admin::utils.alert>
@enderror

@error('flash_success')
    <x-admin::utils.alert type="success" class="header-message">
        {{ $message }}
    </x-admin::utils.alert>
@enderror

@error('flash_warning')
    <x-admin::utils.alert type="warning" class="header-message">
        {{ $message }}
    </x-admin::utils.alert>
@enderror

@error('flash_info')
    <x-admin::utils.alert type="info" class="header-message">
        {{ $message }}
    </x-admin::utils.alert>
@enderror

@if (session()->get('flash_warning_persist'))
    <x-admin::utils.alert type="warning" class="header-message" :dismissable="false">
        {{ session()->get('flash_warning_persist') }}
    </x-admin::utils.alert>
@endif
