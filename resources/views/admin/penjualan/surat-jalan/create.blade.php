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
                            <div class="col-12">
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Kode
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.text :name="'kode'" placeholder="(OTOMATIS)" disabled />
                                    </div>
                                </div>

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
                                        <x-admin::input.select2 :name="'gudang_id'" :defer="false" :options="$this->optionsGudangId"
                                            placeholder="- Pilih Gudang -" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        No Polisi
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.text :name="'no_polisi'" placeholder="Masukkan No Polisi" />
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
                        <div class="mb-3">
                            <div class="row g-3">
                                <div class="col-12">
                                    <x-admin::input.text :name="'input_produk_nama'" placeholder="Produk" disabled />
                                </div>
                                <div class="col-md-8 col-12">
                                    <x-admin::input.text :name="'input_satuan_nama'" placeholder="Satuan" disabled />
                                </div>
                                <div class="col-md-4 col-12">
                                    <x-admin::input.number :name="'input_jumlah'" placeholder="Qty" />
                                </div>
                                <div class="col-12">
                                    <x-admin::buttons.create-edit-item :action="'editItem'" />
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%" class="text-uppercase">No</th>
                                        <th width="45%" class="text-uppercase">Produk</th>
                                        <th width="20%" class="text-uppercase">Satuan</th>
                                        <th width="15%" class="text-uppercase text-end">Qty</th>
                                        <th width="15%" class="text-uppercase text-end">Action</th>
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
                                                {{ $item['satuan_nama'] }}
                                            </td>
                                            <td class="text-end">
                                                {{ _number($item['jumlah']) }}
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
                    </div>

                    <x-admin::includes.pages.create-footer-action />
                </div>
            </form>
        </div>
    </div>
</div>
