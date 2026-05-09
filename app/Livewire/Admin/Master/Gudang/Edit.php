<?php

namespace App\Livewire\Admin\Master\Gudang;

use Livewire\Component;
use App\Models\Master\Gudang;
use Illuminate\Validation\Rule;
use App\Traits\Livewire\WithEditForm;
use App\Services\Master\GudangService;

class Edit extends Component
{
    use WithEditForm;

    public $model = Gudang::class;
    public $menuTitle = 'Gudang';
    public Gudang $obj;
    public $nama;
    public $status = 0;

    protected function rules(): array
    {
        return [
            'nama' => [
                'string', 'required',
                Rule::unique(Gudang::getTableName(), 'nama')
                    ->where('cabang_id', $this->obj->cabang_id)
                    ->ignore($this->obj->id),
            ],
            'status' => ['required'],
        ];
    }

    public function mount()
    {
        $this->checkPermissionEditGate();

        $this->nama = $this->obj->nama;
        $this->status = $this->obj->status;
    }

    public function submit($validated)
    {
        GudangService::update($this->obj, $validated);

        return $this->obj;
    }

    public function render()
    {
        return view('admin.master.gudang.edit')
            ->layout($this->layout);
    }
}
