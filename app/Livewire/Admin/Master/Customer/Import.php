<?php

namespace App\Livewire\Admin\Master\Customer;

use App\Imports\CustomerImport;
use App\Models\Master\Customer;
use App\Traits\Livewire\WithImportForm;
use Livewire\Component;

class Import extends Component
{
    use WithImportForm;

    public $model = Customer::class;
    public $menuTitle = 'Customer';
    protected $template_filename = 'template_customer';
    protected $template_view = 'admin.master.customer.import-template';
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
        $import = new CustomerImport($this->cabang_id);
        $import->import($validated['file']);
        return $import->count;
    }

    public function render()
    {
        return view('admin.master.customer.import')->layout($this->layout);
    }
}
