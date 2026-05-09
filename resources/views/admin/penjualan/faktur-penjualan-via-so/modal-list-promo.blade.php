@php
    use App\Utilities\Constants\Const_Umum;
@endphp
<div>
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">List Promo</h4>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <x-admin::includes.alert-messages />
                    </div>
                </div>

                <x-admin::includes.pages.browse-filter>
                    <div class="row g-3 mb-3">
                        <div class="col-xxl-3 col-sm-6">
                            <x-admin::input.search-box :name="'keyword'" placeholder="Nama Promo" />
                        </div>
                        <!--end col-->

                        <div class="col-xxl-3 col-sm-6">
                            <x-admin::input.select2 :parent="'ModalListPromo'" :name="'customer_ids'" :options="\App\Utilities\SelectHelpers\Master\SH_Customer::active()"
                                :placeholder="'- Semua Customer -'" />
                        </div>
                        <!--end col-->

                        <div class="col-xxl-3 col-sm-6">
                            <x-admin::input.select2 :parent="'ModalListPromo'" :name="'supplier_ids'" :options="\App\Utilities\SelectHelpers\Master\SH_Supplier::active()"
                                :placeholder="'- Semua Supplier -'" />
                        </div>
                        <!--end col-->

                        <div class="col-xxl-3 col-sm-6">
                            <x-admin::input.select2 :parent="'ModalListPromo'" :name="'produk_ids'" :options="\App\Utilities\SelectHelpers\Master\SH_Produk::active()"
                                :placeholder="'- Semua Produk -'" />
                        </div>
                        <!--end col-->

                        <div class="col-12">
                            <button type="button" wire:click="refreshInfo([])"
                                class="btn btn-secondary w-100 btn-load ">
                                <span class="d-flex align-items-center">
                                    <span class="flex-grow-1 me-2">
                                        <i class="ri-equalizer-fill me-1 align-bottom"></i>
                                        Filters
                                    </span>
                                </span>
                            </button>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </x-admin::includes.pages.browse-filter>

                <form wire:submit="submit">
                    <div class="row mt-3">
                        <div class="col-12">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="10%">Nama Promo</th>
                                        <th width="10%">Periode Promo</th>
                                        <th width="15%">Customer</th>
                                        <th width="15%">Supplier</th>
                                        <th width="15%">Produk</th>
                                        <th class="text-end" width="5%">Diskon 1</th>
                                        <th class="text-end" width="5%">Diskon 2</th>
                                        <th class="text-end" width="5%">Diskon 3</th>
                                        <th class="text-end" width="5%">Diskon 4</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($objs as $obj)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $obj->nama }}</td>
                                            <td>
                                                {{ _date_format_output($obj->tanggal_awal) }} -
                                                {{ _date_format_output($obj->tanggal_akhir) }}
                                            </td>
                                            <td>
                                                @if (!$obj->promoCustomers()->first() && $obj->is_promo_grosir)
                                                    Semua Customer Kelas Grosir
                                                @elseif (!$obj->promoCustomers()->first())
                                                    Semua Customer
                                                @else
                                                    {{ implode(', ', $obj->promoCustomers()->with('customer')->get()->pluck('customer.nama')->toArray()) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (!$obj->promoSuppliers()->first())
                                                    Semua Supplier
                                                @else
                                                    {{ implode(', ', $obj->promoSuppliers()->with('supplier')->get()->pluck('supplier.nama')->toArray()) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (!$obj->promoProduks()->first())
                                                    Semua Produk
                                                @else
                                                    {!! implode('<br>', $obj->promoProduks()->with('produk')->get()->pluck('produk.nama')->toArray()) !!}
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if ($obj->diskon_satuan_type_1 == Const_Umum::DISKON_TYPE_RP)
                                                    {{ $obj->diskon_satuan_type_1 . '. ' . _number($obj->diskon_satuan_1) }}
                                                @elseif ($obj->diskon_satuan_type_1 == Const_Umum::DISKON_TYPE_PERCENT)
                                                    {{ _number($obj->diskon_satuan_1) . $obj->diskon_satuan_type_1 }}
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if ($obj->diskon_satuan_type_2 == Const_Umum::DISKON_TYPE_RP)
                                                    {{ $obj->diskon_satuan_type_2 . '. ' . _number($obj->diskon_satuan_2) }}
                                                @elseif ($obj->diskon_satuan_type_2 == Const_Umum::DISKON_TYPE_PERCENT)
                                                    {{ _number($obj->diskon_satuan_2) . $obj->diskon_satuan_type_2 }}
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if ($obj->diskon_satuan_type_3 == Const_Umum::DISKON_TYPE_RP)
                                                    {{ $obj->diskon_satuan_type_3 . '. ' . _number($obj->diskon_satuan_3) }}
                                                @elseif ($obj->diskon_satuan_type_3 == Const_Umum::DISKON_TYPE_PERCENT)
                                                    {{ _number($obj->diskon_satuan_3) . $obj->diskon_satuan_type_3 }}
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if ($obj->diskon_satuan_type_4 == Const_Umum::DISKON_TYPE_RP)
                                                    {{ $obj->diskon_satuan_type_4 . '. ' . _number($obj->diskon_satuan_4) }}
                                                @elseif ($obj->diskon_satuan_type_4 == Const_Umum::DISKON_TYPE_PERCENT)
                                                    {{ _number($obj->diskon_satuan_4) . $obj->diskon_satuan_type_4 }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button type="button" wire:click="submit('{{ $obj->id }}')"
                                                    class="btn btn-sm btn-primary ">
                                                    Pilih Promo
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="border-top border-light border-2 mb-3"></div>

                </form>
            </div>
        </div>
    </div>
</div>
