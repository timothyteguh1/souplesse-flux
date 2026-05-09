<x-admin::layouts.error :exception="$exception">
    @section('title', 'Service Unavailable')
    @section('code', '503')
    @section('message', $exception->getMessage() ?: 'Please check back in sometime!')
    @section('image')
        <img src="{{ asset('assets/admin/images/maintenance.png') }}" alt="" class="error-basic-img move-animation" />
    @endsection
</x-admin::layouts.error>
