<?php

namespace App\Livewire\Admin\Laporan\Penjualan\Penjualan;

use Livewire\Component;
use App\Models\Master\Cabang;
use Illuminate\Http\Response;
use App\Models\Master\Customer;
use Illuminate\Support\Facades\Gate;
use App\Traits\Livewire\WithReportForm;
use App\Utilities\Constants\Const_Umum;

class Index extends Component
{
    use WithReportForm;

    public $menuTitle = 'Penjualan';
    protected $export_filename = 'laporan_penjualan';
    protected $export_orientation = Const_Umum::ORIENTATION_LANDSCAPE;
    protected $export_view = 'admin.laporan.penjualan.penjualan.index-export';
    protected $export_paper = Const_Umum::PAPER_A4;
    public $customer_ids = [];
    public $tanggal;
    public $cabang_ids;

    public function mount()
    {
        abort_if(Gate::none(['admin.laporan.penjualan.penjualan']), Response::HTTP_FORBIDDEN);
        $this->tanggal = _get_default_date_range();
    }

    private function getData()
    {
        $validated = $this->validate([
            'cabang_ids' => [],
            'customer_ids' => ['nullable'],
            'tanggal' => ['required'],
        ]);

        $data = $validated;

        $cabangIds = $this->cabang_ids ?: auth()->user()->getPermissionCabangIds();
        $cabangs = Cabang::whereIn('id', $cabangIds)->get();

        $customers = $validated['customer_ids'] ? Customer::find($validated['customer_ids']) : Customer::all();
        $customerIds = $validated['customer_ids'] ? $validated['customer_ids'] : $customers->pluck('id')->toArray();

        $data['isSemuaCustomer'] = $validated['customer_ids'] ? false : true;
        $data['customerIds'] = $customerIds;
        $data['customers'] = $customers;
        $data['cabangIds'] = $cabangIds;
        $data['cabangs'] = $cabangs;

        [$tanggalAwal, $tanggalAkhir] = _datetime_carbon_split_filter_date($validated['tanggal']);
        $data['tanggalAwal'] = $tanggalAwal;
        $data['tanggalAkhir'] = $tanggalAkhir;

        return $data;
    }

    public function render()
    {
        return view('admin.laporan.penjualan.penjualan.index', $this->data)->layout($this->layout);
    }
}
