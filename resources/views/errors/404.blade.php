<x-admin::layouts.error :exception="$exception">
    @section('title', 'Page Not Found')
    @section('code', '404')
    @section('message', $exception->getMessage() ?: 'The page you are looking for not available!')
</x-admin::layouts.error>
