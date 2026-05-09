<?php

namespace App\Livewire\Admin\Master\Satuan;

use Livewire\Component;
use App\Models\Master\Satuan;
use Illuminate\Validation\Rule;
use App\Traits\Livewire\WithEditForm;
use App\Services\Master\SatuanService;

class Edit extends Component
{
    use WithEditForm;

    public $model = Satuan::class;
    public $menuTitle = 'Satuan';
    public Satuan $obj;
    public $nama;
    public $status = 0;

    protected function rules(): array
    {
        return [
            'nama' => [
                'string', 'required',
                Rule::unique(Satuan::getTableName(), 'nama')
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
        SatuanService::update($this->obj, $validated);

        return $this->obj;
    }

    public function render()
    {
        return view('admin.master.satuan.edit')
            ->layout($this->layout);
    }
}
