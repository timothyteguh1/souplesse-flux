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
                            <div class="col-md-12 col-12">
                                <div class="card-title"><strong>Data Penyesuaian Tambah</strong></div>
                                <hr />

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
                                        Gudang
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <x-admin::input.select2id
                                            :id="'gudang_id'"
                                            :name="'gudang_id'"
                                            :defer="false"
                                            :options="\App\Utilities\SelectHelpers\Master\SH_Gudang::user()"
                                            placeholder="- Pilih Gudang -"
                                        />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-form-label">
                                        Keterangan
                                        <span class="text-danger">*</span>
                                    </label>
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
                                <div class="col-md-6 col-12">
                                    <x-admin::input.select2id
                                        :id="'input_produk_id'"
                                        :name="'input_produk_id'"
                                        :options="
                                            \App\Utilities\SelectHelpers\Master\SH_Produk::stokGudangWithStok(
                                                $gudang_id,
                                                false,
                                            )
                                        "
                                        :defer="false"
                                        placeholder="Produk"
                                    />
                                </div>
                                <div class="col-md-3 col-12">
                                    <x-admin::input.select2id
                                        :id="'input_satuan_id'"
                                        :name="'input_satuan_id'"
                                        :options="[]"
                                        :defer="false"
                                        placeholder="Satuan"
                                    />
                                </div>
                                <div class="col-md-3 col-12">
                                    <x-admin::input.date :name="'input_expired_date'" placeholder="Expired Date" />
                                </div>
                                <div class="col-md-3 col-12">
                                    <x-admin::input.text :name="'input_no_batch'" placeholder="No Batch" />
                                </div>
                                <div class="col-md-3 col-12">
                                    <x-admin::input.number :name="'input_jumlah'" placeholder="Qty Tambah" />
                                </div>

                                <div class="col-md-3 col-12">
                                    <x-admin::input.number :name="'input_harga_satuan'" placeholder="DPP" />
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
                                        <th width="15%" class="text-uppercase">Kode</th>
                                        <th width="20%" class="text-uppercase">Nama</th>
                                        <th width="15%" class="text-uppercase">Satuan</th>
                                        <th width="10%" class="text-uppercase">Expired Date</th>
                                        <th width="10%" class="text-uppercase">No Batch</th>
                                        <th width="10%" class="text-uppercase text-end">Qty Tambah</th>
                                        <th width="15%" class="text-uppercase text-end">DPP</th>
                                        <th width="15%" class="text-uppercase text-end">Subtotal</th>
                                        <th width="10%" class="text-uppercase text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>
                                                {{ $item['kode'] }}
                                            </td>
                                            <td>
                                                {{ $item['nama'] }}
                                            </td>
                                            <td>
                                                {{ $item['satuan_nama'] }}
                                            </td>
                                            <td>
                                                {{ $item['expired_date'] }}
                                            </td>
                                            <td>
                                                {{ $item['no_batch'] }}
                                            </td>
                                            <td class="text-end">
                                                {{ _number($item['jumlah']) }}
                                            </td>
                                            <td class="text-end">
                                                {{ _number($item['harga_satuan']) }}
                                            </td>
                                            <td class="text-end">
                                                {{ _number($item['subtotal']) }}
                                            </td>
                                            <td class="text-end">
                                                <button
                                                    type="button"
                                                    wire:click="edit({{ $loop->index }})"
                                                    class="btn btn-sm btn-warning btn-icon waves-effect waves-light me-2"
                                                >
                                                    <i class="ri-pencil-fill"></i>
                                                </button>

                                                <button
                                                    type="button"
                                                    wire:click="removeItem({{ $loop->index }})"
                                                    class="btn btn-sm btn-danger btn-icon waves-effect waves-light"
                                                >
                                                    <i class="ri-delete-bin-5-line"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="7" class="text-end">Total Nilai Persediaan yang Bertambah</th>
                                        <th class="text-end">{{ _number(collect($items)->sum('subtotal')) }}</th>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <span
                            class="btn btn-primary btn-load waves-effect waves-light"
                            role="button"
                            tabindex="0"
                            wire:click="submitDefault"
                            wire:keydown.enter="submitDefault"
                        >
                            <i class="ri-pencil-line align-bottom me-1"></i>
                            Simpan
                            <span
                                class="spinner-border flex-shrink-0 ms-1 align-bottom"
                                role="status"
                                wire:loading.delay
                                wire:target="submitDefault,submitAndCreate,submitAndShow,submitAndBackToIndex"
                            ></span>
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--end row-->
</div>
