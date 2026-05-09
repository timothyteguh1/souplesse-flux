<?php

namespace App\Livewire\Admin\Laporan\Persediaan\MutasiNilaiPersediaan;

use Livewire\Component;
use App\Models\Master\Cabang;
use App\Models\Master\Produk;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use App\Traits\Livewire\WithReportForm;
use App\Utilities\Constants\Const_Umum;

class Index extends Component
{
    use WithReportForm;

    public $menuTitle = 'Mutasi Nilai Persediaan';
    protected $export_filename = 'laporan_mutasi_nilai_persediaan';
    protected $export_orientation = Const_Umum::ORIENTATION_LANDSCAPE;
    protected $export_view = 'admin.laporan.persediaan.mutasi-nilai-persediaan.index-export';
    protected $export_paper = Const_Umum::PAPER_A4;
    public $tanggal;
    public $produk_id;
    public $cabang_ids;

    public function mount()
    {
        abort_if(Gate::none(['admin.laporan.persediaan.mutasi-nilai-persediaan']), Response::HTTP_FORBIDDEN);
        $this->tanggal = _get_default_date_range();
    }

    private function getData()
    {
        $validated = $this->validate([
            'cabang_ids' => [],
            'tanggal' => ['required'],
            'produk_id' => ['required'],
        ]);

        $data = $validated;

        $data['produk'] = Produk::find($validated['produk_id']);
        $cabangIds = $this->cabang_ids ?: auth()->user()->getPermissionCabangIds();
        $cabangs = Cabang::whereIn('id', $cabangIds)->get();

        $data['cabangIds'] = $cabangIds;
        $data['cabangs'] = $cabangs;

        [$tanggalAwal, $tanggalAkhir] = _datetime_carbon_split_filter_date($validated['tanggal']);
        $data['tanggalAwal'] = $tanggalAwal;
        $data['tanggalAkhir'] = $tanggalAkhir;

        return $data;
    }

    public function render()
    {
        return view('admin.laporan.persediaan.mutasi-nilai-persediaan.index', $this->data)->layout($this->layout);
    }
}
