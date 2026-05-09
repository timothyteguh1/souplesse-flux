<?php

namespace App\Livewire\Admin\Master\Gudang;

use App\Imports\GudangImport;
use App\Models\Master\Gudang;
use App\Traits\Livewire\WithImportForm;
use Livewire\Component;

class Import extends Component
{
    use WithImportForm;

    public $model = Gudang::class;
    public $menuTitle = 'Gudang';
    protected $template_filename = 'template_gudang';
    protected $template_view = 'admin.master.gudang.import-template';
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
        $import = new GudangImport($this->cabang_id);
        $import->import($validated['file']);
        return $import->count;
    }

    public function render()
    {
        return view('admin.master.gudang.import')->layout($this->layout);
    }
}
