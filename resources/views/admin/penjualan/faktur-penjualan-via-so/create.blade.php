@php
    use App\Utilities\Constants\Const_Umum;
@endphp

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
                                <div class="card-title">
                                    <strong>Data Faktur Penjualan</strong>
                                </div>
                                <hr />
                                {{-- <div class="row mb-3"> --}}
                                {{-- <label class="col-lg-3 col-form-label"> --}}
                                {{-- Kode --}}
                                {{-- <span class="text-danger">*</span> --}}
                                {{-- </label> --}}
                                {{-- <div class="col-lg-9"> --}}
                                {{-- <x-admin::input.text :name="'kode'" placeholder="(OTOMATIS)" /> --}}
                                {{-- </div> --}}
                                {{-- </div> --}}

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Tanggal
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.date-time :name="'tanggal'" placeholder="Masukkan Tanggal" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Pesanan Penjualan
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.select2 :name="'pesanan_penjualan_id'" :defer="false" :options="$this->optionsPesananPenjualanId"
                                            placeholder="- Pilih Pesanan Penjualan -" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Customer
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.text :name="'customer_nama'" placeholder="(OTOMATIS)" disabled />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Gudang
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.select2id :id="'gudang_id'" :name="'gudang_id'" :defer="false"
                                            :options="\App\Utilities\SelectHelpers\Master\SH_Gudang::user()" placeholder="- Pilih Gudang -" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Jatuh Tempo</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.date-time :name="'tanggal_jatuh_tempo'"
                                            placeholder="Masukkan Tanggal Jatuh Tempo" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Keterangan</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.textarea :name="'keterangan'" placeholder="Keterangan" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="card-title">
                                    <strong>Data Customer</strong>
                                </div>
                                <hr />

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Alamat</label>
                                    <div class="col-lg-9">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <x-admin::input.textarea :name="'alamat'" prepend-text="Jalan"
                                                    placeholder="Masukkan jalan" disabled />
                                            </div>

                                            <div class="col-6">
                                                <x-admin::input.text :name="'kota'" prepend-text="Kota"
                                                    placeholder="Masukkan kota" disabled />
                                            </div>
                                            <div class="col-6">
                                                <x-admin::input.text :name="'kode_pos'" prepend-text="K. Pos"
                                                    placeholder="Masukkan kode pos" disabled />
                                            </div>
                                            <div class="col-12 mt-3">
                                                <x-admin::input.text :name="'provinsi'" prepend-text="Provinsi"
                                                    placeholder="Masukkan provinsi" disabled />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Kelas Customer</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.readonly :value="$kelas_customer" placeholder="Kelas Customer" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label"></label>
                                    <div class="col-lg-9">
                                        <x-admin::input.checkbox :name="'is_pkp'" :label="'PKP'"
                                            :value="$is_pkp" :inline="true" :disabled="true" />

                                        <x-admin::input.checkbox :name="'is_include_ppn'" :label="'Include PPN'"
                                            :value="$is_include_ppn" :inline="true" :disabled="true" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-4 border-top">
                        <div x-data="{ activeTab: 'produk' }">
                            <div>
                                <ul class="nav nav-custom-light nav-border-top nav-border-top-primary nav-justified rounded card-header-tabs border"
                                    role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" :class="activeTab == 'produk' && 'active'"
                                            data-bs-toggle="tab" href="#tabProduk" role="tab"
                                            @click="activeTab = 'produk'">
                                            Produk
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" :class="activeTab == 'beban' && 'active'"
                                            data-bs-toggle="tab" href="#tabBeban" role="tab"
                                            @click="activeTab = 'beban'">
                                            Beban
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="py-4">
                                <div class="tab-content">
                                    <div class="tab-pane" :class="activeTab == 'produk' && 'active show'" id="tabProduk"
                                        role="tabpanel">
                                        <div class="row">
                                            <div class="mb-3">
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <span class="btn btn-primary" role="button" tabindex="0"
                                                            wire:click="openModalListPromo"
                                                            wire:keydown.enter="openModalListPromo">
                                                            <i class="ri- align-bottom me-1"></i>
                                                            List Promo
                                                        </span>
                                                    </div>
                                                    <div class="col-12">
                                                        <x-admin::input.select2id :id="'input_produk_id'" :name="'input_produk_id'"
                                                            :options="$this->optionsInputProdukId" :defer="false"
                                                            placeholder="Produk" />
                                                    </div>
                                                    <div class="col-md-3 col-12">
                                                        <x-admin::input.select2id :id="'input_satuan_id'" :name="'input_satuan_id'"
                                                            :options="[]" :defer="false"
                                                            placeholder="Satuan" />
                                                    </div>
                                                    <div class="col-md-3 col-12">
                                                        <x-admin::input.number :name="'input_jumlah'" placeholder="Qty"
                                                            :defer="false" />
                                                    </div>
                                                    <div class="col-md-3 col-12">
                                                        <x-admin::input.number :name="'input_harga_satuan'" placeholder="Harga"
                                                            :defer="false" />
                                                    </div>
                                                    <div class="col-md-3 col-12">
                                                        <x-admin::input.number :name="'input_subtotal'"
                                                            placeholder="Subtotal (Otomatis terisi)" readonly />
                                                    </div>
                                                    <div class="col-md-3 col-12">
                                                        <x-admin::input.diskon :type="'input_diskon_satuan_type_1'" :name="'input_diskon_satuan_1'"
                                                            placeholder="Diskon Satuan 1" />
                                                    </div>
                                                    <div class="col-md-3 col-12">
                                                        <x-admin::input.diskon :type="'input_diskon_satuan_type_2'" :name="'input_diskon_satuan_2'"
                                                            placeholder="Diskon Satuan 2" />
                                                    </div>
                                                    <div class="col-md-3 col-12">
                                                        <x-admin::input.diskon :type="'input_diskon_satuan_type_3'" :name="'input_diskon_satuan_3'"
                                                            placeholder="Diskon Satuan 3" />
                                                    </div>
                                                    <div class="col-md-3 col-12">
                                                        <x-admin::input.diskon :type="'input_diskon_satuan_type_4'" :name="'input_diskon_satuan_4'"
                                                            placeholder="Diskon Satuan 4" />
                                                    </div>

                                                    <div class=" col-12">
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
                                                            <th width="5%">No</th>
                                                            <th width="20%">Produk</th>
                                                            <th width="10%" class="text-end">Qty
                                                            </th>
                                                            <th width="10%" class="text-end">Harga
                                                            </th>
                                                            <th class="text-center" colspan="2">Diskon per Qty</th>
                                                            <th width="10%">Detail Diskon</th>
                                                            <th width="10%" class="text-end">Subtotal</th>
                                                            <th width="10%" class="text-end">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($items as $index => $item)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>
                                                                    {{ $item['produk_nama'] }}
                                                                </td>
                                                                <td class="text-end">
                                                                    {{ _number($item['jumlah']) }}
                                                                    {{ $item['satuan_nama'] }}
                                                                </td>
                                                                <td class="text-end">
                                                                    {{ _number($item['harga_satuan']) }}
                                                                </td>
                                                                <td class="text-end">
                                                                    {{ _number($item['diskon_satuan_persen']) }}%
                                                                </td>
                                                                <td class="text-end">
                                                                    {{ _number($item['diskon_satuan_rupiah']) }}
                                                                </td>
                                                                <td>
                                                                    @for ($i = 1; $i <= 4; $i++)
                                                                        @php
                                                                            $diskon = "diskon_satuan_$i";
                                                                            $type = "diskon_satuan_type_$i";
                                                                        @endphp

                                                                        @if ($item[$diskon] != 0)
                                                                            @if ($i > 1)
                                                                                <br>
                                                                            @endif
                                                                            Disk {{ $i }}:
                                                                            @if ($item[$type] == Const_Umum::DISKON_TYPE_RP)
                                                                                {{ $item[$type] . '. ' . _number($item[$diskon]) }}
                                                                            @else
                                                                                {{ _number($item[$diskon]) . $item[$type] }}
                                                                            @endif
                                                                        @endif
                                                                    @endfor
                                                                </td>
                                                                <td class="text-end">
                                                                    {{ _number($item['subtotal']) }}
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
                                    </div>

                                    <div class="tab-pane" :class="activeTab == 'beban' && 'active'" id="tabBeban"
                                        role="tabpanel">
                                        <div class="row">
                                            <div class="mb-3">
                                                <div class="row g-3 align-items-center">
                                                    <div class="col-md-5 col-12">
                                                        <x-admin::input.select2 :name="'input_beban_beban_id'" :options="$this->optionsInputBebanBebanId"
                                                            placeholder="Beban" />
                                                    </div>
                                                    <div class="col-md-4 col-12">
                                                        <x-admin::input.number :name="'input_beban_jumlah'"
                                                            placeholder="Jumlah" />
                                                    </div>

                                                    <div class="col-md-3 col-12">
                                                        @if ($index_edit_item_beban === null)
                                                            <x-admin::buttons.create-add-item :action="'addItemBeban'" />
                                                        @else
                                                            <x-admin::buttons.create-edit-item :action="'editItemBeban'" />
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-bordered align-middle table-nowrap mb-0">
                                                    <thead class="table-light text-uppercase">
                                                        <tr>
                                                            <th width="5%">No</th>
                                                            <th width="40%">Beban</th>
                                                            <th width="40%" class="text-end">Jumlah
                                                            </th>
                                                            <th width="15%" class="text-end">Action
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($items_beban as $index => $item)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>
                                                                    {{ $item['beban_nama'] }}
                                                                </td>
                                                                <td class="text-end">
                                                                    {{ _number($item['jumlah']) }}
                                                                </td>
                                                                <td class="text-end">
                                                                    <button type="button"
                                                                        wire:click="editBeban({{ $loop->index }})"
                                                                        class="btn btn-sm btn-warning btn-icon waves-effect waves-light me-2">
                                                                        <i class="ri-pencil-fill"></i>
                                                                    </button>

                                                                    <button type="button"
                                                                        wire:click="removeItemBeban({{ $loop->index }})"
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

                        <hr />

                        <div class="row mt-4">
                            <div class="col-12 col-md-6 offset-md-6">
                                <table class="table table-bordered align-middle">
                                    <tbody>
                                        <tr>
                                            <th class="col-4">Total</th>
                                            <th class="col-6 text-end">
                                                {{ _number($total) }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Diskon Faktur</th>
                                            <th class="text-end">
                                                <x-admin::input.diskon :type="'diskon_type'" :name="'diskon'"
                                                    placeholder="Diskon Faktur" :defer="false" class="text-end" />
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>DPP</th>
                                            <th class="text-end">
                                                {{ _number($total_dpp) }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>PPN ({{ $ppn_percent }}%)</th>
                                            <th class="text-end">
                                                {{ _number($total_ppn) }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Total Beban</th>
                                            <th class="text-end">
                                                {{ _number($total_beban) }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Grand Total</th>
                                            <th class="text-end">
                                                {{ _number($grandtotal) }}
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <x-admin::includes.pages.create-footer-action :create-button="false" :confirmation-button="true" />
                </div>
            </form>
        </div>
    </div>

    <x-admin::utils.modal-dialog :id="'ModalListPromo'">
        <livewire:admin.penjualan.faktur-penjualan-via-so.modal-list-promo />
    </x-admin::utils.modal-dialog>

    <x-admin::utils.modal-dialog :id="'ModalPembayaran'">
        <livewire:admin.penjualan.faktur-penjualan-via-so.modal-pembayaran />
    </x-admin::utils.modal-dialog>
</div>
