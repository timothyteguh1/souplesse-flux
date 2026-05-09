<?php

namespace App\Livewire\Admin\System\Setting;

use App\Services\Master\PerusahaanService;
use App\Utilities\Constants\Const_Perusahaan;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Perusahaan extends Component
{
    use WithFileUploads;

    public $menuTitle = 'Perusahaan';
    public \App\Models\Master\Perusahaan $obj;
    public $nama;
    public $alamat;
    public $kota;
    public $telp;
    public $email;
    public $logo;
    public $uploaded_logo = [];

    protected function rules(): array
    {
        return [
            'nama' => ['required'],
            'alamat' => ['required'],
            'kota' => ['required'],
            'telp' => ['required'],
            'email' => ['required'],
            'logo' => ['nullable', 'image', 'max:10000'],
            'uploaded_logo' => [],
        ];
    }

    public function mount()
    {
        abort_if(Gate::none(['admin.system.setting.perusahaan']), Response::HTTP_FORBIDDEN);

        $this->obj = \App\Models\Master\Perusahaan::findOrFail(Const_Perusahaan::PT);
        $this->nama = $this->obj->nama;
        $this->alamat = $this->obj->alamat;
        $this->kota = $this->obj->kota;
        $this->telp = $this->obj->telp;
        $this->email = $this->obj->email;
        $this->logo = $this->obj->logo;

        if ($this->logo && Storage::disk('public')->exists($this->logo)) {
            $logo = Storage::disk('public')->url($this->logo);
            $this->uploaded_logo[] = [
                'source' => $logo,
                'options' => [
                    'type' => 'local',
                ],
            ];
        }
    }

    public function submit()
    {
        $validated = $this->validate();

        try {
            PerusahaanService::update($this->obj, $validated);
            session()->flash('flash_success', $this->menuTitle . ' berhasil diupdate.');

            return to_route('admin.system.setting.perusahaan');
        } catch (Exception $exception) {
            $this->addError('flash_danger', _get_exception_message($exception));

            return false;
        }
    }

    public function render()
    {
        return view('admin.system.setting.perusahaan')
            ->layout('admin.components.layouts.app');
    }
}
