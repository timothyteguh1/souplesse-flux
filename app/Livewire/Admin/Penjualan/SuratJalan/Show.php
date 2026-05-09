<?php

namespace App\Livewire\Admin\Penjualan\SuratJalan;

use Livewire\Component;
use App\Models\Penjualan\SuratJalan;
use App\Traits\Livewire\WithShowForm;
use App\Services\Penjualan\SuratJalanService;

class Show extends Component
{
    use WithShowForm;

    public $model = SuratJalan::class;
    public $menuTitle = 'Surat Jalan';
    public SuratJalan $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function print()
    {
        $view = view('admin.penjualan.surat-jalan.print', [
            'obj' => $this->obj,
        ]);

        $this->cetakRawHtml($view->render());
    }

    public function approve($id)
    {
        $penerimaanPenjualan = SuratJalan::find($id);
        SuratJalanService::updateStatusTerima($penerimaanPenjualan);
        $this->obj = $this->obj->refresh();

        session()->flash('flash_success', 'Surat Jalan telah di-Approve.');
    }

    public function tolak($id)
    {
        $penerimaanPenjualan = SuratJalan::find($id);
        SuratJalanService::updateStatusTolak($penerimaanPenjualan);
        $this->obj = $this->obj->refresh();

        session()->flash('flash_success', 'Surat Jalan telah di-Tolak.');
    }

    public function tutup($id)
    {
        $penerimaanPenjualan = SuratJalan::find($id);
        SuratJalanService::updateStatusTutup($penerimaanPenjualan);
        $this->obj = $this->obj->refresh();

        session()->flash('flash_success', 'Surat Jalan telah di-Tutup.');
    }

    public function processDelete($id)
    {
        $obj = SuratJalan::findOrFail($id);
        SuratJalanService::destroy($obj);
    }

    public function render()
    {
        return view('admin.penjualan.surat-jalan.show')
            ->layout($this->layout);
    }
}
