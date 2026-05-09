<?php

namespace App\Livewire\Admin\Master\Satuan;

use App\Imports\SatuanImport;
use App\Models\Master\Satuan;
use App\Traits\Livewire\WithImportForm;
use Livewire\Component;

class Import extends Component
{
    use WithImportForm;

    public $model = Satuan::class;
    public $menuTitle = 'Satuan';
    protected $template_filename = 'template_satuan';
    protected $template_view = 'admin.master.satuan.import-template';
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
        $import = new SatuanImport($this->cabang_id);
        $import->import($validated['file']);
        return $import->count;
    }

    public function render()
    {
        return view('admin.master.satuan.import')->layout($this->layout);
    }
}
