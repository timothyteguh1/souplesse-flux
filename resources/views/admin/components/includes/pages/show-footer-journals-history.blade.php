@props([
    'obj',
])

<div class="card">
    <div class="card-header bg-success-subtle">
        <h6 class="card-title mb-0">Journals History</h6>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th width="15%">Tanggal</th>
                        <th width="20%">Jurnal</th>
                        <th width="35%">Akun</th>
                        <th width="15%" class="text-end">Debit</th>
                        <th width="15%" class="text-end">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalDebit = 0;
                        $totalKredit = 0;
                    @endphp

                    @foreach ($obj->jurnals as $jurnal)
                        @php
                            $jurnalDetails = [];
                            if ($jurnal) {
                                $jurnalDetails = $jurnal
                                    ->details()
                                    ->with(['jurnal', 'akun'])
                                    ->get();
                            }
                        @endphp

                        @foreach ($jurnalDetails as $detail)
                            @php
                                $debit = $detail->jumlah > 0 ? $detail->jumlah : 0;
                                $kredit = $detail->jumlah <= 0 ? abs($detail->jumlah) : 0;

                                $totalDebit += $debit;
                                $totalKredit += $kredit;
                            @endphp

                            <tr>
                                @if ($loop->first)
                                    <td rowspan="{{ $jurnalDetails->count() }}">
                                        {{ $jurnal->tanggal }}
                                    </td>
                                    <td rowspan="{{ $jurnalDetails->count() }}">
                                        <a href="{{ $jurnal->getRouteShow() }}">
                                            {{ $jurnal->kode }}
                                            <br />
                                            {{ $jurnal->nama }}
                                        </a>
                                    </td>
                                @endif

                                <td>
                                    <a href="{{ $detail->kas->getRouteShow() }}">
                                        <span @class(['ps-5' => $detail->jumlah <= 0])></span>
                                        {{ $detail->kas->kode }} &mdash;
                                        {{ $detail->kas->nama }}
                                    </a>
                                </td>
                                <td class="text-end">
                                    {{ $debit ? _number($debit) : '' }}
                                </td>
                                <td class="text-end">
                                    {{ $kredit ? _number($kredit) : '' }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">TOTAL</th>
                        <th class="text-end">{{ _number($totalDebit) }}</th>
                        <th class="text-end">{{ _number($totalKredit) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
