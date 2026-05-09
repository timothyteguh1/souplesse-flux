<?php

namespace App\Livewire\Admin\Laporan\Keuangan\LabaRugi;

use Livewire\Component;
use App\Models\Master\Cabang;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use App\Traits\Livewire\WithReportForm;
use App\Utilities\Constants\Const_Umum;

class Index extends Component
{
    use WithReportForm;

    public $menuTitle = 'Laba Rugi';
    protected $export_filename = 'laporan_laba-rugi';
    protected $export_orientation = Const_Umum::ORIENTATION_LANDSCAPE;
    protected $export_view = 'admin.laporan.keuangan.laba-rugi.index-export';
    protected $export_paper = Const_Umum::PAPER_A4;
    public int $tahun;
    public int $bulan;
    public $cabang_ids;

    public function mount()
    {
        abort_if(Gate::none(['admin.laporan.keuangan.laba-rugi']), Response::HTTP_FORBIDDEN);
        $this->tahun = _datetime_carbon_db(_get_default_date())->year;
        $this->bulan = _datetime_carbon_db(_get_default_date())->month;
    }

    private function getData()
    {
        $validated = $this->validate([
            'cabang_ids' => [],
            'tahun' => ['required'],
            'bulan' => ['required'],
        ]);

        $data = $validated;

        $cabangIds = $this->cabang_ids ?: auth()->user()->getPermissionCabangIds();
        $cabangs = Cabang::whereIn('id', $cabangIds)->get();

        $data['cabangIds'] = $cabangIds;
        $data['cabangs'] = $cabangs;

        return $data;
    }

    public function render()
    {
        return view('admin.laporan.keuangan.laba-rugi.index', $this->data)->layout($this->layout);
    }
}
