<div>
    <div class="card">
        <div class="card-header bg-primary">
            <h5 class="card-title mb-0 fs-14 text-white">Saldo Kas</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="row mb-3">
                        <div class="col-12 mb-2 mb-lg-0">
                            <x-admin::input.date :name="'tanggal'" placeholder="Masukkan Tanggal" :defer="false" />
                        </div>
                    </div>

                    <div class="text-center mt-3 fw-medium">Total Saldo: {{ _number($total_saldo) }}</div>
                    <div style="height: 20rem" class="mt-3">
                        <livewire:livewire-pie-chart key="{{ $chart->reactiveKey() }}" :pie-chart-model="$chart" />
                    </div>
                </div>
                <div class="col-6">
                    <div class="table-responsive mt-3">
                        <table class="table table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th>Akun</th>
                                    <th class="text-end">Saldo</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($kass as $kas)
                                    <tr>
                                        <td>
                                            <a href="{{ $kas['route'] }}" target="_blank">
                                                {{ $kas['nama'] }}
                                            </a>
                                        </td>
                                        <td class="text-end">
                                            {{-- <a
                                                href="{{
                                                    route('admin.laporan.akuntansi.buku-besar', [
                                                        'cabang_ids' => [$cabang_id],
                                                        'akun_ids' => $kas['akun_id'],
                                                        'tanggal' => $tanggal,
                                                    ])
                                                }}"
                                                target="_blank"
                                            > --}}
                                            {{ _number($kas['saldo']) }}
                                            {{-- </a> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
