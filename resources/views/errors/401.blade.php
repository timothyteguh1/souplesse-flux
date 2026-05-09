<x-admin::layouts.error :exception="$exception">
    @section('title', 'Unauthorized')
    @section('code', '401')
    @section('message', $exception->getMessage() ?: 'The page you are looking for need authorization!')
</x-admin::layouts.error>
