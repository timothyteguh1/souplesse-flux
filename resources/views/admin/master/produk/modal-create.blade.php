<div>
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <form wire:submit="submit">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Produk</h4>
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
                                <ul class="nav nav-custom-light nav-border-top nav-border-top-primary nav-justified rounded card-header-tabs border"
                                    role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" :class="activeTab == 'data_umum' && 'active'"
                                            data-bs-toggle="tab" href="#tabDataUmum" role="tab"
                                            @click="activeTab = 'data_umum'">
                                            Umum
                                        </a>
                                    </li>
                                    {{-- @if ($tipe_produk == \App\Utilities\Constants\Const_Umum::TIPE_PRODUK_PAKET)
                                        <li class="nav-item">
                                            <a
                                                class="nav-link"
                                                :class="activeTab == 'paket' && 'active'"
                                                data-bs-toggle="tab"
                                                href="#tabPaket"
                                                role="tab"
                                                @click="activeTab = 'paket'"
                                            >
                                                Paket
                                            </a>
                                        </li>
                                    @endif --}}

                                    <li class="nav-item">
                                        <a class="nav-link" :class="activeTab == 'satuan' && 'active'"
                                            data-bs-toggle="tab" href="#tabSatuan" role="tab"
                                            @click="activeTab = 'satuan'">
                                            Satuan
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" :class="activeTab == 'foto' && 'active'"
                                            data-bs-toggle="tab" href="#tabFoto" role="tab"
                                            @click="activeTab = 'foto'">
                                            Foto
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="pt-5">
                                <div class="tab-content">
                                    <div class="tab-pane" :class="activeTab == 'data_umum' && 'active show'"
                                        id="tabDataUmum" role="tabpanel">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Kode</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.text :name="'kode'"
                                                            placeholder="(OTOMATIS)" />
                                                    </div>
                                                </div>

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

                                                {{-- <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">
                                                        Tipe Produk
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.select2 :name="'tipe_produk'" :options="$this->optionsTipeProduk"
                                                            :defer="false" placeholder="- Pilih Tipe Produk -" />
                                                    </div>
                                                </div> --}}

                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">
                                                        Kategori
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.select2 :name="'kategori_produk_id'" :options="$this->optionsKategoriProdukId"
                                                            :defer="false" placeholder="- Pilih Kategori Produk -" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Sub Kategori</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.select2 :name="'sub_kategori_produk_id'"
                                                            placeholder="- Pilih Sub Kategori Produk -" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Jenis</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.select2 :name="'jenis_produk_id'" :options="$this->optionsJenisProdukId"
                                                            placeholder="- Pilih Jenis Produk -" />
                                                    </div>
                                                </div>

                                                {{-- <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Merk</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.select2 :name="'merk_id'" :options="$this->optionsMerkId"
                                                            placeholder="- Pilih Merk -" />
                                                    </div>
                                                </div> --}}

                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Part Number</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.text :name="'part_number'"
                                                            placeholder="Masukkan part number satuan dasar" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Lokasi</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.textarea :name="'lokasi'"
                                                            placeholder="Masukkan lokasi satuan dasar" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Berat</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.number :name="'berat'"
                                                            placeholder="Masukkan berat" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Panjang</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.number :name="'panjang'"
                                                            placeholder="Masukkan panjang" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Lebar</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.number :name="'lebar'"
                                                            placeholder="Masukkan lebar" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Tinggi</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.number :name="'tinggi'"
                                                            placeholder="Masukkan tinggi" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Keterangan</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.textarea :name="'keterangan'"
                                                            placeholder="Masukkan keterangan" />
                                                    </div>
                                                </div>
                                                {{-- <div class="row mb-3"> --}}
                                                {{-- <label class="col-lg-3 col-form-label"></label> --}}
                                                {{-- <div class="col-lg-9"> --}}
                                                {{-- <x-admin::input.checkbox :name="'is_have_expired_date'" :label="'Produk ini mempunyai expired date'" --}}
                                                {{-- :value="$is_have_expired_date" :inline="true" /> --}}
                                                {{-- </div> --}}
                                                {{-- </div> --}}

                                                {{-- <div class="row mb-3"> --}}
                                                {{-- <label class="col-lg-3 col-form-label"></label> --}}
                                                {{-- <div class="col-lg-9"> --}}
                                                {{-- <x-admin::input.checkbox :name="'is_have_no_batch'" :label="'Produk ini mempunyai no. batch'" --}}
                                                {{-- :value="$is_have_no_batch" :inline="true" /> --}}
                                                {{-- </div> --}}
                                                {{-- </div> --}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" :class="activeTab == 'paket' && 'active'" id="tabPaket"
                                        role="tabpanel">
                                        <div>
                                            <div class="mb-3">
                                                <h5>Rincian Paket</h5>
                                            </div>
                                            <div class="row mb-3 g-3">
                                                <div class="col-xxl-8 col-sm-12">
                                                    <x-admin::input.select2 :name="'input_paket_produk_paket_id'" :defer="false"
                                                        placeholder="Produk" />
                                                </div>
                                                <div class="col-xxl-4 col-sm-12">
                                                    <x-admin::input.select2 :name="'input_paket_satuan_id'" placeholder="Satuan" />
                                                </div>
                                                <div class="col-xxl-6 col-sm-6">
                                                    <x-admin::input.text :name="'input_paket_nama_alias'" placeholder="Nama Alias" />
                                                </div>
                                                <div class="col-xxl-4 col-sm-6">
                                                    <x-admin::input.number :name="'input_paket_jumlah'" placeholder="Jumlah" />
                                                </div>
                                                <div class="col-xxl-2 col-sm-6">
                                                    @if ($index_edit_item_paket === null)
                                                        <x-admin::buttons.create-add-item :action="'addItemPaket'" />
                                                    @else
                                                        <x-admin::buttons.create-edit-item :action="'editItemPaket'" />
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle table-nowrap mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="35%" class="text-uppercase">Produk</th>
                                                        <th width="25%" class="text-uppercase text-end">Nama Alias
                                                        </th>
                                                        <th width="15%" class="text-uppercase text-end">Satuan</th>
                                                        <th width="15%" class="text-uppercase text-end">Jumlah</th>
                                                        <th width="10%" class="text-uppercase text-end">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($items_paket as $item)
                                                        <tr>
                                                            <td>
                                                                {{ $item['produk_paket_nama'] }}
                                                            </td>
                                                            <td>
                                                                {{ $item['nama_alias'] }}
                                                            </td>
                                                            <td>
                                                                {{ $item['satuan_nama'] }}
                                                            </td>
                                                            <td class="text-end">
                                                                {{ _number($item['jumlah']) }}
                                                            </td>
                                                            <td class="text-end">
                                                                <button type="button"
                                                                    wire:click="editPaket({{ $loop->index }})"
                                                                    class="btn btn-sm btn-warning btn-icon waves-effect waves-light me-2">
                                                                    <i class="ri-pencil-fill"></i>
                                                                </button>

                                                                <button type="button"
                                                                    wire:click="removeItemPaket({{ $loop->index }})"
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

                                    <div class="tab-pane" :class="activeTab == 'satuan' && 'active'" id="tabSatuan"
                                        role="tabpanel">
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">
                                                        Satuan Dasar
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.select2 :name="'satuan_dasar_id'" :options="$this->optionsSatuanDasarId"
                                                            placeholder="- Pilih Satuan Dasar -" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Harga Jual Bawah</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.number :name="'harga_jual_bawah'"
                                                            placeholder="Masukkan harga jual bawah satuan dasar" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Harga Jual Atas</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.number :name="'harga_jual_atas'"
                                                            placeholder="Masukkan harga jual atas satuan dasar" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Barcode</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.text :name="'barcode'"
                                                            placeholder="Masukkan barcode satuan dasar" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Satuan Beli</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.select2 :name="'default_satuan_beli_id'" :options="$this->optionsDefaultSatuanBeliId"
                                                            placeholder="- Pilih Default Satuan Beli -" />
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Satuan Jual</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.select2 :name="'default_satuan_jual_id'" :options="$this->optionsDefaultSatuanJualId"
                                                            placeholder="- Pilih Default Satuan Jual -" />
                                                    </div>
                                                </div>

                                                {{-- <div class="row mb-3"> --}}
                                                {{-- <label class="col-lg-3 col-form-label">Etiket</label> --}}
                                                {{-- <div class="col-lg-9"> --}}
                                                {{-- <x-admin::input.select2 :name="'default_etiket_id'" --}}
                                                {{-- :options="\App\Utilities\SelectHelpers\Master\SH_Etiket::active()" placeholder="- Pilih Default Etiket -" /> --}}
                                                {{-- </div> --}}
                                                {{-- </div> --}}

                                                <div class="row mb-3">
                                                    <label class="col-lg-3 col-form-label">Stok Minimum</label>
                                                    <div class="col-lg-9">
                                                        <x-admin::input.number :name="'stok_minimum'"
                                                            placeholder="Masukkan stok minimum" />
                                                        <small class="text-muted">* Dalam satuan dasar</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr />

                                        <div>
                                            <div class="mb-3">
                                                <small class="text-muted">
                                                    * Konversi adalah jumlah satuan dasar dalam satuan yang dipilih.
                                                </small>
                                            </div>
                                            <div class="row mb-3 g-3">
                                                <div class="col-xxl-4 col-sm-12">
                                                    <x-admin::input.select2 :name="'input_modal_satuan_id'" :options="$this->optionsInputSatuanId"
                                                        placeholder="Satuan" />
                                                </div>
                                                <div class="col-xxl-2 col-sm-6">
                                                    <x-admin::input.number :name="'input_konversi'" placeholder="Konversi" />
                                                </div>
                                                <div class="col-xxl-3 col-sm-6">
                                                    <x-admin::input.number :name="'input_harga_jual_bawah'"
                                                        placeholder="Harga Jual Bawah" />
                                                </div>
                                                <div class="col-xxl-3 col-sm-6">
                                                    <x-admin::input.number :name="'input_harga_jual_atas'"
                                                        placeholder="Harga Jual Atas" />
                                                </div>
                                                <div class="col-xxl-4 col-sm-6">
                                                    <x-admin::input.number :name="'input_barcode'" placeholder="Barcode" />
                                                </div>

                                                <div class="col-xxl-2 col-sm-6">
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
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="30%" class="text-uppercase">Satuan</th>
                                                        <th width="10%" class="text-uppercase text-end">Konversi
                                                        </th>
                                                        <th width="15%" class="text-uppercase text-end">
                                                            Harga Jual Bawah
                                                        </th>
                                                        <th width="15%" class="text-uppercase text-end">
                                                            Harga Jual Atas
                                                        </th>
                                                        <th width="20%" class="text-uppercase">Barcode</th>
                                                        <th width="10%" class="text-uppercase text-end">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($items as $item)
                                                        <tr>
                                                            <td>
                                                                {{ $item['satuan_nama'] }}
                                                            </td>
                                                            <td class="text-end">
                                                                {{ _number($item['konversi']) }}
                                                            </td>
                                                            <td class="text-end">
                                                                {{ _number($item['harga_jual_bawah']) }}
                                                            </td>
                                                            <td class="text-end">
                                                                {{ _number($item['harga_jual_atas']) }}
                                                            </td>
                                                            <td>
                                                                {{ $item['barcode'] }}
                                                            </td>
                                                            <td class="text-end">
                                                                <button type="button"
                                                                    wire:click="edit({{ $loop->index }})"
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

                                    <div class="tab-pane" :class="activeTab == 'foto' && 'active'" id="tabFoto"
                                        role="tabpanel">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="row mb-3 g-3">
                                                    <div class="col-9">
                                                        <x-admin::input.file :name="'input_foto'" accept="image/*"
                                                            class="form-control" placeholder="Masukkan foto" />
                                                    </div>

                                                    <div class="col-3">
                                                        <x-admin::buttons.create-add-item :action="'addItemFoto'" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered align-middle table-nowrap mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th width="70%" class="text-uppercase">Foto</th>
                                                                <th width="20%" class="text-uppercase">Size</th>
                                                                <th width="10%" class="text-uppercase text-end">
                                                                    Action
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($items_foto as $item)
                                                                <tr>
                                                                    <td>{{ $item->getClientOriginalName() }}</td>
                                                                    <td>{{ Number::fileSize($item->getSize()) }}</td>
                                                                    <td class="text-end">
                                                                        <button type="button"
                                                                            wire:click="removeItemFoto({{ $loop->index }})"
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
