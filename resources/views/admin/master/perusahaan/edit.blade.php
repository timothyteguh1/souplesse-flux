<div>
    @section('title', 'Ubah ' . $obj->kode)

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
                        <div class="row">
                            <div class="card-title">
                                <strong>Data Perusahaan</strong>
                            </div>
                            <hr />
                            <div class="col-md-6 col-12">
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Kode
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.text :name="'kode'" placeholder="Masukkan kode" />
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
                                    <label class="col-lg-3 col-form-label">Telp</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.text :name="'telp'" placeholder="Masukkan telp" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">E-Mail</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.email :name="'email'" placeholder="Masukkan email" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Plan
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.select2id
                                            :id="'plan_id'"
                                            :name="'plan_id'"
                                            :defer="false"
                                            :options="\App\Utilities\SelectHelpers\System\SH_Plan::active()"
                                            placeholder="- Pilih Plan -"
                                        />
                                    </div>
                                </div>

                                <div class="row">
                                    <label class="col-lg-3 col-form-label">
                                        Status
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.select2
                                            :name="'status'"
                                            :options="\App\Utilities\SelectHelpers\System\SH_Status::common()"
                                            :placeholder="'- Pilih Status -'"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Alamat</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.textarea :name="'alamat'" placeholder="Masukkan alamat" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Kota</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.text :name="'kota'" placeholder="Masukkan kota" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Logo</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.file
                                            :name="'logo'"
                                            class="form-control"
                                            placeholder="Masukkan logo"
                                            accept="image/*"
                                        />
                                        <small class="text-muted">* Upload jika ingin mengganti logo</small>
                                    </div>
                                </div>
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
