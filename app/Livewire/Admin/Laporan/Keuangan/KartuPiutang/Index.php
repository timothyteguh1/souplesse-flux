<?php

namespace App\Livewire\Admin\Laporan\Keuangan\KartuPiutang;

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

    public $menuTitle = 'Kartu Piutang';
    protected $export_filename = 'laporan_kartu_piutang';
    protected $export_orientation = Const_Umum::ORIENTATION_LANDSCAPE;
    protected $export_view = 'admin.laporan.keuangan.kartu-piutang.index-export';
    protected $export_paper = Const_Umum::PAPER_A4;
    public $customer_id;
    public $tanggal;
    public $cabang_ids;

    public function mount()
    {
        abort_if(Gate::none(['admin.laporan.keuangan.kartu-piutang']), Response::HTTP_FORBIDDEN);
        $this->tanggal = _get_default_date_range();
    }

    private function getData()
    {
        $validated = $this->validate([
            'cabang_ids' => [],
            'tanggal' => ['required'],
            'customer_id' => ['required'],
        ]);

        $data = $validated;

        $customer = Customer::find($validated['customer_id']);
        $cabangIds = $this->cabang_ids ?: auth()->user()->getPermissionCabangIds();
        $cabangs = Cabang::whereIn('id', $cabangIds)->get();

        $data['customer'] = $customer;
        $data['cabangIds'] = $cabangIds;
        $data['cabangs'] = $cabangs;

        [$tanggalAwal, $tanggalAkhir] = _datetime_carbon_split_filter_date($validated['tanggal']);
        $data['tanggalAwal'] = $tanggalAwal;
        $data['tanggalAkhir'] = $tanggalAkhir;

        return $data;
    }

    public function render()
    {
        return view('admin.laporan.keuangan.kartu-piutang.index', $this->data)->layout($this->layout);
    }
}
