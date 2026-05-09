<?php

namespace App\Livewire\Admin\Master\Promo;

use App\Models\Master\Promo;
use App\Services\Master\PromoService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use Livewire\Component;

class Index extends Component
{
    use WithIndexForm;

    public $model = Promo::class;
    public $menuTitle = 'Tier Diskon';
    protected $export_filename = 'tier_diskon';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.master.promo.index-export';
    public $keyword;
    public $status;
    public $cabang_ids;

    public function mount()
    {
        $this->checkPermissionIndexGate();
        $this->cabang_ids = session()->get('cabang_ids');
    }

    public function processDelete($id)
    {
        $obj = Promo::findOrFail($id);
        PromoService::destroy($obj);
    }

    private function getQuery()
    {
        return Promo::query()
            ->with(['cabang', 'produk'])
            ->whereIn('cabang_id', $this->cabang_ids)
            ->keywordSearch($this->keyword, ['kode', 'nama'])
            ->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            });
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.master.promo.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
