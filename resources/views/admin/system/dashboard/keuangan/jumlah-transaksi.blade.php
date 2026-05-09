<div>
    @section('title', 'Dashboard')

    <div class="row mb-3">
        <div class="col-12 col-lg-4 mb-2 mb-lg-0">
            <x-admin::input.date-range :name="'tanggal'" placeholder="Masukkan Tanggal" :defer="false" />
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 col-12">
            <x-admin::utils.dashboard-card title="Kas Masuk" :value="$jumlah_kas_masuk" />
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <x-admin::utils.dashboard-card title="Kas Keluar" :value="$jumlah_kas_keluar" />
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <x-admin::utils.dashboard-card title="Transfer Kas" :value="$jumlah_transfer_kas" />
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <x-admin::utils.dashboard-card title="Pemberian Kas Bon" :value="$jumlah_kas_bon" />
        </div>
    </div>
</div>
