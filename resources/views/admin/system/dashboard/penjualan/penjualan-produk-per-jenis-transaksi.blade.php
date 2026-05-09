<div>
    <div class="card">
        <div class="card-header bg-primary">
            <h5 class="card-title mb-0 fs-14 text-white">Penjualan Produk Per Jenis Transaksi</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-4 mb-2 mb-lg-0">
                    <x-admin::input.select2
                        :id="'tahun_penjualan_barang_per_jenis_transaksi'"
                        :name="'tahun'"
                        :options="$this->optionsTahun"
                        :defer="false"
                        :placeholder="'- Semua Tahun -'"
                    />
                </div>
                <div class="col-12 col-lg-4 mb-2 mb-lg-0">
                    <x-admin::input.select2
                        :id="'customer_id_penjualan_barang_per_jenis_transaksi'"
                        :name="'customer_id'"
                        :options="$this->optionsCustomerId"
                        :defer="false"
                        :placeholder="'- Semua Customer -'"
                    />
                </div>
                <div class="col-12 col-lg-4 mb-2 mb-lg-0">
                    <x-admin::input.select2
                        :id="'gudang_id_penjualan_barang_per_jenis_transaksi'"
                        :name="'gudang_id'"
                        :options="$this->optionsGudangId"
                        :defer="false"
                        :placeholder="'- Semua Gudang -'"
                    />
                </div>
            </div>
            <div style="height: 20rem" class="mt-3">
                <livewire:livewire-column-chart key="{{ $chart->reactiveKey() }}" :column-chart-model="$chart" />
            </div>
        </div>
    </div>
</div>
