<x-admin::layouts.error :exception="$exception">
    @section('title', 'Too Many Requests')
    @section('code', '429')
    @section('message', $exception->getMessage() ?: 'Too many request to page that you are looking for!')
</x-admin::layouts.error>
