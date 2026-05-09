<x-admin::layouts.export>
    <x-admin::includes.reports.export-header title="Laporan Pesanan Penjualan Per Sales" :file_type="$file_type">
        <tr>
            <td>Tanggal : {{ $tanggal }}</td>
        </tr>
        <tr>
            <td>Customer : {{ $isSemuaCustomer ? 'Semua Customer' : $customers->pluck('nama')->implode(', ') }}</td>
        </tr>
        <tr>
            <td>Sales : {{ $isSemuaUser ? 'Semua Sales' : $users->pluck('name')->implode(', ') }}</td>
        </tr>
    </x-admin::includes.reports.export-header>

    <div class="row mt-10">
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

        <div class="col-12">
            <table class="bordered mb-20">
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kode</th>
                        <th>Customer</th>
                        <th>Sales</th>
                        <th>Status</th>
                        <th class="text-end">Grand Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($objs as $obj)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ _date_format_output($obj->tanggal) }}</td>
                            <td>
                                {{ $obj->kode }}
                            </td>
                            <td>
                                {{ $obj?->customer?->nama }}
                            </td>
                            <td>{{ $obj?->createdBy?->causer?->name }}</td>
                            <td>{{ $obj->status }}</td>
                            <td class="text-end">
                                {{ _numberReport($obj->grandtotal, $file_type) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-center" colspan="6">GRANDTOTAL</th>
                        <td class="text-end">
                            {{ _numberReport($objs->sum('grandtotal'), $file_type) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-admin::layouts.export>
