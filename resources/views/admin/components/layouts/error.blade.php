@props([
    'exception' => null,
    'showException' => false,
])

<x-admin::layouts.auth-base>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center pt-4">
                    <div class="">
                        @hasSection('image')
                            @yield('image')
                        @else
                            <img
                                src="{{ asset('assets/admin/images/error.svg') }}"
                                alt=""
                                class="error-basic-img move-animation"
                            />
                        @endif
                    </div>
                    <div class="mt-n4">
                        <h1 class="display-1 fw-medium">@yield('code')</h1>
                        <h3 class="text-uppercase">
                            Sorry,
                            @yield('title', 'Error')
                            .
                        </h3>
                        <p class="text-muted mb-0">@yield('message')</p>
                        @if ($showException && $exception && $exception->getMessage())
                            <p class="text-muted mb-0">** {{ $exception->getMessage() }} **</p>
                        @endif

                        <a href="{{ route(_get_homepage_route()) }}" class="btn btn-success mt-4">
                            <i class="mdi mdi-home me-1"></i>
                            Back to home
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{ $slot }}
    </div>
    <!-- end container -->
</x-admin::layouts.auth-base>
