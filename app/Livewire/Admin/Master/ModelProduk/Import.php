<?php

namespace App\Livewire\Admin\Master\ModelProduk;

use App\Imports\ModelProdukImport;
use App\Models\Master\ModelProduk;
use App\Traits\Livewire\WithImportForm;
use Livewire\Component;

class Import extends Component
{
    use WithImportForm;

    public $model = ModelProduk::class;
    public $menuTitle = 'Model Produk';
    protected $template_filename = 'template_model_produk';
    protected $template_view = 'admin.master.model-produk.import-template';
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
        $import = new ModelProdukImport($this->cabang_id);
        $import->import($validated['file']);
        return $import->count;
    }

    public function render()
    {
        return view('admin.master.model-produk.import')->layout($this->layout);
    }
}
