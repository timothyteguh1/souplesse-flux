<x-admin::layouts.error :exception="$exception">
    @section('title', 'Page Expired')
    @section('code', '419')
    @section('message', $exception->getMessage() ?: 'The page you are looking for is expired!')
</x-admin::layouts.error>
