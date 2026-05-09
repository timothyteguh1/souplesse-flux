<div>
    @section('title', $menuTitle)

    @section('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ $model::routeIndex() }}">{{ $menuTitle }}</a>
        </li>
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
                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Kode</label>
                            <div class="col-lg-9">
                                <x-admin::input.text :name="'kode'" placeholder="(OTOMATIS)" />
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
                                Jumlah Cabang
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9">
                                <x-admin::input.number
                                    :name="'jumlah_cabang'"
                                    :decimalScale="0"
                                    placeholder="Masukkan Jumlah Cabang"
                                />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">
                                Jumlah User
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9">
                                <x-admin::input.number
                                    :name="'jumlah_user'"
                                    :decimalScale="0"
                                    placeholder="Masukkan Jumlah User"
                                />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">
                                Harga
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9">
                                <x-admin::input.number :name="'harga'" placeholder="Masukkan Harga" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">
                                Masa Aktif Hari
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9">
                                <x-admin::input.number
                                    :name="'masa_aktif_hari'"
                                    :decimalScale="0"
                                    placeholder="Masukkan Masa Aktif Hari"
                                />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Keterangan</label>
                            <div class="col-lg-9">
                                <x-admin::input.textarea :name="'keterangan'" placeholder="Keterangan" />
                            </div>
                        </div>
                    </div>

                    <x-admin::includes.pages.create-footer-action />
                </div>
            </form>
        </div>
    </div>
    <!--end row-->
</div>
