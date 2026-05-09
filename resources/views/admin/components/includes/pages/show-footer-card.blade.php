@props([
    'obj',
    'showActivityHistory' => true,
    'showJurnal' => false,
    'showStockCard' => false,
])

@if ($showJurnal)
    <div class="row">
        <div class="col-12">
            <x-admin::includes.pages.show-footer-journals-history :obj="$obj" />
        </div>
    </div>
@endif

@if ($showStockCard)
    <div class="row">
        <div class="col-12">
            <x-admin::includes.pages.show-footer-stock-cards-history :obj="$obj" />
        </div>
    </div>
@endif

@if ($showActivityHistory)
    @can($obj->getPermissionActivityLog())
        <div class="row">
            <div class="col-12">
                <x-admin::includes.pages.show-footer-activity-history :obj="$obj" />
            </div>
        </div>
    @endcan
@endif
