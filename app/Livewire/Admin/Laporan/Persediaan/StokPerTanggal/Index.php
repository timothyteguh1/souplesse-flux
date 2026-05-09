<?php

namespace App\Livewire\Admin\Laporan\Persediaan\StokPerTanggal;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Master\Cabang;
use App\Models\Master\Gudang;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use App\Traits\Livewire\WithReportForm;
use App\Utilities\Constants\Const_Date;
use App\Utilities\Constants\Const_Umum;

class Index extends Component
{
    use WithReportForm;

    public $menuTitle = 'Stok Per Tanggal';
    protected $export_filename = 'laporan_stok_per_tanggal';
    protected $export_orientation = Const_Umum::ORIENTATION_LANDSCAPE;
    protected $export_view = 'admin.laporan.persediaan.stok-per-tanggal.index-export';
    protected $export_paper = Const_Umum::PAPER_A4;
    public $gudang_ids = [];
    public $cabang_ids;
    public $tanggal;

    public function mount()
    {
        abort_if(Gate::none(['admin.laporan.persediaan.stok-per-tanggal']), Response::HTTP_FORBIDDEN);
        $this->tanggal = _get_default_date();
    }

    private function getData()
    {
        $validated = $this->validate([
            'cabang_ids' => [],
            'gudang_ids' => ['nullable'],
            'tanggal' => ['required'],
        ]);

        $data = $validated;

        $cabangIds = $this->cabang_ids ?: auth()->user()->getPermissionCabangIds();
        $cabangs = Cabang::whereIn('id', $cabangIds)->get();

        $gudangIds = $this->gudang_ids ?: auth()->user()->getPermissionGudangIds();
        $gudangs = Gudang::whereIn('id', $gudangIds)->get();

        $data['cabangIds'] = $cabangIds;
        $data['cabangs'] = $cabangs;
        $data['gudangIds'] = $gudangIds;
        $data['gudangs'] = $gudangs;
        $data['tanggalCarbon'] = Carbon::createFromFormat(Const_Date::DATE_FORMAT_OUTPUT, $validated['tanggal']);

        return $data;
    }

    public function render()
    {
        return view('admin.laporan.persediaan.stok-per-tanggal.index', $this->data)->layout($this->layout);
    }
}
