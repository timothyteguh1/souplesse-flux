<div>
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <form wire:submit="submit">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Customer</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body border-top border-bottom">
                    <div class="row">
                        <div class="col-12">
                            <x-admin::includes.alert-messages />
                        </div>
                    </div>

                    <div class="row">
                        <div x-data="{ activeTab: 'data_umum' }">
                            <div>
                                <ul
                                    class="nav nav-custom-light nav-border-top nav-border-top-primary nav-justified rounded card-header-tabs border"
                                    role="tablist"
                                >
                                    <li class="nav-item">
                                        <a
                                            class="nav-link"
                                            :class="activeTab == 'data_umum' && 'active'"
                                            data-bs-toggle="tab"
                                            href="#tabDataUmum"
                                            role="tab"
                                            @click="activeTab = 'data_umum'"
                                        >
                                            Data Umum
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a
                                            class="nav-link"
                                            :class="activeTab == 'lain_lain' && 'active'"
                                            data-bs-toggle="tab"
                                            href="#tabLainLain"
                                            role="tab"
                                            @click="activeTab = 'lain_lain'"
                                        >
                                            Lain-lain
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="pt-5">
                                <div class="tab-content">
                                    <div
                                        class="tab-pane"
                                        :class="activeTab == 'data_umum' && 'active show'"
                                        id="tabDataUmum"
                                        role="tabpanel"
                                    >
                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="row mb-3">
                                                        <label class="col-lg-3 col-form-label">
                                                            Nama
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="col-lg-9">
                                                            <x-admin::input.text
                                                                :name="'nama'"
                                                                placeholder="Masukkan nama"
                                                            />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label class="col-lg-3 col-form-label">No. Telp Bisnis</label>
                                                        <div class="col-lg-9">
                                                            <x-admin::input.text
                                                                :name="'telp'"
                                                                placeholder="Masukkan no telp bisnis"
                                                            />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label class="col-lg-3 col-form-label">Handphone</label>
                                                        <div class="col-lg-9">
                                                            <x-admin::input.text
                                                                :name="'handphone'"
                                                                placeholder="Masukkan handphone"
                                                            />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label class="col-lg-3 col-form-label">No. Whatsapp</label>
                                                        <div class="col-lg-9">
                                                            <x-admin::input.text
                                                                :name="'whatsapp'"
                                                                placeholder="Masukkan nama"
                                                            />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label class="col-lg-3 col-form-label">Email</label>
                                                        <div class="col-lg-9">
                                                            <x-admin::input.text
                                                                :name="'email'"
                                                                placeholder="Masukkan email"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="row mb-3">
                                                        <label class="col-lg-3 col-form-label">Alamat</label>
                                                        <div class="col-lg-9">
                                                            <div class="row">
                                                                <div class="col-12 mb-3">
                                                                    <x-admin::input.textarea
                                                                        :name="'alamat'"
                                                                        prepend-text="Jalan"
                                                                        placeholder="Masukkan jalan"
                                                                    />
                                                                </div>

                                                                <div class="col-6">
                                                                    <x-admin::input.text
                                                                        :name="'kota'"
                                                                        prepend-text="Kota"
                                                                        placeholder="Masukkan kota"
                                                                    />
                                                                </div>
                                                                <div class="col-6">
                                                                    <x-admin::input.text
                                                                        :name="'kode_pos'"
                                                                        prepend-text="K. Pos"
                                                                        placeholder="Masukkan kode pos"
                                                                    />
                                                                </div>
                                                                <div class="col-12 mt-3">
                                                                    <x-admin::input.text
                                                                        :name="'provinsi'"
                                                                        prepend-text="Provinsi"
                                                                        placeholder="Masukkan provinsi"
                                                                    />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label class="col-lg-3 col-form-label">Fax</label>
                                                        <div class="col-lg-9">
                                                            <x-admin::input.text
                                                                :name="'fax'"
                                                                placeholder="Masukkan fax"
                                                            />
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label class="col-lg-3 col-form-label">Website</label>
                                                        <div class="col-lg-9">
                                                            <x-admin::input.text
                                                                :name="'website'"
                                                                placeholder="Masukkan website"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="tab-pane"
                                        :class="activeTab == 'lain_lain' && 'active'"
                                        id="tabLainLain"
                                        role="tabpanel"
                                    >
                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Jatuh Tempo</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.number
                                                            :name="'jatuh_tempo'"
                                                            :decimal-scale="0"
                                                            append-text="Hari"
                                                            placeholder="Masukkan jatuh tempo"
                                                        />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Limit Piutang</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.number
                                                            :name="'limit_piutang'"
                                                            prepend-text="Rp"
                                                            placeholder="Masukkan jatuh tempo"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
