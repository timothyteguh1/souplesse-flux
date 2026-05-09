<?php

namespace App\Livewire\Admin\System\Setting;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class Cabang extends Component
{
    public $cabang;
    public $cabangs;
    public $cabang_ids = [];
    public $isCheckedAllCabang = false;
    public $selected_cabang_id;
    public $currentRouteName;
    public $currentRouteParams;

    public function mount()
    {
        $this->currentRouteName = Route::getCurrentRoute()->action['as'];
        $this->currentRouteParams = Route::getCurrentRoute()->parameters();

        $cabangIds = auth()->user()->getPermissionCabangIds();
        $this->cabangs = \App\Models\Master\Cabang::find($cabangIds);

        // cabang utama
        $id = session()->get('cabang_id');
        $this->selected_cabang_id = $id;
        $cabang = \App\Models\Master\Cabang::find($this->selected_cabang_id);
        $this->cabang = $cabang;

        // cabang filter
        $ids = session()->get('cabang_ids');
        $this->cabang_ids = [];
        foreach ($ids ?? [] as $item) {
            $this->cabang_ids[$item] = true;
        }
    }

    public function selectCabang($id)
    {
        $this->selected_cabang_id = $id;
        $cabang = \App\Models\Master\Cabang::find($this->selected_cabang_id);
        $this->cabang = $cabang;

        $this->cabang_ids[$id] = true;
    }

    public function toggleCheckAllCabang()
    {
        if ($this->isCheckedAllCabang) {
            foreach ($this->cabangs as $item) {
                $this->cabang_ids[$item['id']] = true;
            }
        } else {
            foreach ($this->cabangs as $item) {
                $this->cabang_ids[$item['id']] = false;
            }
        }
    }

    public function save()
    {
        $cabang_ids = [];
        foreach ($this->cabang_ids as $index => $item) {
            if ($item == true) {
                $cabang_ids[] = $index;
            }
        }

        // setting session cabang filter
        session()->put('cabang_ids', $cabang_ids);

        // setting session cabang utama
        session()->put('cabang_id', $this->cabang->id);
        session()->put('cabang_kode', $this->cabang->kode);
        session()->put('cabang_nama', $this->cabang->nama);

        // update auth sesion
        $pipe_segments = sprintf(
            '%s|%s|%s|%s',
            Auth::user()->getAuthIdentifier(),
            Auth::user()->getRememberToken(),
            Auth::user()->getAuthPassword(),
            collect($cabang_ids)->prepend($this->cabang->id)->implode(","),
        );

        Auth::getCookieJar()->queue(
            Auth::getCookieJar()->make(Auth::getRecallerName(), $pipe_segments, 60 * 24 * 365),
        );

        return to_route($this->currentRouteName, $this->currentRouteParams);
    }

    public function render()
    {
        return view('admin.system.setting.cabang');
    }
}
