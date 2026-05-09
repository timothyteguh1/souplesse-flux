<div>
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <form wire:submit="submit">
                <div class="modal-header">
                    <h4 class="modal-title">{{ $index_modal_set ? 'Edit' : 'Tambah' }} Set</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body border-top border-bottom">
                    <div class="row">
                        <div class="col-12">
                            <x-admin::includes.alert-messages />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="row mb-3">
                                <label class="col-lg-3 col-form-label">
                                    Gudang
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-lg-9" wire:key="gudang_id">
                                    <x-admin::input.select2 :name="'gudang_id'" :defer="false" :options="$this->optionsGudangId"
                                        placeholder="- Pilih Gudang -" />
                                </div>
                            </div>

                            {{--
                                <div class="row mb-3">
                                <label class="col-lg-3 col-form-label">
                                Nama
                                <span class="text-danger">*</span>
                                </label>
                                <div class="col-lg-9">
                                <x-admin::input.text :name="'produk_teks'" placeholder="Nama Set" />
                                </div>
                                </div>
                            --}}

                            <div class="row mb-3">
                                <label class="col-lg-3 col-form-label">
                                    Satuan
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-lg-9">
                                    <x-admin::input.text :name="'satuan_teks'" placeholder="Satuan Set" />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-lg-3 col-form-label">
                                    Jumlah
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-lg-9">
                                    <x-admin::input.number :name="'jumlah'" placeholder="Jumlah" :defer="false" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-top border-light border-2 mb-3"></div>

                    <div class="mb-3" x-data>
                        <div class="row g-3">
                            <div class="col-12">
                                <x-admin::input.select2 :name="'input_set_produk_id'" :defer="false" placeholder="Produk"
                                    :parent="'ModalSet'" />
                            </div>
                            <div class="col-md-3 col-12">
                                <x-admin::input.select2 :name="'input_set_satuan_id'" :defer="false" placeholder="Satuan"
                                    :parent="'ModalSet'" />
                            </div>
                            <div class="col-md-2 col-12">
                                <x-admin::input.number :name="'input_set_jumlah'" placeholder="Qty" />
                            </div>
                            <div class="col-md-2 col-12">
                                <x-admin::input.number :name="'input_set_harga_satuan'" placeholder="Harga" />
                            </div>
                            <div class="col-md-3 col-12">
                                <x-admin::input.diskon :type="'input_set_diskon_satuan_type'" :name="'input_set_diskon_satuan'"
                                    placeholder="Diskon Satuan" />
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

                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%" class="text-uppercase">No</th>
                                            <th width="15%" class="text-uppercase">Produk</th>
                                            <th width="15%" class="text-uppercase">Satuan</th>
                                            <th width="10%" class="text-uppercase text-end">Qty</th>
                                            <th width="15%" class="text-uppercase text-end">Harga</th>
                                            <th width="10%" class="text-uppercase text-end" colspan="2">Diskon
                                            </th>
                                            <th width="15%" class="text-uppercase text-end">Subtotal</th>
                                            <th width="10%" class="text-uppercase text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $index => $item)
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
                                                    {{ _number($item['harga_satuan']) }}
                                                </td>
                                                <td class="text-end">{{ _number($item['diskon_satuan_persen']) }}%</td>
                                                <td class="text-end">
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
                                        <th>Diskon Set</th>
                                        <th class="text-end">
                                            <x-admin::input.diskon :type="'diskon_type'" :name="'diskon'"
                                                placeholder="Diskon Set" :defer="false" class="text-end" />
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

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
