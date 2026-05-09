<?php

namespace App\Livewire\Admin\Laporan\Pembelian\FakturPembelian;

use Livewire\Component;
use App\Models\Master\Cabang;
use Illuminate\Http\Response;
use App\Models\Master\Supplier;
use Illuminate\Support\Facades\Gate;
use App\Traits\Livewire\WithReportForm;
use App\Utilities\Constants\Const_Umum;

class Index extends Component
{
    use WithReportForm;

    public $menuTitle = 'Faktur Pembelian';
    protected $export_filename = 'laporan_faktur_pembelian';
    protected $export_orientation = Const_Umum::ORIENTATION_LANDSCAPE;
    protected $export_view = 'admin.laporan.pembelian.faktur-pembelian.index-export';
    protected $export_paper = Const_Umum::PAPER_A4;
    public $supplier_ids = [];
    public $tanggal;
    public $cabang_ids;

    public function mount()
    {
        abort_if(Gate::none(['admin.laporan.pembelian.faktur-pembelian']), Response::HTTP_FORBIDDEN);
        $this->tanggal = _get_default_date_range();
    }

    private function getData()
    {
        $validated = $this->validate([
            'cabang_ids' => [],
            'supplier_ids' => ['nullable'],
            'tanggal' => ['required'],
        ]);

        $data = $validated;

        $cabangIds = $this->cabang_ids ?: auth()->user()->getPermissionCabangIds();
        $cabangs = Cabang::whereIn('id', $cabangIds)->get();

        $suppliers = $validated['supplier_ids'] ? Supplier::find($validated['supplier_ids']) : Supplier::all();
        $supplierIds = $validated['supplier_ids'] ? $validated['supplier_ids'] : $suppliers->pluck('id')->toArray();


        $data['cabangIds'] = $cabangIds;
        $data['cabangs'] = $cabangs;

        $data['isSemuaSupplier'] = $validated['supplier_ids'] ? false : true;
        $data['supplierIds'] = $supplierIds;
        $data['suppliers'] = $suppliers;

        [$tanggalAwal, $tanggalAkhir] = _datetime_carbon_split_filter_date($validated['tanggal']);
        $data['tanggalAwal'] = $tanggalAwal;
        $data['tanggalAkhir'] = $tanggalAkhir;

        return $data;
    }

    public function render()
    {
        return view('admin.laporan.pembelian.faktur-pembelian.index', $this->data)->layout($this->layout);
    }
}
