<?php

namespace App\Livewire\Admin\Master\JenisProduk;

use App\Imports\JenisProdukImport;
use App\Models\Master\JenisProduk;
use App\Traits\Livewire\WithImportForm;
use Livewire\Component;

class Import extends Component
{
    use WithImportForm;

    public $model = JenisProduk::class;
    public $menuTitle = 'Jenis Produk';
    protected $template_filename = 'template_jenis_produk';
    protected $template_view = 'admin.master.jenis-produk.import-template';
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
        $import = new JenisProdukImport($this->cabang_id);
        $import->import($validated['file']);
        return $import->count;
    }

    public function render()
    {
        return view('admin.master.jenis-produk.import')->layout($this->layout);
    }
}
