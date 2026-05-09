<?php

namespace App\Livewire\Admin\Laporan\Persediaan\KartuStok;

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

    public $menuTitle = 'Kartu Stok';
    protected $export_filename = 'laporan_kartu_stok';
    protected $export_orientation = Const_Umum::ORIENTATION_LANDSCAPE;
    protected $export_view = 'admin.laporan.persediaan.kartu-stok.index-export';
    protected $export_paper = Const_Umum::PAPER_A4;
    public $gudang_ids = [];
    public $produk_ids = [];
    public $tanggal;
    public $cabang_ids;

    public function mount()
    {
        abort_if(Gate::none(['admin.laporan.persediaan.kartu-stok']), Response::HTTP_FORBIDDEN);
        $this->tanggal = _get_default_date_range();

        $produk_ids = request()->get('produk_ids');
        $gudang_ids = request()->get('gudang_ids');
        $tanggal = request()->get('tanggal');
        $cabang_ids = request()->get('cabang_ids');

        if ($tanggal) {
            $this->tanggal = $tanggal;
            $this->produk_ids = $produk_ids;
            $this->gudang_ids = $gudang_ids;
            $this->cabang_ids = $cabang_ids;

            $this->prosesLihat();
        }
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

        $produks = $validated['produk_ids'] ? Produk::find($validated['produk_ids']) : Produk::all();
        $produkIds = $validated['produk_ids'] ? $validated['produk_ids'] : $produks->pluck('id')->toArray();

        $gudangIds = $this->gudang_ids ?: auth()->user()->getPermissionGudangIds();
        $gudangs = Gudang::whereIn('id', $gudangIds)->get();

        $cabangIds = $this->cabang_ids ?: auth()->user()->getPermissionCabangIds();
        $cabangs = Cabang::whereIn('id', $cabangIds)->get();

        $data['gudangIds'] = $gudangIds;
        $data['gudangs'] = $gudangs;
        $data['produkIds'] = $produkIds;
        $data['produks'] = $produks;
        $data['cabangIds'] = $cabangIds;
        $data['cabangs'] = $cabangs;

        [$tanggalAwal, $tanggalAkhir] = _datetime_carbon_split_filter_date($validated['tanggal']);
        $data['tanggalAwal'] = $tanggalAwal;
        $data['tanggalAkhir'] = $tanggalAkhir;

        return $data;
    }

    public function render()
    {
        return view('admin.laporan.persediaan.kartu-stok.index', $this->data)->layout($this->layout);
    }
}
