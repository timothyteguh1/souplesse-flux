<?php

namespace App\Livewire\Admin\Master\Gudang;

use Livewire\Component;
use App\Models\Master\Gudang;
use Illuminate\Validation\Rule;
use App\Services\Master\GudangService;
use App\Traits\Livewire\WithCreateForm;

class Create extends Component
{
    use WithCreateForm;

    public $model = Gudang::class;
    public $menuTitle = 'Gudang';
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
                Rule::unique(Gudang::getTableName(), 'nama')
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
        return GudangService::create($validated);
    }

    public function render()
    {
        return view('admin.master.gudang.create')->layout($this->layout);
    }
}
