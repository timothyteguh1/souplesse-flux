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
                        {{-- <div class="row mb-3"> --}}
                        {{-- <label class="col-lg-3 col-form-label">Kode</label> --}}
                        {{-- <div class="col-lg-9"> --}}
                        {{-- <x-admin::input.text :name="'kode'" placeholder="(OTOMATIS)" disabled /> --}}
                        {{-- </div> --}}
                        {{-- </div> --}}

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
                            <label class="col-lg-3 col-form-label">User</label>
                            <div class="col-lg-9">
                                <x-admin::input.select2id :id="'user_id'" :name="'user_id'" :options="\App\Utilities\SelectHelpers\System\SH_User::karyawan()"
                                    placeholder="- Pilih User -" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">
                                Tanggal Masuk
                            </label>
                            <div class="col-lg-9">
                                <x-admin::input.date-time :name="'tanggal_masuk'" placeholder="Masukkan Tanggal Masuk" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">
                                No KTP
                            </label>
                            <div class="col-lg-9">
                                <x-admin::input.text :name="'no_ktp'" placeholder="Masukkan no ktp" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Level</label>
                            <div class="col-lg-9">
                                <x-admin::input.text :name="'level'" placeholder="Masukkan level" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Komisi (%)</label>
                            <div class="col-lg-9">
                                <x-admin::input.number :name="'komisi'" placeholder="Masukkan komisi" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Telp</label>
                            <div class="col-lg-9">
                                <x-admin::input.text :name="'telp'" placeholder="Masukkan telp" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Handphone</label>
                            <div class="col-lg-9">
                                <x-admin::input.text :name="'handphone'" placeholder="Masukkan handphone" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Email</label>
                            <div class="col-lg-9">
                                <x-admin::input.text :name="'email'" placeholder="Masukkan email" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Alamat</label>
                            <div class="col-lg-9">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <x-admin::input.textarea :name="'alamat'" prepend-text="Jalan"
                                            placeholder="Masukkan jalan" />
                                    </div>

                                    <div class="col-12">
                                        <x-admin::input.text :name="'kota'" prepend-text="Kota"
                                            placeholder="Masukkan kota" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Internal Note</label>
                            <div class="col-lg-9">
                                <x-admin::input.textarea :name="'keterangan'" placeholder="Masukkan internal note" />
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
