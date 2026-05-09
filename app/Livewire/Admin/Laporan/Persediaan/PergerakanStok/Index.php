<?php

namespace App\Livewire\Admin\Laporan\Persediaan\PergerakanStok;

use Livewire\Component;
use App\Models\Master\Cabang;
use App\Models\Master\Gudang;
use App\Models\Master\Produk;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use App\Traits\Livewire\WithReportForm;
use App\Utilities\Constants\Const_Umum;

class Index extends Component
{
    use WithReportForm;

    public $menuTitle = 'Pergerakan Stok';
    protected $export_filename = 'laporan_pergerakan_stok';
    protected $export_orientation = Const_Umum::ORIENTATION_LANDSCAPE;
    protected $export_view = 'admin.laporan.persediaan.pergerakan-stok.index-export';
    protected $export_paper = Const_Umum::PAPER_A4;
    public $cabang_ids;
    public $gudang_ids = [];
    public $produk_ids = [];
    public $tanggal;

    public function mount()
    {
        abort_if(Gate::none(['admin.laporan.persediaan.pergerakan-stok']), Response::HTTP_FORBIDDEN);
        $this->tanggal = _get_default_date_range();
    }

    private function getData()
    {
        $validated = $this->validate([
            'cabang_ids' => [],
            'produk_ids' => ['nullable'],
            'gudang_ids' => ['nullable'],
            'tanggal' => ['required'],
        ]);

        $data = $validated;

        $cabangIds = $this->cabang_ids ?: auth()->user()->getPermissionCabangIds();
        $cabangs = Cabang::whereIn('id', $cabangIds)->get();

        $gudangIds = $this->gudang_ids ?: auth()->user()->getPermissionGudangIds();
        $gudangs = Gudang::whereIn('id', $gudangIds)->get();

        $produks = $validated['produk_ids'] ? Produk::find($validated['produk_ids']) : Produk::all();
        $produkIds = $validated['produk_ids'] ?: $produks->pluck('id')->toArray();

        $data['cabangIds'] = $cabangIds;
        $data['cabangs'] = $cabangs;
        $data['gudangIds'] = $gudangIds;
        $data['gudangs'] = $gudangs;
        $data['produkIds'] = $produkIds;
        $data['produks'] = $produks;

        [$tanggalAwal, $tanggalAkhir] = _datetime_carbon_split_filter_date($validated['tanggal']);
        $data['tanggalAwal'] = $tanggalAwal;
        $data['tanggalAkhir'] = $tanggalAkhir;

        return $data;
    }

    public function render()
    {
        return view('admin.laporan.persediaan.pergerakan-stok.index', $this->data)->layout($this->layout);
    }
}
