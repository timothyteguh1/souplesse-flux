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
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="card-title">
                                    <strong>Data Penjualan</strong>
                                </div>
                                <hr />
                                {{-- <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Kode
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.text :name="'kode'" placeholder="(OTOMATIS)" />
                                    </div>
                                </div> --}}

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
                                        Customer
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.select2 :name="'customer_id'" :defer="false" :options="$this->optionsCustomerId"
                                            :modal-form="'admin.master.customer.modal-create'" placeholder="- Pilih Customer -" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Salesman
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.select2 :name="'karyawan_id'" :options="$this->optionsKaryawanId"
                                            placeholder="- Pilih Salesman -" />
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

                                            <div class="col-12">
                                                <x-admin::input.text :name="'kota'" prepend-text="Kota"
                                                    placeholder="Masukkan kota" disabled />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-4 border-top">

                        <div class="mb-3">
                            <div class="row g-3">
                                <div class="col-12">
                                    <x-admin::input.select2 :name="'input_produk_id'" :options="$this->optionsInputProdukId" :defer="false"
                                        placeholder="Produk" />
                                </div>
                                <div class="col-md-2 col-12">
                                    <x-admin::input.number :name="'input_jumlah'" placeholder="Qty" />
                                </div>
                                <div class="col-md-3 col-12">
                                    <x-admin::input.number :name="'input_harga_satuan'" placeholder="Harga" />
                                </div>
                                <div class="col-md-3 col-12">
                                    <x-admin::input.diskon :type="'input_diskon_satuan_type'" :name="'input_diskon_satuan'"
                                        placeholder="Diskon Satuan" />
                                </div>
                                <div class="col-md-4 col-12">
                                    <x-admin::input.text :type="'input_keterangan'" :name="'input_keterangan'" placeholder="Note" />
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
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%" class="text-uppercase">No</th>
                                        <th width="20%" class="text-uppercase">Produk</th>
                                        <th width="10%" class="text-uppercase">Model</th>
                                        <th width="10%" class="text-uppercase text-end">Qty</th>
                                        <th width="10%" class="text-uppercase text-end">Harga
                                        </th>
                                        <th class="text-uppercase text-end" colspan="2">Diskon
                                        </th>
                                        <th width="15%" class="text-uppercase text-end">Subtotal
                                        </th>
                                        <th width="10%" class="text-uppercase">Note</th>
                                        <th width="10%" class="text-uppercase text-end">Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $item['produk_nama'] }}
                                            </td>
                                            <td>
                                                {{ $item['model_produk_nama'] }}
                                            </td>
                                            <td class="text-end">
                                                {{ _number($item['jumlah']) }}
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
                                            <td>
                                                {{ $item['keterangan'] }}
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
                                            <th class="col-4">Total Produk</th>
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
</div>
