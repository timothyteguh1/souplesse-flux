<div>
    @section('title', $menuTitle)

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ $model::routeIndex() }}">{{ $menuTitle }}</a></li>
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
                                    <label class="col-lg-3 col-form-label">PKP</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.checkbox :name="'is_pkp'" :value="$is_pkp" :defer="false"
                                            :inline="true" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Include PPN</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.checkbox :name="'is_include_ppn'" :value="$is_include_ppn" :inline="true"
                                            :disabled="!$is_pkp" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">PPN Percent</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.number :name="'ppn_percent'" :disabled="true"
                                            placeholder="Masukkan PPN Percent" />
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
                                        <x-admin::input.file :name="'input_logo'" class="form-control"
                                            placeholder="Masukkan logo" accept="image/*" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="row mb-4">
                                    <div class="card-title"><strong>KTP Pemilik</strong></div>
                                    <div class="row mb-3">
                                        <label class="col-lg-3 col-form-label">Nama</label>
                                        <div class="col-lg-9">
                                            <x-admin::input.text :name="'ktp_nama'"
                                                placeholder="Masukan Nama KTP Pemilik" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-lg-3 col-form-label">No. KTP</label>
                                        <div class="col-lg-9">
                                            <x-admin::input.text :name="'ktp_nomor'"
                                                placeholder="Masukan Nomor KTP Pemilik" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-lg-3 col-form-label">Foto KTP</label>
                                        <div class="col-lg-9">
                                            <x-admin::input.file :name="'input_ktp_foto'" class="form-control"
                                                placeholder="Masukkan Foto KTP Pemilik" accept="image/*" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="row mb-4">
                                    <div class="card-title"><strong>NPWP Pemilik</strong></div>
                                    <div class="row mb-3">
                                        <label class="col-lg-3 col-form-label">Nama</label>
                                        <div class="col-lg-9">
                                            <x-admin::input.text :name="'npwp_nama'"
                                                placeholder="Masukan Nama NPWP Pemilik" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-lg-3 col-form-label">No. NPWP</label>
                                        <div class="col-lg-9">
                                            <x-admin::input.text :name="'npwp_nomor'"
                                                placeholder="Masukan Nomor NPWP Pemilik" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-lg-3 col-form-label">Foto NPWP</label>
                                        <div class="col-lg-9">
                                            <x-admin::input.file :name="'input_npwp_foto'" class="form-control"
                                                placeholder="Masukkan Foto NPWP Pemilik" accept="image/*" />
                                        </div>
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
