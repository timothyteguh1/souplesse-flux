<x-admin::layouts.error :exception="$exception">
    @section('title', 'Forbidden')
    @section('code', '403')
    @section('message', $exception->getMessage() ?: 'The page you are looking for is forbidden!')
</x-admin::layouts.error>
