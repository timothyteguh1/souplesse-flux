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
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Kode</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.readonly :value="$kode" :name="'kode'" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Nama
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.text :name="'nama'" placeholder="Masukkan nama" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Kategori
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.select2id :id="'kategori_produk_id'" :name="'kategori_produk_id'" :options="\App\Utilities\SelectHelpers\Master\SH_KategoriProduk::active()"
                                            placeholder="- Pilih Kategori Produk -" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Jenis
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.select2id :id="'jenis_produk_id'" :name="'jenis_produk_id'" :options="\App\Utilities\SelectHelpers\Master\SH_JenisProduk::active()"
                                            placeholder="- Pilih Jenis Produk -" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Model
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.select2id :id="'model_produk_id'" :name="'model_produk_id'"
                                            :options="\App\Utilities\SelectHelpers\Master\SH_ModelProduk::active()" placeholder="- Pilih Model Produk -" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Satuan</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.readonly :value="$satuan_nama" :name="'satuan_nama'" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Harga Beli</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.number :name="'harga_beli'" placeholder="Masukkan harga beli" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Harga Jual</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.number :name="'harga_jual'" placeholder="Masukkan harga jual" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Min. Order</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.number :name="'minimal_order'"
                                            placeholder="Masukkan minimal order" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Stok Minimum</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.number :name="'stok_minimum'" placeholder="Masukkan stok minimum" />
                                        <small class="text-muted">* Dalam satuan dasar</small>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Status
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.select2 :name="'status'" :options="\App\Utilities\SelectHelpers\System\SH_Status::common()"
                                            :placeholder="'- Pilih Status -'" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Deskripsi</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.textarea :name="'deskripsi'" placeholder="Masukkan deskripsi" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Internal Note</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.textarea :name="'keterangan'"
                                            placeholder="Masukkan internal note" />
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
