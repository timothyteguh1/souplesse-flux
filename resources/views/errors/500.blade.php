<x-admin::layouts.error :exception="$exception">
    @section('title', 'Server Error')
    @section('code', '500')
    @section('message', $exception->getMessage() ?: "We're not exactly sure what happened, but our servers say something
    is wrong.")
</x-admin::layouts.error>
