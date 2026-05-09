<?php

namespace App\Livewire\Admin\Laporan\Penjualan\HistoryPenjualanProduk;

use Livewire\Component;
use App\Models\Master\Cabang;
use App\Models\Master\Produk;
use Illuminate\Http\Response;
use App\Models\Master\Customer;
use Illuminate\Support\Facades\Gate;
use App\Traits\Livewire\WithReportForm;
use App\Utilities\Constants\Const_Umum;

class Index extends Component
{
    use WithReportForm;

    public $menuTitle = 'History Penjualan Produk';
    protected $export_filename = 'lapotan_history_penjualan_produk';
    protected $export_orientation = Const_Umum::ORIENTATION_LANDSCAPE;
    protected $export_view = 'admin.laporan.penjualan.history-penjualan-produk.index-export';
    protected $export_paper = Const_Umum::PAPER_A4;
    public $customer_ids = [];
    public $produk_ids = [];
    public $tanggal;
    public $cabang_ids;

    public function mount()
    {
        abort_if(Gate::none(['admin.laporan.penjualan.history-penjualan-produk']), Response::HTTP_FORBIDDEN);
        $this->tanggal = _get_default_date_range();
        $cabang_ids = request()->get('cabang_ids');
        $customer_ids = request()->get('customer_ids');
        $produk_ids = request()->get('produk_ids');
        if ($cabang_ids) {
            $this->tanggal = _get_default_datetime_range(true);
            $this->cabang_ids = $cabang_ids;
            $this->customer_ids = $customer_ids ?: [];
            $this->produk_ids = $produk_ids ?: [];
            $this->prosesLihat();
        }
    }

    private function getData()
    {
        $validated = $this->validate([
            'cabang_ids' => [],
            'customer_ids' => ['nullable'],
            'produk_ids' => ['nullable'],
            'tanggal' => ['required'],
        ]);

        $data = $validated;

        $cabangIds = $this->cabang_ids ?: auth()->user()->getPermissionCabangIds();
        $cabangs = Cabang::whereIn('id', $cabangIds)->get();

        $customers = $validated['customer_ids'] ? Customer::find($validated['customer_ids']) : Customer::all();
        $customerIds = $validated['customer_ids'] ? $validated['customer_ids'] : $customers->pluck('id')->toArray();

        $produks = $validated['produk_ids'] ? Produk::find($validated['produk_ids']) : Produk::all();
        $produkIds = $validated['produk_ids'] ? $validated['produk_ids'] : $produks->pluck('id')->toArray();

        $data['isSemuaCustomer'] = $validated['customer_ids'] ? false : true;
        $data['customerIds'] = $customerIds;
        $data['customers'] = $customers;
        $data['isSemuaProduk'] = $validated['produk_ids'] ? false : true;
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
        return view('admin.laporan.penjualan.history-penjualan-produk.index', $this->data)->layout($this->layout);
    }
}
