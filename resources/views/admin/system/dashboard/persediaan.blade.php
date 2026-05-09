<div>
    @section('title', $this->menuTitle)

    <div class="row">
        @can('dashboard.persediaan.penyesuaian-persediaan')
            <div class="col-12 mb-4">
                <!-- Penyesuaian Persediaan-->
                <livewire:admin.system.dashboard.persediaan.penyesuaian-persediaan />
            </div>
        @endcan

        @can('dashboard.persediaan.total-nilai-persediaan')
            <div class="col-12 mb-4">
                <!-- Total Nilai Persediaan-->
                <livewire:admin.system.dashboard.persediaan.total-nilai-persediaan />
            </div>
        @endcan
    </div>
</div>
