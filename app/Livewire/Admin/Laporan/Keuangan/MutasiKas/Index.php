<?php

namespace App\Livewire\Admin\Laporan\Keuangan\MutasiKas;

use Livewire\Component;
use App\Models\Master\Kas;
use App\Models\Master\Cabang;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use App\Traits\Livewire\WithReportForm;
use App\Utilities\Constants\Const_Umum;

class Index extends Component
{
    use WithReportForm;

    public $menuTitle = 'Mutasi Kas';
    protected $export_filename = 'laporan_mutasi_kas';
    protected $export_orientation = Const_Umum::ORIENTATION_LANDSCAPE;
    protected $export_view = 'admin.laporan.keuangan.mutasi-kas.index-export';
    protected $export_paper = Const_Umum::PAPER_A4;
    public $tanggal;
    public $cabang_ids;
    public $kas_ids;

    public function mount()
    {
        abort_if(Gate::none(['admin.laporan.keuangan.mutasi-kas']), Response::HTTP_FORBIDDEN);
        $this->tanggal = _get_default_date_range();
    }

    private function getData()
    {
        $validated = $this->validate([
            'cabang_ids' => [],
            'tanggal' => ['required'],
            'kas_ids' => [],
        ]);

        $data = $validated;

        $cabangIds = $this->cabang_ids ?: auth()->user()->getPermissionCabangIds();
        $cabangs = Cabang::whereIn('id', $cabangIds)->get();

        $kasIds = $this->kas_ids ?: auth()->user()->getPermissionKasIds();
        $kass = Kas::whereIn('id', $kasIds)->get();

        $data['cabangIds'] = $cabangIds;
        $data['cabangs'] = $cabangs;
        $data['kass'] = $kass;

        [$tanggalAwal, $tanggalAkhir] = _datetime_carbon_split_filter_date($validated['tanggal']);
        $data['tanggalAwal'] = $tanggalAwal;
        $data['tanggalAkhir'] = $tanggalAkhir;

        return $data;
    }

    public function render()
    {
        return view('admin.laporan.keuangan.mutasi-kas.index', $this->data)->layout($this->layout);
    }
}
