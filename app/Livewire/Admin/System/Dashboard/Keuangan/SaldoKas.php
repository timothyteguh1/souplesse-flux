<?php

namespace App\Livewire\Admin\System\Dashboard\Keuangan;

use App\Models\Master\Kas;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Livewire\Component;

class SaldoKas extends Component
{
    public $cabang_id;
    public $tanggal;
    public $kass;
    public $total_saldo = 0;

    public function mount()
    {
        $this->tanggal = _get_default_date();
        $this->cabang_id = session()->get('cabang_id');
    }

    public function render()
    {
        $kass = Kas::all();
        $this->total_saldo = 0;
        $tanggal = _date_format_db($this->tanggal);

        $this->kass = [];
        $chart = new PieChartModel();
        foreach ($kass as $kas) {
            $saldoAkhir = \App\Models\System\MutasiTransaksi::query()
                ->where('vendor_id', $kas->id)
                ->where('cabang_id', $this->cabang_id)
                ->whereDate('tanggal', '<=', $tanggal)
                ->sum('jumlah');
            $saldoAkhir = $saldoAkhir ?: 0;
            $this->total_saldo += $saldoAkhir;

            $chart->addSlice($kas->nama, $saldoAkhir, '');
            $this->kass[] = [
                'id' => $kas->id,
                'nama' => $kas->nama,
                'saldo' => $saldoAkhir,
                'akun_id' => $kas->akun_id,
                'route' => $kas->getRouteShow(),
            ];
        }

        $chart->asDonut();
        $chart->withoutLegend();
        $chart->setColors(_get_hex_colors());
        $chart->setJsonConfig([
            'tooltip.y.formatter' => '(val) => val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")',
        ]);

        return view('admin.system.dashboard.keuangan.saldo-kas', [
            'chart' => $chart,
        ]);
    }
}
