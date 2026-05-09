<?php

namespace App\Livewire\Admin\System\Dashboard\Keuangan;

use Carbon\Carbon;
use Livewire\Component;
use Carbon\CarbonPeriod;
use Livewire\Attributes\Computed;
use App\Models\Keuangan\KasMasukDetail;
use App\Utilities\SelectHelpers\SH_Umum;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;

class KasMasuk extends Component
{
    public $cabang_ids = [];
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
            $kasMasukDetails = KasMasukDetail::whereHas('header', function ($query) use ($tanggal_awal, $tanggal_akhir) {
                $query->whereDate('tanggal', '<=', $tanggal_akhir)->whereDate('tanggal', '>=', $tanggal_awal);
            })
                ->whereRelation('header', 'cabang_id', $this->cabang_id)
                ->get();
            $chart->addColumn($bulan, $kasMasukDetails->sum('jumlah'), $colors[$index]);
        }

        return view('admin.system.dashboard.keuangan.kas-masuk', [
            'chart' => $chart,
        ]);
    }
}
