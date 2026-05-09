<?php

namespace App\Livewire\Admin\System\Dashboard\Persediaan;

use Carbon\Carbon;
use Livewire\Component;
use Carbon\CarbonPeriod;
use Livewire\Attributes\Computed;
use App\Utilities\SelectHelpers\SH_Umum;
use App\Models\Persediaan\PenambahanPersediaan;
use App\Models\Persediaan\PenguranganPersediaan;
use App\Models\Persediaan\TransferPersediaan;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;

class PenyesuaianPersediaan extends Component
{
    public $cabang_id;
    public $tahun;

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

        foreach ($intervals as $index => $interval) {
            $bulan = substr($interval->monthName, 0, 3);
            $tanggal_awal = $interval->clone()->startOfMonth();
            $tanggal_akhir = $interval->clone()->endOfMonth();

            $penyesuaianTambah = PenambahanPersediaan::query()
                ->where('cabang_id', $this->cabang_id)
                ->whereDate('tanggal', '<=', $tanggal_akhir)
                ->whereDate('tanggal', '>=', $tanggal_awal)
                ->get();
            $chart->addSeriesColumn('Penyesuaian Tambah', $bulan, $penyesuaianTambah->sum('grandtotal'));

            $penyesuaianKurang = PenguranganPersediaan::query()
                ->where('cabang_id', $this->cabang_id)
                ->whereDate('tanggal', '<=', $tanggal_akhir)
                ->whereDate('tanggal', '>=', $tanggal_awal)
                ->get();
            $chart->addSeriesColumn('Penyesuaian Kurang', $bulan, $penyesuaianKurang->sum('grandtotal'));


            $transferKurang = TransferPersediaan::query()
                ->where('cabang_id', $this->cabang_id)
                ->whereDate('tanggal', '<=', $tanggal_akhir)
                ->whereDate('tanggal', '>=', $tanggal_awal)
                ->get();
            $chart->addSeriesColumn('Transfer Kurang', $bulan, $transferKurang->sum('grandtotal'));

            $transferTambah = TransferPersediaan::query()
                ->where('cabang_tujuan_id', $this->cabang_id)
                ->whereDate('tanggal', '<=', $tanggal_akhir)
                ->whereDate('tanggal', '>=', $tanggal_awal)
                ->get();
            $chart->addSeriesColumn('Transfer Tambah', $bulan, $transferTambah->sum('grandtotal'));
        }

        return view('admin.system.dashboard.persediaan.penyesuaian-persediaan', [
            'chart' => $chart,
        ]);
    }
}
