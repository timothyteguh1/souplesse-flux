<?php

namespace App\Livewire\Admin\Laporan\Keuangan\KartuKasBon;

use Livewire\Component;
use App\Models\Master\Cabang;
use App\Models\Master\Karyawan;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use App\Traits\Livewire\WithReportForm;
use App\Utilities\Constants\Const_Umum;

class Index extends Component
{
    use WithReportForm;

    public $menuTitle = 'Kartu Kas Bon';
    protected $export_filename = 'laporan_kartu_kas_bon';
    protected $export_orientation = Const_Umum::ORIENTATION_LANDSCAPE;
    protected $export_view = 'admin.laporan.keuangan.kartu-kas-bon.index-export';
    protected $export_paper = Const_Umum::PAPER_A4;
    public $cabang_ids;
    public $karyawan_id;
    public $tanggal;

    public function mount()
    {
        abort_if(Gate::none(['admin.laporan.keuangan.kartu-kas-bon']), Response::HTTP_FORBIDDEN);
        $this->tanggal = _get_default_date_range();
    }

    private function getData()
    {
        $validated = $this->validate([
            'cabang_ids' => [],
            'tanggal' => ['required'],
            'karyawan_id' => ['required'],
        ]);

        $data = $validated;

        $karyawan = Karyawan::find($validated['karyawan_id']);
        $cabangIds = $this->cabang_ids ?: auth()->user()->getPermissionCabangIds();
        $cabangs = Cabang::whereIn('id', $cabangIds)->get();

        $data['karyawan'] = $karyawan;
        $data['cabangIds'] = $cabangIds;
        $data['cabangs'] = $cabangs;

        [$tanggalAwal, $tanggalAkhir] = _datetime_carbon_split_filter_date($validated['tanggal']);
        $data['tanggalAwal'] = $tanggalAwal;
        $data['tanggalAkhir'] = $tanggalAkhir;

        return $data;
    }

    public function render()
    {
        return view('admin.laporan.keuangan.kartu-kas-bon.index', $this->data)->layout($this->layout);
    }
}
