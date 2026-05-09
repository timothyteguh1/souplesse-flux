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
                <div class="card" x-data="{ activeTab: 'umum' }">
                    <div class="card-body">
                        <ul class="nav nav-custom-light nav-border-top nav-border-top-primary nav-justified rounded card-header-tabs border"
                            role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" :class="activeTab == 'umum' && 'active'" data-bs-toggle="tab"
                                    href="#tabUmum" role="tab" @click="activeTab = 'umum'">
                                    Data Umum
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" :class="activeTab == 'pajak' && 'active'" data-bs-toggle="tab"
                                    href="#tabPajak" role="tab" @click="activeTab = 'pajak'">
                                    Pajak
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" :class="activeTab == 'lain' && 'active'" data-bs-toggle="tab"
                                    href="#tabLainLain" role="tab" @click="activeTab = 'lain'">
                                    Lain-Lain
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-4">
                        <div class="tab-content">
                            <div class="tab-pane" :class="activeTab == 'umum' && 'active'" id="tabUmum"
                                role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 col-12">
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
                                                <x-admin::input.text :name="'email'" placeholder="Masukkan email" />
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-lg-3 col-form-label">Blacklist</label>
                                            <div class="col-lg-9">
                                                <x-admin::input.checkbox :name="'is_blacklist'" :value="$is_blacklist"
                                                    :defer="false" :inline="false" />
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
                                    </div>
                                </div>
                            </div>
                            <!--end tab-pane-->

                            <!--end tab-pane-->
                            <div class="tab-pane" :class="activeTab == 'pajak' && 'active show'" id="tabPajak"
                                role="tabpanel">
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">NPWP</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.npwp :name="'npwp_kode'" placeholder="Masukan NPWP" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">NIK</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.text :name="'npwp_nik'" placeholder="Masukan NIK" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Wajib Pajak</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.text :name="'npwp_wajib_pajak'" placeholder="Masukan wajib pajak" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Alamat</label>
                                    <div class="col-lg-9">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <x-admin::input.textarea :name="'npwp_alamat'" prepend-text="Jalan"
                                                    placeholder="Masukan jalan" />
                                            </div>

                                            <div class="col-6 mb-3">
                                                <x-admin::input.text :name="'npwp_kota'" prepend-text="Kota"
                                                    placeholder="Masukan kota" />
                                            </div>
                                            <div class="col-6 mb-3">
                                                <x-admin::input.text :name="'npwp_kode_pos'" prepend-text="K. Pos"
                                                    placeholder="Masukan kode pos" />
                                            </div>
                                            <div class="col-12 mb-3">
                                                <x-admin::input.text :name="'npwp_provinsi'" prepend-text="Provinsi"
                                                    placeholder="Masukan provinsi" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" :class="activeTab == 'lain' && 'active show'" id="tabLainLain"
                                role="tabpanel">
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Jatuh Tempo</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.number :name="'jatuh_tempo'" :decimal-scale="0" append-text="Hari"
                                            placeholder="Masukkan jatuh tempo" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Limit Piutang</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.number :name="'limit_piutang'" prepend-text="Rp"
                                            placeholder="Masukkan jatuh tempo" />
                                    </div>
                                </div>

                                <hr />
                                <div class="card-title"><strong>Bank Pembayaran</strong></div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Bank</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.text :name="'rekening_bank'" placeholder="Masukan Nama Bank" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Nomor Rekening</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.text :name="'rekening_nomor'" placeholder="Masukan Nomor Rekening" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Atas Nama</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.text :name="'rekening_nama'" placeholder="Masukan Nama" />
                                    </div>
                                </div>

                                <hr />
                                <div class="card-title"><strong>Diskon Customer</strong></div>
                                <div>
                                    <div class="row mb-3 g-3">
                                        <div class="col-6">
                                            <x-admin::input.text :name="'input_metode_pembayaran'" placeholder="Metode Pembayaran" />
                                        </div>
                                        <div class="col-6">
                                            <x-admin::input.number :name="'input_diskon'" placeholder="Diskon (%)" />
                                        </div>

                                        <div class="col-12">
                                            @if ($index_edit_item === null)
                                                <x-admin::buttons.create-add-item :action="'addItem'" />
                                            @else
                                                <x-admin::buttons.create-edit-item :action="'editItem'" />
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle table-nowrap mb-0">
                                        <thead class="table-light text-uppercase">
                                            <tr>
                                                <th width="10%">No.</th>
                                                <th width="40%">Metode Pembayaran</th>
                                                <th width="40%" class="text-end">Diskon (%)</th>
                                                <th width="10%" class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        {{ $item['metode_pembayaran'] }}
                                                    </td>
                                                    <td class="text-end">
                                                        {{ _number($item['diskon']) }}
                                                    </td>
                                                    <td class="text-end">
                                                        <button type="button" wire:click="edit({{ $loop->index }})"
                                                            class="btn btn-sm btn-warning btn-icon waves-effect waves-light me-2">
                                                            <i class="ri-pencil-fill"></i>
                                                        </button>

                                                        <button type="button"
                                                            wire:click="removeItem({{ $loop->index }})"
                                                            class="btn btn-sm btn-danger btn-icon waves-effect waves-light">
                                                            <i class="ri-delete-bin-5-line"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <!--end tab-pane-->
                        </div>
                    </div>

                    <x-admin::includes.pages.create-footer-action />
                </div>
            </form>
        </div>
    </div>
    <!--end row-->
</div>
