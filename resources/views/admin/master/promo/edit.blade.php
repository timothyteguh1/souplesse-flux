<div>
    @section('title', 'Ubah ' . $obj->nama)

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ $obj->getRouteIndex() }}">{{ $menuTitle }}</a></li>
        <li class="breadcrumb-item"><a href="{{ $obj->getRouteShow() }}">{{ $obj->kode }}</a></li>
    @endsection

    <div class="row">
        <div class="col-12">
            <x-admin::includes.alert-messages />
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <form wire:submit="submitDefault">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <strong>Data Promo</strong>
                        </div>
                        <hr />

                        <div class="row">
                            <div class="col-12">
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Produk
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.select2id :id="'produk_id'" :name="'produk_id'" :options="\App\Utilities\SelectHelpers\Master\SH_Produk::active()"
                                            :defer="false" placeholder="Produk" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Minimum Pembelian (Pcs)
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.number :name="'jumlah_minimum'"
                                            placeholder="Masukkan Jumlah Minimum Pembelian (Pcs)" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Tambahan Diskon (%)
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.number :name="'tambahan_diskon'"
                                            placeholder="Masukkan Tambahan Diskon (%)" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Internal Note</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.textarea :name="'keterangan'" placeholder="Internal Note" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Status
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.select2 :name="'status'" :options="\App\Utilities\SelectHelpers\System\SH_Status::common()" :placeholder="'- Pilih Status -'" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <x-admin::includes.pages.edit-footer-action />
                </div>
            </form>
        </div>
    </div>
    <!--end row-->
</div>
