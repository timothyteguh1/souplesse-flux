<?php

namespace App\Livewire\Admin\Laporan\Pembelian\HistoryPembelianProduk;

use Livewire\Component;
use App\Models\Master\Cabang;
use App\Models\Master\Produk;
use Illuminate\Http\Response;
use App\Models\Master\Supplier;
use Illuminate\Support\Facades\Gate;
use App\Traits\Livewire\WithReportForm;
use App\Utilities\Constants\Const_Umum;

class Index extends Component
{
    use WithReportForm;

    public $menuTitle = 'History Pembelian Produk';
    protected $export_filename = 'laporan_history_pembelian_produk';
    protected $export_orientation = Const_Umum::ORIENTATION_LANDSCAPE;
    protected $export_view = 'admin.laporan.pembelian.history-pembelian-produk.index-export';
    protected $export_paper = Const_Umum::PAPER_A4;
    public $supplier_ids = [];
    public $produk_ids = [];
    public $tanggal;
    public $cabang_ids;

    public function mount()
    {
        abort_if(Gate::none(['admin.laporan.pembelian.history-pembelian-produk']), Response::HTTP_FORBIDDEN);
        $this->tanggal = _get_default_date_range();
        $cabang_ids = request()->get('cabang_ids');
        $produk_ids = request()->get('produk_ids');
        $supplier_ids = request()->get('supplier_ids');
        if ($cabang_ids) {
            $this->tanggal = _get_default_datetime_range(true);
            $this->cabang_ids = $cabang_ids;
            $this->produk_ids = $produk_ids ?: [];
            $this->supplier_ids = $supplier_ids ?: [];
            $this->prosesLihat();
        }
    }

    private function getData()
    {
        $validated = $this->validate([
            'cabang_ids' => [],
            'supplier_ids' => ['nullable'],
            'produk_ids' => ['nullable'],
            'tanggal' => ['required'],
        ]);

        $data = $validated;

        $cabangIds = $this->cabang_ids ?: auth()->user()->getPermissionCabangIds();
        $cabangs = Cabang::whereIn('id', $cabangIds)->get();

        $suppliers = $validated['supplier_ids'] ? Supplier::find($validated['supplier_ids']) : Supplier::all();
        $supplierIds = $validated['supplier_ids'] ? $validated['supplier_ids'] : $suppliers->pluck('id')->toArray();

        $produks = $validated['produk_ids'] ? Produk::find($validated['produk_ids']) : Produk::all();
        $produkIds = $validated['produk_ids'] ? $validated['produk_ids'] : $produks->pluck('id')->toArray();

        $data['cabangIds'] = $cabangIds;
        $data['cabangs'] = $cabangs;

        $data['isSemuaSupplier'] = $validated['supplier_ids'] ? false : true;
        $data['supplierIds'] = $supplierIds;
        $data['suppliers'] = $suppliers;

        $data['isSemuaProduk'] = $validated['produk_ids'] ? false : true;
        $data['produkIds'] = $produkIds;
        $data['produks'] = $produks;

        [$tanggalAwal, $tanggalAkhir] = _datetime_carbon_split_filter_date($validated['tanggal']);
        $data['tanggalAwal'] = $tanggalAwal;
        $data['tanggalAkhir'] = $tanggalAkhir;

        return $data;
    }

    public function render()
    {
        return view('admin.laporan.pembelian.history-pembelian-produk.index', $this->data)->layout($this->layout);
    }
}
