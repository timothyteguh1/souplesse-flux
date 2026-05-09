<div>
    @section('title', $this->menuTitle)

    <div class="row">
        @can('dashboard.pembelian.jumlah-transaksi')
            <div class="col-12 mb-4">
                <!-- Jumlah Transaksi-->
                <livewire:admin.system.dashboard.pembelian.jumlah-transaksi />
            </div>
        @endcan

        @can('dashboard.pembelian.pembelian-produk')
            <div class="col-12 mb-4">
                <!-- Pembelian Produk-->
                <livewire:admin.system.dashboard.pembelian.pembelian-produk />
            </div>
        @endcan
    </div>
</div>
