<?php

namespace App\Livewire\Admin\Laporan\Keuangan\KasBon;

use App\Models\Master\Karyawan;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\Master\Cabang;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use App\Traits\Livewire\WithReportForm;
use App\Utilities\Constants\Const_Date;
use App\Utilities\Constants\Const_Umum;

class Index extends Component
{
    use WithReportForm;

    public $menuTitle = 'Kas Bon';
    protected $export_filename = 'laporan_kas_bon';
    protected $export_orientation = Const_Umum::ORIENTATION_LANDSCAPE;
    protected $export_view = 'admin.laporan.keuangan.kas-bon.index-export';
    protected $export_paper = Const_Umum::PAPER_A4;
    public $tanggal;
    public $karyawan_id;
    public $cabang_ids;

    public function mount()
    {
        abort_if(Gate::none(['admin.laporan.keuangan.kas-bon']), Response::HTTP_FORBIDDEN);
        $this->tanggal = _get_default_date();
    }

    private function getData()
    {
        $validated = $this->validate([
            'cabang_ids' => [],
            'tanggal' => ['required'],
            'karyawan_id' => [],
        ]);

        $data = $validated;

        $cabangIds = $this->cabang_ids ?: auth()->user()->getPermissionCabangIds();
        $cabangs = Cabang::whereIn('id', $cabangIds)->get();
        $karyawan = Karyawan::find($validated['karyawan_id']);

        $data['cabangIds'] = $cabangIds;
        $data['cabangs'] = $cabangs;
        $data['karyawan'] = $karyawan;
        $data['tanggalCarbon'] = Carbon::createFromFormat(Const_Date::DATE_FORMAT_OUTPUT, $validated['tanggal'])->endOfDay();

        return $data;
    }

    public function render()
    {
        return view('admin.laporan.keuangan.kas-bon.index', $this->data)->layout($this->layout);
    }
}
