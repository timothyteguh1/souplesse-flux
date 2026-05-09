<div>
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <form wire:submit="submit">
                <div class="modal-header">
                    <h4 class="modal-title">Pembayaran Faktur Penjualan</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <x-admin::includes.alert-messages />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="row mb-3">
                                <label class="col-lg-3 col-form-label">Tanggal</label>
                                <div class="col-lg-9">
                                    <x-admin::input.readonly :value="$transaksi['tanggal'] ?? ''" />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-lg-3 col-form-label">Customer</label>
                                <div class="col-lg-9">
                                    @php
                                        $customer = null;
                                        if (isset($transaksi['customer_id'])) {
                                            $customer = \App\Models\Master\Customer::find($transaksi['customer_id']);
                                        }
                                    @endphp

                                    <x-admin::input.readonly :value="$customer?->kode . ' -- ' . $customer?->nama" />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-lg-3 col-form-label">Gudang</label>
                                <div class="col-lg-9">
                                    @php
                                        $gudang = null;
                                        if (isset($transaksi['gudang_id'])) {
                                            $gudang = \App\Models\Master\Gudang::find($transaksi['gudang_id']);
                                        }
                                    @endphp

                                    <x-admin::input.readonly :value="$gudang?->kode . ' -- ' . $gudang?->nama" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-top border-light border-2 mb-3"></div>

                    <div class="mb-3" x-data>
                        <div class="row g-3">
                            <div class="col-xxl-4 col-sm-12">
                                <x-admin::input.select2id
                                    :id="'input_kas_id'"
                                    :name="'input_kas_id'"
                                    :options="\App\Utilities\SelectHelpers\Master\SH_Kas::user()"
                                    placeholder="Kas"
                                    :parent="'ModalPembayaran'"
                                />
                            </div>

                            <div class="col-xxl-4 col-sm-6">
                                <x-admin::input.text :name="'input_keterangan'" placeholder="Keterangan" />
                            </div>

                            <div class="col-xxl-2 col-sm-6">
                                <x-admin::input.number :name="'input_nominal'" placeholder="Nominal" />
                            </div>

                            <div class="col-xxl-2 col-sm-6">
                                @if ($index_edit_item === null)
                                    <x-admin::buttons.create-add-item />
                                @else
                                    <x-admin::buttons.create-edit-item />
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
                                            <th class="text-uppercase" width="10%">No</th>
                                            <th class="text-uppercase" width="30%">Kas</th>
                                            <th class="text-uppercase" width="35%">Keterangan</th>
                                            <th class="text-uppercase text-end" width="15%">Nominal</th>
                                            <th class="text-uppercase text-end" width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $index => $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $item['kas_nama'] }}
                                                </td>
                                                <td>
                                                    {{ $item['keterangan'] }}
                                                </td>
                                                <td class="text-end">
                                                    {{ _number($item['jumlah']) }}
                                                </td>
                                                <td class="text-end">
                                                    <button
                                                        type="button"
                                                        wire:click="edit({{ $index }})"
                                                        class="btn btn-sm btn-warning btn-icon waves-effect waves-light me-2"
                                                    >
                                                        <i class="ri-pencil-fill"></i>
                                                    </button>

                                                    <button
                                                        type="button"
                                                        wire:click="removeItem({{ $index }})"
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
                                            <th class="text-end" colspan="3">GRAND TOTAL</th>
                                            <th class="text-end">
                                                {{ _number($grandtotal) }}
                                            </th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th class="text-end" colspan="3">TOTAL BAYAR</th>
                                            <th class="text-end">
                                                {{ _number($total_bayar) }}
                                            </th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th class="text-end" colspan="3">KEMBALIAN</th>
                                            <th class="text-end">
                                                {{ _number($kembalian) }}
                                            </th>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
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
