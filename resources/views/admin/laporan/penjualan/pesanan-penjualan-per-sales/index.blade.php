@php
    use App\Utilities\Constants\Const_Umum;
@endphp
<div>
    @section('title', $menuTitle)

    <form wire:submit="prosesLihat">
        <x-admin::includes.pages.report-page-title />

        <div class="row">
            <div class="col-12">
                <x-admin::includes.alert-messages />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <x-admin::includes.pages.report-filter>
                        <div class="row g-3">
                            {{-- <div class="col-xxl-4 col-sm-6">
                                <x-admin::input.tags :id="'cabang_ids'" :name="'cabang_ids'" :options="\App\Utilities\SelectHelpers\Master\SH_Cabang::user()"
                                    :placeholder="'- Semua Cabang -'" />
                            </div> --}}
                            <div class="col-xxl-4 col-sm-6">
                                <x-admin::input.date-range :name="'tanggal'" placeholder="Masukkan Tanggal" />
                            </div>

                            <div class="col-xxl-4 col-sm-6">
                                <x-admin::input.tags :id="'customer_ids'" :name="'customer_ids'" :options="\App\Utilities\SelectHelpers\Master\SH_Customer::active()"
                                    :placeholder="'- Semua Customer -'" />
                            </div>


                            <div class="col-xxl-4 col-sm-6">
                                <x-admin::input.tags :id="'user_ids'" :name="'user_ids'" :options="\App\Utilities\SelectHelpers\System\SH_User::active()"
                                    :placeholder="'- Semua User -'" />
                            </div>

                            <div class="col-xxl-12 col-sm-12">
                                <x-admin::buttons.report-lihat />
                            </div>
                        </div>
                    </x-admin::includes.pages.report-filter>
                </div>

                @if ($is_lihat)
                    <x-admin::includes.pages.report-content>
                        <div class="row">
                            <div class="col-12 text-center h3">PESANAN PENJUALAN PER SALES</div>
                            <div class="col-12 my-3">
                                <table>
                                    {{-- <tr>
                                        <th width="100px">Cabang</th>
                                        <th>: {{ $cabangs?->pluck('nama')->implode(', ') }}</th>
                                    </tr> --}}
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>: {{ $tanggal }}</th>
                                    </tr>
                                    <tr>
                                        <th>Customer</th>
                                        <th>
                                            :
                                            {{ $isSemuaCustomer ? 'Semua Customer' : $customers?->pluck('nama')->implode(', ') }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Sales</th>
                                        <th>
                                            :
                                            {{ $isSemuaUser ? 'Semua Sales' : $users?->pluck('name')->implode(', ') }}
                                        </th>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <?php
                            $objs = App\Models\Penjualan\PesananPenjualan::query()
                                ->when($tanggal, function ($query) use ($tanggal) {
                                    return \App\Utilities\QueryHelpers\QH_DateTime::scopeDateRange($query, $tanggal, 'tanggal');
                                })
                                ->whereIn('cabang_id', $cabangIds)
                                ->when(!$isSemuaCustomer, function ($query) use ($customerIds) {
                                    return $query->whereIn('customer_id', $customerIds);
                                })
                                ->when(!$isSemuaUser, function ($query) use ($userIds) {
                                    return $query->whereHas('createdBy', function ($q) use ($userIds) {
                                        $q->whereIn('causer_id', $userIds);
                                    });
                                })
                                ->with(['customer', 'cabang', 'latestActivity.causer', 'createdBy.causer'])
                                ->get();
                            ?>

                            <div class="col-12 mt-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="5%">No</th>
                                            <th width="10%">Tanggal</th>
                                            <th width="10%">Kode</th>
                                            <th width="10%">Customer</th>
                                            <th width="10%">Sales</th>
                                            <th width="10%">Status</th>
                                            <th width="10%">Grand Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="align-middle">
                                        @foreach ($objs as $obj)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ _date_format_output($obj->tanggal) }}</td>
                                                <td>
                                                    <a href="{{ $obj->getRouteShow() }}">
                                                        {{ $obj->kode }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{ $obj?->customer?->getRouteShow() }}">
                                                        {{ $obj?->customer?->nama }}
                                                    </a>
                                                </td>
                                                <td>{{ $obj?->createdBy?->causer?->name }}</td>
                                                <td>{{ $obj->status }}</td>
                                                <td class="text-end">
                                                    {{ _number($obj->grandtotal) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-center" colspan="6">GRANDTOTAL</th>
                                            <td class="text-end">
                                                {{ _number($objs->sum('grandtotal')) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </x-admin::includes.pages.report-content>
                @endif
            </div>
        </div>
    </form>
</div>
