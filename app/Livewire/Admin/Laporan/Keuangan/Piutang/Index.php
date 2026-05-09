<?php

namespace App\Livewire\Admin\Laporan\Keuangan\Piutang;

use App\Models\Master\Customer;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\Master\Cabang;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use App\Traits\Livewire\WithReportForm;
use App\Utilities\Constants\Const_Date;
use App\Utilities\Constants\Const_Umum;

class Index extends Component
{
    use WithReportForm;

    public $menuTitle = 'Piutang';
    protected $export_filename = 'laporan_piutang';
    protected $export_orientation = Const_Umum::ORIENTATION_LANDSCAPE;
    protected $export_view = 'admin.laporan.keuangan.piutang.index-export';
    protected $export_paper = Const_Umum::PAPER_A4;
    public $tanggal;
    public $customer_id;
    public $cabang_ids;

    public function mount()
    {
        abort_if(Gate::none(['admin.laporan.keuangan.piutang']), Response::HTTP_FORBIDDEN);
        $this->tanggal = _get_default_date();
    }

    private function getData()
    {
        $validated = $this->validate([
            'cabang_ids' => [],
            'tanggal' => ['required'],
            'customer_id' => [],
        ]);

        $data = $validated;

        $cabangIds = $this->cabang_ids ?: auth()->user()->getPermissionCabangIds();
        $cabangs = Cabang::whereIn('id', $cabangIds)->get();
        $customer = Customer::find($validated['customer_id']);

        $data['cabangIds'] = $cabangIds;
        $data['cabangs'] = $cabangs;
        $data['customer'] = $customer;

        $data['tanggalCarbon'] = Carbon::createFromFormat(Const_Date::DATE_FORMAT_OUTPUT, $validated['tanggal'])->endOfDay();

        return $data;
    }

    public function render()
    {
        return view('admin.laporan.keuangan.piutang.index', $this->data)->layout($this->layout);
    }
}
