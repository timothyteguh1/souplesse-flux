<div>
    @section('title', 'Dashboard')

    <div class="row mb-3">
        <div class="col-12 col-lg-4 mb-2 mb-lg-0">
            <x-admin::input.date-range :name="'tanggal'" placeholder="Masukkan Tanggal" :defer="false" />
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 col-12">
            <x-admin::utils.dashboard-card title="Pesanan" :value="$jumlah_pesanan" />
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <x-admin::utils.dashboard-card title="Surat Jalan" :value="$jumlah_surat_jalan" />
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <x-admin::utils.dashboard-card title="Faktur" :value="$jumlah_faktur" />
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <x-admin::utils.dashboard-card title="Retur" :value="$jumlah_retur" />
        </div>
    </div>
</div>
