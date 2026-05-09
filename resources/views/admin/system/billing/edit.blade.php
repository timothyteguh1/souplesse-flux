<div>
    @section('title', 'Ubah ' . $obj->nama)

    @section('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ $obj->getRouteIndex() }}">{{ $menuTitle }}</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ $obj->getRouteShow() }}">{{ $obj->nama }}</a>
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
                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Kode</label>
                            <div class="col-lg-9">
                                <x-admin::input.text :name="'kode'" placeholder="(OTOMATIS)" />
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
                                Perusahaan
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9">
                                <x-admin::input.select2id :id="'perusahaan_id'" :name="'perusahaan_id'" :options="\App\Utilities\SelectHelpers\System\SH_Perusahaan::active()"
                                    placeholder="- Pilih Perusahaan -" :defer="false" :allow-clear="false" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label"></label>
                            <div class="col-lg-9">
                                <x-admin::input.checkbox :name="'is_pkp'" :label="'PKP'" :value="$is_pkp"
                                    :inline="true" />

                                <x-admin::input.checkbox :name="'is_include_ppn'" :label="'Include PPN'" :value="$is_include_ppn"
                                    :inline="true" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">
                                PPN (%)
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9">
                                <x-admin::input.number :name="'ppn_percent'" placeholder="Masukkan PPN (%)" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">
                                Diskon
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9">
                                <x-admin::input.diskon :type="'diskon_type'" :name="'diskon'" placeholder="Diskon"
                                    :defer="false" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">
                                Beban Lain
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9">
                                <x-admin::input.number :name="'beban_lain'" placeholder="Beban Lainnya"
                                    :defer="false" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">Keterangan</label>
                            <div class="col-lg-9">
                                <x-admin::input.textarea :name="'keterangan'" placeholder="Keterangan" />
                            </div>
                        </div>
                    </div>


                    <div class="card-body pt-4 border-top">
                        <div class="mb-3">
                            <div class="row g-3">
                                <div class="col-md-3 col-12">
                                    <x-admin::input.text :name="'input_item'" placeholder="Item" />
                                </div>
                                <div class="col-md-2 col-12">
                                    <x-admin::input.number :name="'input_jumlah'" placeholder="Qty" />
                                </div>
                                <div class="col-md-2 col-12">
                                    <x-admin::input.number :name="'input_harga_satuan'" placeholder="Harga" />
                                </div>
                                <div class="col-md-3 col-12">
                                    <x-admin::input.diskon :type="'input_diskon_satuan_type'" :name="'input_diskon_satuan'"
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

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%" class="text-uppercase">No</th>
                                        <th width="25%" class="text-uppercase">Item</th>
                                        <th width="15%" class="text-uppercase text-end">Qty</th>
                                        <th width="15%" class="text-uppercase text-end">Harga</th>
                                        <th width="15%" class="text-uppercase text-end" colspan="2">Diskon</th>
                                        <th width="15%" class="text-uppercase text-end">Subtotal</th>
                                        <th width="10%" class="text-uppercase text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $item['item'] }}
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
                                <tfoot>
                                    <tr>
                                        <th colspan="6" class="text-end">TOTAL</th>
                                        <th class="text-end">{{ _number($total) }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <x-admin::includes.pages.create-footer-action />
                </div>
            </form>
        </div>
    </div>
    <!--end row-->
</div>
