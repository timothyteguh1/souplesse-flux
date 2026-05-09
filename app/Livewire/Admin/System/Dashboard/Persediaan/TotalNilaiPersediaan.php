<?php

namespace App\Livewire\Admin\System\Dashboard\Persediaan;

use Carbon\Carbon;
use Livewire\Component;
use Carbon\CarbonPeriod;
use App\Models\System\MutasiStok;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use App\Utilities\SelectHelpers\SH_Umum;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;

class TotalNilaiPersediaan extends Component
{
    public $tahun;
    public $cabang_id;

    public function mount()
    {
        $this->tahun = now()->inUserTimezone()->year;
        $this->cabang_id = session()->get('cabang_id');
    }

    #[Computed(persist: true)]
    public function optionsTahun()
    {
        return SH_Umum::tahun();
    }

    public function render()
    {
        $chart = new ColumnChartModel();
        $chart->setJsonConfig([
            'markers.size' => 5,
            'tooltip.y.formatter' => '(val) => val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")',
            'yaxis.labels.formatter' => '(val) => val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")',
        ]);

        $intervals = CarbonPeriod::create(
            Carbon::create($this->tahun, 1, 1),
            '1 month',
            Carbon::create($this->tahun, 12, 1),
        );

        $colors = _get_hex_colors();

        foreach ($intervals as $index => $interval) {
            $bulan = substr($interval->monthName, 0, 3);
            $tanggal_awal = $interval->clone()->startOfMonth();
            $tanggal_akhir = $interval->clone()->endOfMonth();

            $grandtotalMutasiStok = MutasiStok::query()
                ->select(DB::raw('SUM(jumlah * harga) as grandtotal'))
                ->where('cabang_id', $this->cabang_id)
                ->whereDate('tanggal', '<=', $tanggal_akhir)
                ->first();

            $chart->addColumn($bulan, $grandtotalMutasiStok->grandtotal, $colors[$index]);
        }


        return view('admin.system.dashboard.persediaan.total-nilai-persediaan', [
            'chart' => $chart,
        ]);
    }
}
