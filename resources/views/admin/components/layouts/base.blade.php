<!DOCTYPE html>
<html
    lang="id"
    data-layout="vertical"
    data-bs-theme="light"
    data-topbar="light"
    data-sidebar="dark"
    data-sidebar-size="lg"
    data-sidebar-image="none"
    data-preloader="disable"
    class="notranslate"
    translate="no"
>
    <head>
        <title>@yield('title', 'Title') - {{ config('app.name') }}</title>
        <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.ico') }}" />
        <link
            rel="apple-touch-icon"
            sizes="180x180"
            href="{{ asset('assets/images/favicon/apple-touch-icon.png') }}"
        />
        <link
            rel="icon"
            type="image/png"
            sizes="32x32"
            href="{{ asset('assets/images/favicon/favicon-32x32.png') }}"
        />
        <link
            rel="icon"
            type="image/png"
            sizes="16x16"
            href="{{ asset('assets/images/favicon/favicon-16x16.png') }}"
        />
        <link rel="manifest" href="{{ asset('assets/images/favicon/site.webmanifest') }}" />

        <x-admin::includes.meta />

        @stack('before-styles')
        <x-admin::includes.styles />
        @stack('after-styles')
    </head>

    <body {{ $attributes }}>
        {{ $slot }}

        @stack('before-scripts')
        <x-admin::includes.scripts />
        @stack('after-scripts')
    </body>
</html>
