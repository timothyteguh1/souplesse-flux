<div>
    @section('title', $menuTitle)

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <x-admin::includes.alert-messages />
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                {{ $menuTitle }}
                            </h6>
                        </div>
                        <form wire:submit="submit">
                            <div class="card-body p-4">
                                <h5>Stok Awal akan dimasukkan ke dalam Gudang Utama</h5>
                                <div class="mb-3">
                                    <div class="row g-3">
                                        <div class="col-md-6 col-12">
                                            <x-admin::input.select2id :id="'input_produk_id'" :name="'input_produk_id'"
                                                :options="$this->optionsInputProdukId" :defer="false" placeholder="Produk" />
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <x-admin::input.select2id :id="'input_satuan_id'" :name="'input_satuan_id'"
                                                :options="[]" :defer="false" placeholder="Satuan" />
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <x-admin::input.date :name="'input_expired_date'" placeholder="Expired Date" />
                                        </div>
                                        {{-- <div class="col-md-3 col-12">
                                    <x-admin::input.text :name="'input_no_batch'" placeholder="No Batch" />
                                </div> --}}
                                        <div class="col-md-3 col-12">
                                            <x-admin::input.number :name="'input_jumlah'" placeholder="Qty Tambah" />
                                        </div>

                                        <div class="col-md-3 col-12">
                                            <x-admin::input.number :name="'input_harga_satuan'" placeholder="DPP" />
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <x-admin::input.text :name="'input_keterangan'" placeholder="Keterangan" />
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
                                                <th width="15%" class="text-uppercase">Kode</th>
                                                <th width="20%" class="text-uppercase">Nama</th>
                                                <th width="15%" class="text-uppercase">Satuan</th>
                                                <th width="10%" class="text-uppercase">Expired Date</th>
                                                {{-- <th width="10%" class="text-uppercase">No Batch</th> --}}
                                                <th width="10%" class="text-uppercase text-end">Qty Tambah</th>
                                                <th width="15%" class="text-uppercase text-end">DPP</th>
                                                <th width="15%" class="text-uppercase text-end">Subtotal</th>
                                                <th width="10%" class="text-uppercase text-end">Keterangan</th>
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
                                                    {{-- <td>
                                                {{ $item['no_batch'] }}
                                            </td> --}}
                                                    <td class="text-end">
                                                        {{ _number($item['jumlah']) }}
                                                    </td>
                                                    <td class="text-end">
                                                        {{ _number($item['harga_satuan']) }}
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

                                                        <button type="button"
                                                            wire:click="removeItem({{ $loop->index }})"
                                                            class="btn btn-sm btn-danger btn-icon waves-effect waves-light">
                                                            <i class="ri-delete-bin-5-line"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="6" class="text-end">Total Nilai Stok Awal</th>
                                                <th class="text-end">{{ _number(collect($items)->sum('subtotal')) }}
                                                </th>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <x-admin::buttons.app-update />
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end col -->
            </div>
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
</div>
