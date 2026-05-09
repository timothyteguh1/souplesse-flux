<x-admin::layouts.error :exception="$exception">
    @section('title', 'Payment Required')
    @section('code', '402')
    @section('message', $exception->getMessage() ?: 'The page you are looking for require payment!')
</x-admin::layouts.error>
