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
                    <div x-data="{ activeTab: 'data_umum' }">
                        <div class="card-body">
                            <ul class="nav nav-custom-light nav-border-top nav-border-top-primary nav-justified rounded card-header-tabs border"
                                role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link" :class="activeTab == 'data_umum' && 'active'"
                                        data-bs-toggle="tab" href="#tabDataUmum" role="tab"
                                        @click="activeTab = 'data_umum'">
                                        Data Umum
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" :class="activeTab == 'lain_lain' && 'active'"
                                        data-bs-toggle="tab" href="#tabLainLain" role="tab"
                                        @click="activeTab = 'lain_lain'">
                                        Lain-lain
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body p-4">
                            <div class="tab-content">
                                <div class="tab-pane" :class="activeTab == 'data_umum' && 'active show'"
                                    id="tabDataUmum" role="tabpanel">
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">
                                                        Nama
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.text :name="'nama'"
                                                            placeholder="Masukkan nama" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">No. Telp Bisnis</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.text :name="'telp'"
                                                            placeholder="Masukkan no telp bisnis" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Handphone</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.text :name="'handphone'"
                                                            placeholder="Masukkan handphone" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Email</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.text :name="'email'"
                                                            placeholder="Masukkan email" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">PKP</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.checkbox :name="'is_pkp'" :value="$is_pkp"
                                                            :defer="false" :inline="true" />
                                                    </div>
                                                </div>

                                                {{-- <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Include PPN</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.checkbox :name="'is_include_ppn'" :value="$is_include_ppn"
                                                            :inline="true" :disabled="!$is_pkp" />
                                                    </div>
                                                </div> --}}
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Alamat</label>
                                                    <div class="col-lg-9">
                                                        <div class="row">
                                                            <div class="col-12 mb-3">
                                                                <x-admin::input.textarea :name="'alamat'"
                                                                    prepend-text="Jalan" placeholder="Masukkan jalan" />
                                                            </div>

                                                            <div class="col-12">
                                                                <x-admin::input.text :name="'kota'"
                                                                    prepend-text="Kota" placeholder="Masukkan kota" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Payment Information</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.textarea :name="'payment_info'"
                                                            placeholder="Payment Information" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Internal Note</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.textarea :name="'keterangan'"
                                                            placeholder="Internal Note" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end tab-pane-->

                                <div class="tab-pane" :class="activeTab == 'lain_lain' && 'active'" id="tabLainLain"
                                    role="tabpanel">
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="row mb-3">
                                                <label class="col-lg-3 col-form-label">Jatuh Tempo</label>
                                                <div class="col-lg-9">
                                                    <x-admin::input.number :name="'jatuh_tempo'" :decimal-scale="0"
                                                        append-text="Hari" placeholder="Masukkan jatuh tempo" />
                                                </div>
                                            </div>

                                            <hr />
                                            <div class="card-title"><strong>Bank Pembayaran</strong></div>
                                            <div class="row mb-3">
                                                <label class="col-lg-3 col-form-label">Bank</label>
                                                <div class="col-lg-9">
                                                    <x-admin::input.text :name="'rekening_bank'"
                                                        placeholder="Masukan Nama Bank" />
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-lg-3 col-form-label">Nomor Rekening</label>
                                                <div class="col-lg-9">
                                                    <x-admin::input.text :name="'rekening_nomor'"
                                                        placeholder="Masukan Nomor Rekening" />
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-lg-3 col-form-label">Atas Nama</label>
                                                <div class="col-lg-9">
                                                    <x-admin::input.text :name="'rekening_nama'"
                                                        placeholder="Masukan Nama" />
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-lg-3 col-form-label">NPWP</label>
                                                <div class="col-lg-9">
                                                    <x-admin::input.npwp :name="'npwp'"
                                                        placeholder="Masukan NPWP" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end tab-pane-->
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
