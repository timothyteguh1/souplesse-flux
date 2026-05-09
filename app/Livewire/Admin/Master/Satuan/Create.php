<?php

namespace App\Livewire\Admin\Master\Satuan;

use Livewire\Component;
use App\Models\Master\Satuan;
use Illuminate\Validation\Rule;
use App\Services\Master\SatuanService;
use App\Traits\Livewire\WithCreateForm;

class Create extends Component
{
    use WithCreateForm;

    public $model = Satuan::class;
    public $menuTitle = 'Satuan';
    public $cabang_id;
    public $kode;
    public $nama;

    protected function rules(): array
    {
        return [
            'cabang_id' => ['required'],
            'kode' => [],
            'nama' => [
                'string', 'required',
                Rule::unique(Satuan::getTableName(), 'nama')
                    ->where('cabang_id', $this->cabang_id),
            ],
        ];
    }

    public function mount()
    {
        $this->checkPermissionCreateGate();
        $this->cabang_id = session()->get('cabang_id');
    }

    public function submit($validated)
    {
        return SatuanService::create($validated);
    }

    public function render()
    {
        return view('admin.master.satuan.create')->layout($this->layout);
    }
}
