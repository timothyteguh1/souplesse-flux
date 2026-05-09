<?php

namespace App\Livewire\Admin\Laporan\Penjualan\PesananPenjualanPerSales;

use Livewire\Component;
use App\Models\Master\Cabang;
use Illuminate\Http\Response;
use App\Models\Master\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Traits\Livewire\WithReportForm;
use App\Utilities\Constants\Const_Umum;

class Index extends Component
{
    use WithReportForm;

    public $menuTitle = 'Pesanan Penjualan Per Sales';
    protected $export_filename = 'laporan_pesanan_penjualan_per_sales';
    protected $export_orientation = Const_Umum::ORIENTATION_LANDSCAPE;
    protected $export_view = 'admin.laporan.penjualan.pesanan-penjualan-per-sales.index-export';
    protected $export_paper = Const_Umum::PAPER_A4;
    public $customer_ids = [];
    public $tanggal;
    public $cabang_ids;
    public $user_ids = [];

    public function mount()
    {
        abort_if(Gate::none(['admin.laporan.penjualan.pesanan-penjualan-per-sales']), Response::HTTP_FORBIDDEN);
        $this->tanggal = _get_default_date_range();
    }

    private function getData()
    {
        $validated = $this->validate([
            'cabang_ids' => [],
            'customer_ids' => ['nullable'],
            'user_ids' => ['nullable'],
            'tanggal' => ['required'],
        ]);

        $data = $validated;

        $cabangIds = $this->cabang_ids ?: auth()->user()->getPermissionCabangIds();
        $cabangs = Cabang::whereIn('id', $cabangIds)->get();
        $data['cabangIds'] = $cabangIds;
        $data['cabangs'] = $cabangs;

        $customers = $validated['customer_ids'] ? Customer::find($validated['customer_ids']) : Customer::all();
        $customerIds = $validated['customer_ids'] ? $validated['customer_ids'] : $customers->pluck('id')->toArray();
        $data['isSemuaCustomer'] = $validated['customer_ids'] ? false : true;
        $data['customerIds'] = $customerIds;
        $data['customers'] = $customers;

        $users = $validated['user_ids'] ? User::find($validated['user_ids']) : User::all();
        $userIds = $validated['user_ids'] ? $validated['user_ids'] : $users->pluck('id')->toArray();
        $data['isSemuaUser'] = $validated['user_ids'] ? false : true;
        $data['userIds'] = $userIds;
        $data['users'] = $users;

        [$tanggalAwal, $tanggalAkhir] = _datetime_carbon_split_filter_date($validated['tanggal']);
        $data['tanggalAwal'] = $tanggalAwal;
        $data['tanggalAkhir'] = $tanggalAkhir;

        return $data;
    }

    public function render()
    {
        return view('admin.laporan.penjualan.pesanan-penjualan-per-sales.index', $this->data)->layout($this->layout);
    }
}
