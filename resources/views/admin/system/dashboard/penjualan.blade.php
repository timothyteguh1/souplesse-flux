<div>
    @section('title', $this->menuTitle)

    <div class="row">
        @can('dashboard.penjualan.jumlah-transaksi')
            <div class="col-12 mb-4">
                <!-- Jumlah Transaksi-->
                <livewire:admin.system.dashboard.penjualan.jumlah-transaksi />
            </div>
        @endcan

        @can('dashboard.penjualan.penjualan-produk')
            <div class="col-12 mb-4">
                <!-- Penjualan Produk-->
                <livewire:admin.system.dashboard.penjualan.penjualan-produk />
            </div>
        @endcan

        {{-- @can('dashboard.penjualan.penjualan-produk-per-jenis-transaksi')
            <div class="col-12 mb-4">
                <!-- Penjualan Produk per Jenis Transaksi-->
                <livewire:admin.system.dashboard.penjualan.penjualan-produk-per-jenis-transaksi />
            </div>
        @endcan --}}
    </div>
</div>
