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
                            <div class="col-12">
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
                                        Jatuh Tempo
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.date-time :name="'tanggal_jatuh_tempo'"
                                            placeholder="Masukkan Tanggal Jatuh Tempo" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Customer
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.select2 :name="'customer_id'" :defer="false" :options="$this->optionsCustomerId"
                                            :modal-form="'admin.master.customer.modal-create'" placeholder="- Pilih Customer -" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">Keterangan</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.textarea :name="'keterangan'" placeholder="Keterangan" />
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
                                        <a class="nav-link" :class="activeTab == 'set' && 'active'" data-bs-toggle="tab"
                                            href="#tabSet" role="tab" @click="activeTab = 'set'">
                                            Set
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" :class="activeTab == 'service' && 'active'"
                                            data-bs-toggle="tab" href="#tabService" role="tab"
                                            @click="activeTab = 'service'">
                                            Service
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
                                                        <x-admin::input.select2 :name="'input_surat_jalan_id'" :options="$this->optionsInputSuratJalanId"
                                                            :defer="false" placeholder="Surat Jalan" />
                                                    </div>

                                                    <div class="col-12">
                                                        <x-admin::input.select2 :name="'input_produk_id'" :modal-form="'admin.master.produk.modal-create'"
                                                            :defer="false" placeholder="Produk" />
                                                    </div>
                                                    <div class="col-md-2 col-12">
                                                        <x-admin::input.text :name="'input_satuan_nama'" placeholder="Satuan"
                                                            disabled />
                                                    </div>
                                                    <div class="col-md-2 col-12">
                                                        <x-admin::input.number :name="'input_jumlah'" placeholder="Qty" />
                                                    </div>
                                                    <div class="col-md-3 col-12">
                                                        <x-admin::input.number :name="'input_harga_satuan'" placeholder="Harga"
                                                            disabled />
                                                    </div>
                                                    <div class="col-md-3 col-12">
                                                        <x-admin::input.diskon :type="'input_diskon_satuan_type'" :name="'input_diskon_satuan'"
                                                            placeholder="Diskon Satuan" :disabled="true" />
                                                    </div>

                                                    <div class="col-md-2 col-12">
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
                                                            <th width="5%" class="text-uppercase">No</th>
                                                            <th width="10%" class="text-uppercase">
                                                                Pesanan Penjualan
                                                            </th>
                                                            <th width="15%" class="text-uppercase">Produk</th>
                                                            <th width="10%" class="text-uppercase">Satuan</th>
                                                            <th width="10%" class="text-uppercase text-end">Qty</th>
                                                            <th width="10%" class="text-uppercase text-end">Harga
                                                            </th>
                                                            <th width="10%" class="text-uppercase text-end"
                                                                colspan="2">
                                                                Diskon
                                                            </th>
                                                            <th width="15%" class="text-uppercase text-end">
                                                                Subtotal
                                                            </th>
                                                            <th width="10%" class="text-uppercase text-end">Action
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($items as $index => $item)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>
                                                                    {{ $item['pesanan_penjualan_kode'] }}
                                                                </td>
                                                                <td>
                                                                    {{ $item['produk_nama'] }}
                                                                </td>
                                                                <td>
                                                                    {{ $item['satuan_nama'] }}
                                                                </td>
                                                                <td class="text-end">
                                                                    {{ _number($item['jumlah']) }}
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

                                    <div class="tab-pane" :class="activeTab == 'set' && 'active'" id="tabSet"
                                        role="tabpanel">
                                        <div class="row">
                                            <div class="mb-3">
                                                <div class="row g-3">
                                                    <div class="col-md-12 col-12">
                                                        <button type="button" wire:click="openModalSetEdit()"
                                                            class="btn btn-secondary w-100 btn-load">
                                                            <i class="ri-add-line me-1 align-bottom"></i>
                                                            Add Item
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-bordered align-middle table-nowrap mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="5%" class="text-uppercase">No</th>
                                                            <th width="20%" class="text-uppercase">Produk</th>
                                                            <th width="20%" class="text-uppercase">Satuan</th>
                                                            <th width="20%" class="text-uppercase text-end">Jumlah
                                                            </th>
                                                            <th width="20%" class="text-uppercase text-end">
                                                                Grand Total
                                                            </th>
                                                            <th width="15%" class="text-uppercase text-end">Action
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($items_set as $index => $item)
                                                            @foreach ($item['items'] as $setDetail)
                                                                <tr>
                                                                    <td>
                                                                        @if ($loop->first)
                                                                            {{ $loop->parent->iteration }}
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        {{ $setDetail['produk_nama'] }}
                                                                    </td>
                                                                    <td>{{ $setDetail['satuan_nama'] }}</td>
                                                                    <td class="text-end">
                                                                        {{ _number($setDetail['jumlah']) }}
                                                                    </td>
                                                                    <td class="text-end">
                                                                        {{ _number($setDetail['subtotal']) }}
                                                                    </td>
                                                                    @if ($loop->first)
                                                                        <td class="text-end"
                                                                            rowspan="{{ count($item['items']) }}">
                                                                            <button type="button"
                                                                                wire:click="openModalSetEdit({{ $loop->parent->index }})"
                                                                                class="btn btn-sm btn-warning btn-icon waves-effect waves-light me-2">
                                                                                <i class="ri-pencil-fill"></i>
                                                                            </button>

                                                                            <button type="button"
                                                                                wire:click="removeItemSet({{ $loop->parent->index }})"
                                                                                class="btn btn-sm btn-danger btn-icon waves-effect waves-light">
                                                                                <i class="ri-delete-bin-5-line"></i>
                                                                            </button>
                                                                        </td>
                                                                    @endif
                                                                </tr>
                                                            @endforeach

                                                            <tr class="bg-light">
                                                                <td></td>
                                                                <td></td>
                                                                <td>
                                                                    {{ $item['satuan_teks'] }}
                                                                </td>
                                                                <td class="text-end">
                                                                    {{ _number($item['jumlah']) }}
                                                                </td>
                                                                <td class="text-end">
                                                                    {{ _number($item['grandtotal']) }}
                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" :class="activeTab == 'service' && 'active'" id="tabService"
                                        role="tabpanel">
                                        <div class="row">
                                            <div class="mb-3">
                                                <div class="row g-3">
                                                    <div class="col-md-9 col-12">
                                                        <x-admin::input.select2 :name="'input_service_perintah_service_id'" :options="$this->optionsInputServicePerintahServiceId"
                                                            :defer="false" placeholder="Service" />
                                                    </div>

                                                    <div class="col-md-3 col-12">
                                                        @if ($index_edit_item_service === null)
                                                            <x-admin::buttons.create-add-item :action="'addItemService'" />
                                                        @else
                                                            <x-admin::buttons.create-edit-item :action="'editItemService'" />
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-bordered align-middle table-nowrap mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="5%" class="text-uppercase">No</th>
                                                            <th width="20%" class="text-uppercase">Kode</th>
                                                            <th width="20%" class="text-uppercase">Nama</th>
                                                            <th width="20%" class="text-uppercase">Tanggal</th>
                                                            <th width="20%" class="text-uppercase text-end">
                                                                Grand Total
                                                            </th>
                                                            <th width="15%" class="text-uppercase text-end">Action
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($items_service as $index => $item)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>
                                                                    {{ $item['perintah_service_kode'] }}
                                                                </td>
                                                                <td>
                                                                    {{ $item['customer_nama'] }}
                                                                </td>
                                                                <td>
                                                                    {{ $item['tanggal'] }}
                                                                </td>
                                                                <td class="text-end">
                                                                    {{ _number($item['grandtotal']) }}
                                                                </td>
                                                                <td class="text-end">
                                                                    <button type="button"
                                                                        wire:click="editService({{ $loop->index }})"
                                                                        class="btn btn-sm btn-warning btn-icon waves-effect waves-light me-2">
                                                                        <i class="ri-pencil-fill"></i>
                                                                    </button>

                                                                    <button type="button"
                                                                        wire:click="removeItemService({{ $loop->index }})"
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
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="5%" class="text-uppercase">No</th>
                                                            <th width="40%" class="text-uppercase">Beban</th>
                                                            <th width="40%" class="text-uppercase text-end">Jumlah
                                                            </th>
                                                            <th width="15%" class="text-uppercase text-end">Action
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
                                            <th class="col-4">Total Produk</th>
                                            <th class="col-6 text-end">
                                                {{ _number($total) }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="col-4">Total Set</th>
                                            <th class="col-6 text-end">
                                                {{ _number($total_set) }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="col-4">Total Service</th>
                                            <th class="col-6 text-end">
                                                {{ _number($total_service) }}
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
                                            <th>
                                                <div class="d-flex justify-content-between">
                                                    <span>PPN</span>
                                                    <button type="button" wire:click="openModalSelisihPpn()"
                                                        class="btn btn-sm btn-warning me-2">
                                                        <i class="ri-pencil-fill"></i>
                                                    </button>
                                                </div>
                                            </th>
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

    <x-admin::utils.modal-dialog :id="'ModalSetEdit'">
        <livewire:admin.penjualan.faktur-penjualan-via-sj.modal-set-edit :index="1" />
    </x-admin::utils.modal-dialog>

    <x-admin::utils.modal-dialog :id="'ModalSelisihPpn'">
        <livewire:admin.master.pajak.modal-selisih-ppn :index="2" />
    </x-admin::utils.modal-dialog>
</div>
