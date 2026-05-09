<div>
    @section('title', 'Ubah ' . $obj->kode)

    @section('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ $obj->getRouteIndex() }}">{{ $menuTitle }}</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ $obj->getRouteShow() }}">{{ $obj->kode }}</a>
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
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="card-title">
                                    <strong>Data Retur Pembelian</strong>
                                </div>
                                <hr />

                                <div class="row mt-4 mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Kode
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.text :name="'kode'" placeholder="(OTOMATIS)" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Tanggal Retur
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.date-time :name="'tanggal'" placeholder="Masukkan Tanggal" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Supplier
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.select2id :id="'supplier_id'" :name="'supplier_id'" :defer="false"
                                            :options="\App\Utilities\SelectHelpers\Master\SH_Supplier::active()" placeholder="- Pilih Supplier -" />
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
                                    <label class="col-lg-3 col-form-label">Keterangan</label>
                                    <div class="col-lg-9">
                                        <x-admin::input.textarea :name="'keterangan'" placeholder="Keterangan" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="card-title">
                                    <strong>Data Supplier</strong>
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
                        <div class="mb-3">
                            <div class="row g-3">
                                <div class="col-md-3 col-12">
                                    <x-admin::input.select2id :id="'input_faktur_pembelian_id'" :name="'input_faktur_pembelian_id'" :options="\App\Utilities\SelectHelpers\Transaksi\Pembelian\SH_FakturPembelian::all(
                                        $supplier_id,
                                        is_show_sisa_utang: false,
                                    )"
                                        :defer="false" placeholder="Pilih Faktur Pembelian" />
                                </div>
                                <div class="col-md-3 col-12">
                                    <x-admin::input.select2id :id="'input_faktur_pembelian_detail_id'" :name="'input_faktur_pembelian_detail_id'" :options="[]"
                                        :defer="false" placeholder="Produk" />
                                </div>
                                <div class="col-md-3 col-12">
                                    <x-admin::input.select2id :id="'input_satuan_id'" :name="'input_satuan_id'" :options="[]"
                                        :defer="false" placeholder="Satuan" />
                                </div>
                                <div class="col-md-3 col-12">
                                    <x-admin::input.number :name="'input_jumlah'" placeholder="Qty" />
                                </div>
                                <div class="col-md-3 col-12">
                                    <x-admin::input.readonly :value="_date_format_output($input_tanggal_faktur)" :name="'input_tanggal_faktur'"
                                        placeholder="Tanggal Faktur" />
                                </div>
                                <div class="col-md-3 col-12">
                                    <x-admin::input.readonly :value="$input_harga_satuan ? _number($input_harga_satuan) : ''" :name="'input_harga_satuan'"
                                        placeholder="Harga" />
                                </div>
                                <div class="col-md-3 col-12">
                                    <x-admin::input.readonly :value="$input_diskon_satuan ? _number($input_diskon_satuan) : ''" :name="'input_diskon_satuan'"
                                        placeholder="Diskon Satuan" />
                                </div>

                                <div class="col-md-3 col-12">
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
                                        <th width="10%" class="text-uppercase">No. Faktur <br> Pembelian</th>
                                        <th width="10%" class="text-uppercase">No. Faktur <br> Pembelian Supplier
                                        </th>
                                        <th width="10%" class="text-uppercase">Tanggal Faktur</th>
                                        <th width="10%" class="text-uppercase">Produk</th>
                                        <th width="10%" class="text-uppercase text-end">Qty</th>
                                        <th width="10%" class="text-uppercase text-end">Harga Beli Satuan</th>
                                        <th class="text-uppercase text-end" colspan="2">Diskon Per Qty</th>
                                        <th width="10%" class="text-uppercase text-end">Subtotal</th>
                                        <th width="10%" class="text-uppercase text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $item['faktur_pembelian_kode'] }}
                                            </td>
                                            <td>
                                                {{ $item['faktur_pembelian_supplier_kode'] }}
                                            </td>
                                            <td>
                                                {{ $item['tanggal_faktur'] }}
                                            </td>
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
                                            <td class="text-end" width="5%">
                                                {{ _number($item['diskon_satuan_persen']) }}%
                                            </td>
                                            <td class="text-end" width="10%">
                                                {{ _number($item['diskon_satuan_rupiah']) }}
                                            </td>
                                            <td class="text-end">
                                                {{ _number($item['subtotal']) }}
                                            </td>
                                            <td class="text-end">
                                                <button type="button" wire:click="edit({{ $loop->index }})"
                                                    class="btn btn-sm btn-warning btn-icon waves-effect waves-light me-2">
                                                    <i class="ri-pencil-fill"></i>
                                                </button>

                                                <button type="button" wire:click="removeItem({{ $loop->index }})"
                                                    class="btn btn-sm btn-danger btn-icon waves-effect waves-light">
                                                    <i class="ri-delete-bin-5-line"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
                                            <th>DPP</th>
                                            <th class="text-end">
                                                {{ _number($total_dpp) }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>PPN</th>
                                            <th class="text-end">
                                                {{ _number($total_ppn) }}
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

                    <x-admin::includes.pages.create-footer-action />
                </div>
            </form>
        </div>
    </div>
    <!--end row-->
</div>
