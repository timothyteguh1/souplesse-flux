<?php

namespace App\Livewire\Admin\Penjualan\FakturPenjualanViaSo;

use App\Imports\Penjualan\FakturPenjualanImport;
use App\Models\Penjualan\FakturPenjualan;
use App\Traits\Livewire\WithImportForm;
use App\Utilities\SelectHelpers\Master\SH_Ekspedisi;
use App\Utilities\SelectHelpers\Master\SH_Gudang;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Import extends Component
{
    use WithImportForm;

    public $model = FakturPenjualan::class;
    public $menuTitle = 'Faktur Penjualan';
    protected $template_filename = 'template_faktur_penjualan';
    protected $template_view = 'admin.penjualan.faktur-penjualan-via-so.import-template';
    public $cabang_id;
    public $ekspedisi_id;
    public $gudang_id;
    public $file;

    protected function rules(): array
    {
        return [
            'cabang_id' => ['required'],
            'ekspedisi_id' => ['required'],
            'gudang_id' => ['required'],
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ];
    }

    public function mount()
    {
        $this->checkPermissionCreateGate();
        $this->cabang_id = session()->get('cabang_id');
    }

    #[Computed(persist: true)]
    public function optionsEkspedisiId()
    {
        return SH_Ekspedisi::active();
    }

    #[Computed(persist: true)]
    public function optionsGudangId()
    {
        return SH_Gudang::user();
    }

    public function submit($validated)
    {
        $import = new FakturPenjualanImport($this->cabang_id, $this->ekspedisi_id, $this->gudang_id);
        $import->import($validated['file']);
        return $import->count;
    }

    public function render()
    {
        return view('admin.penjualan.faktur-penjualan-via-so.import')->layout($this->layout);
    }
}
