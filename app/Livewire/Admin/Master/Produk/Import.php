<?php

namespace App\Livewire\Admin\Master\Produk;

use App\Imports\ProdukImport;
use App\Models\Master\Produk;
use App\Traits\Livewire\WithImportForm;
use Livewire\Component;

class Import extends Component
{
    use WithImportForm;

    public $model = Produk::class;
    public $menuTitle = 'Produk';
    protected $template_filename = 'template_produk';
    protected $template_view = 'admin.master.produk.import-template';
    public $cabang_id;
    public $file;

    protected function rules(): array
    {
        return [
            'cabang_id' => ['required'],
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ];
    }

    public function mount()
    {
        $this->checkPermissionCreateGate();
        $this->cabang_id = session()->get('cabang_id');
    }

    public function submit($validated)
    {
        $import = new ProdukImport($this->cabang_id);
        $import->import($validated['file']);
        return $import->count;
    }

    public function render()
    {
        return view('admin.master.produk.import')->layout($this->layout);
    }
}
