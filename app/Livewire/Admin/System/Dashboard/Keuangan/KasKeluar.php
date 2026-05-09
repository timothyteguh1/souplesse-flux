<?php

namespace App\Livewire\Admin\System\Dashboard\Keuangan;

use Carbon\Carbon;
use Livewire\Component;
use Carbon\CarbonPeriod;
use Livewire\Attributes\Computed;
use App\Models\Master\KategoriBeban;
use App\Models\Keuangan\KasKeluarDetail;
use App\Utilities\SelectHelpers\SH_Umum;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;

class KasKeluar extends Component
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
        $chart = (new ColumnChartModel())->multiColumn();
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

        $kategoriBebans = KategoriBeban::all();

        foreach ($intervals as $index => $interval) {
            $bulan = substr($interval->monthName, 0, 3);
            $tanggal_awal = $interval->clone()->startOfMonth();
            $tanggal_akhir = $interval->clone()->endOfMonth();
            foreach ($kategoriBebans as $kategoriBeban) {
                $kasKeluarDetails = KasKeluarDetail::whereRelation('beban', 'kategori_beban_id', $kategoriBeban->id)
                    ->whereHas('header', function ($query) use ($tanggal_awal, $tanggal_akhir) {
                        $query->whereDate('tanggal', '<=', $tanggal_akhir)->whereDate('tanggal', '>=', $tanggal_awal);
                    })
                    ->whereRelation('header', 'cabang_id', $this->cabang_id)
                    ->get();
                $chart->addSeriesColumn($kategoriBeban->nama, $bulan, $kasKeluarDetails->sum('jumlah'));
            }
        }

        return view('admin.system.dashboard.keuangan.kas-keluar', [
            'chart' => $chart,
        ]);
    }
}
