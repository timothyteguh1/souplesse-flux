<?php

namespace App\Livewire\Admin\System\Dashboard\Penjualan;

use Carbon\Carbon;
use Livewire\Component;
use Carbon\CarbonPeriod;
use Livewire\Attributes\Computed;
use App\Utilities\SelectHelpers\SH_Umum;
use App\Models\Penjualan\FakturPenjualan;
use App\Utilities\SelectHelpers\Master\SH_Gudang;
use App\Utilities\SelectHelpers\SH_JenisTransaksi;
use App\Utilities\SelectHelpers\Master\SH_Customer;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;

class PenjualanProdukPerJenisTransaksi extends Component
{
    public $customer_id;
    public $gudang_id;
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

    #[Computed(persist: true)]
    public function optionsCustomerId()
    {
        return SH_Customer::active();
    }

    #[Computed(persist: true)]
    public function optionsGudangId()
    {
        return SH_Gudang::active();
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

        $jenis_transaksis = SH_JenisTransaksi::penjualan();

        foreach ($intervals as $index => $interval) {
            $bulan = substr($interval->monthName, 0, 3);
            $tanggal_awal = $interval->clone()->startOfMonth();
            $tanggal_akhir = $interval->clone()->endOfMonth();
            foreach ($jenis_transaksis as $jenis_transaksi) {
                $fakturPenjualans = FakturPenjualan::where('jenis_transaksi', $jenis_transaksi)
                    ->where('cabang_id', $this->cabang_id)
                    ->when($this->customer_id, function ($query) {
                        return $query->where('customer_id', $this->customer_id);
                    })
                    ->when($this->gudang_id, function ($query) {
                        return $query->where('gudang_id', $this->gudang_id);
                    })
                    ->whereDate('tanggal', '>=', $tanggal_awal)
                    ->whereDate('tanggal', '<=', $tanggal_akhir)
                    ->get();
                $chart->addSeriesColumn($jenis_transaksi, $bulan, $fakturPenjualans->sum('grandtotal'));
            }
        }


        return view('admin.system.dashboard.penjualan.penjualan-produk-per-jenis-transaksi', [
            'chart' => $chart,
        ]);
    }
}
