<div>
    @section('title', $this->menuTitle)

    <div class="row">
        @can('dashboard.keuangan.jumlah-transaksi')
            <div class="col-12 mb-4">
                <!-- Jumlah Transaksi-->
                <livewire:admin.system.dashboard.keuangan.jumlah-transaksi />
            </div>
        @endcan

        @can('dashboard.keuangan.saldo-kas')
            <div class="col-12 mb-4">
                <!-- Saldo Kas -->
                <livewire:admin.system.dashboard.keuangan.saldo-kas />
            </div>
        @endcan

        @can('dashboard.keuangan.kas-masuk')
            <div class="col-12 mb-4">
                <!-- Kas Masuk-->
                <livewire:admin.system.dashboard.keuangan.kas-masuk />
            </div>
        @endcan

        @can('dashboard.keuangan.kas-keluar')
            <div class="col-12 mb-4">
                <!-- Kas Keluar-->
                <livewire:admin.system.dashboard.keuangan.kas-keluar />
            </div>
        @endcan
    </div>
</div>
