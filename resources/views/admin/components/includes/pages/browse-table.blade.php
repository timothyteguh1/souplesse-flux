@props([
    'head',
    'data',
    'pagination' => true,
])

<div class="card-body">
    <div>
        <div class="table-responsive" style="min-height: 300px">
            <table class="table align-middle table-nowrap mb-0 table-bordered">
                <thead class="table-light">
                    {{ $head }}
                </thead>

                <tbody>
                    {{ $slot }}
                </tbody>
            </table>
        </div>

        @if ($pagination)
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="flex-grow-1 pagination-separated">
                    {{ $data->links() }}
                </div>

                <div>
                    {{ __('Tampilan :first - :last dari total :total data', ['first' => $data->count() ? $data->firstItem() : 0, 'last' => $data->count() ? $data->lastItem() : 0, 'total' => _number($data->total())]) }}
                </div>
            </div>
        @endif
    </div>
</div>
