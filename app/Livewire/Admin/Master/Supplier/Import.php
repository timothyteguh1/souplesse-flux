<?php

namespace App\Livewire\Admin\Master\Supplier;

use App\Imports\SupplierImport;
use App\Models\Master\Supplier;
use App\Traits\Livewire\WithImportForm;
use Livewire\Component;

class Import extends Component
{
    use WithImportForm;

    public $model = Supplier::class;
    public $menuTitle = 'Supplier';
    protected $template_filename = 'template_supplier';
    protected $template_view = 'admin.master.supplier.import-template';
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
        $import = new SupplierImport($this->cabang_id);
        $import->import($validated['file']);
        return $import->count;
    }

    public function render()
    {
        return view('admin.master.supplier.import')->layout($this->layout);
    }
}
