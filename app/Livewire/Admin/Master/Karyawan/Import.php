<?php

namespace App\Livewire\Admin\Master\Karyawan;

use App\Imports\KaryawanImport;
use App\Models\Master\Karyawan;
use App\Traits\Livewire\WithImportForm;
use Livewire\Component;

class Import extends Component
{
    use WithImportForm;

    public $model = Karyawan::class;
    public $menuTitle = 'Salesman';
    protected $template_filename = 'template_salesman';
    protected $template_view = 'admin.master.karyawan.import-template';
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
        $import = new KaryawanImport($this->cabang_id);
        $import->import($validated['file']);
        return $import->count;
    }

    public function render()
    {
        return view('admin.master.karyawan.import')->layout($this->layout);
    }
}
