<?php

namespace App\Livewire\Admin\Penjualan\PesananPenjualan;

use Exception;
use Livewire\Component;
use App\Models\Master\Promo;
use App\Traits\Livewire\WithModalForm;
use App\Utilities\Constants\Const_Status;

class ModalListPromo extends Component
{
    use WithModalForm;

    public $objs = [];
    public $promo_id;
    public $keyword;
    public $customer_ids;
    public $supplier_ids;
    public $produk_ids;
    protected $listeners = [
        'refreshInfo' => 'refreshInfo',
    ];

    protected function rules(): array
    {
        return [
            'promo_id' => ['required'],
        ];
    }

    public function refreshInfo($params)
    {
        $this->objs = Promo::where('status', Const_Status::AKTIF)
            ->keywordSearch($this->keyword, ['nama'])
            ->when($this->customer_ids, function ($query) {
                return $query->whereHas('promoCustomers', function ($q) {
                    $q->where('customer_id', $this->customer_ids);
                })->orWhereDoesntHave('promoCustomers');
            })
            ->when($this->supplier_ids, function ($query) {
                return $query->whereHas('promoSuppliers', function ($q) {
                    $q->where('supplier_id', $this->supplier_ids);
                })->orWhereDoesntHave('promoSuppliers');
            })
            ->when($this->produk_ids, function ($query) {
                return $query->whereHas('promoProduks', function ($q) {
                    $q->where('produk_id', $this->produk_ids);
                })->orWhereDoesntHave('promoProduks');
            })
            ->where('is_promo_grosir', false)
            ->get();

        $this->showModal();
    }

    public function submit($id)
    {
        $this->promo_id = $id;
        $validated = $this->validate();
        try {
            $this->dispatch('pilihPromoUpdated', $validated);
            $this->closeModal();
        } catch (Exception $exception) {
            $this->addError('flash_danger', _get_exception_message($exception));

            return false;
        }
    }

    public function render()
    {
        return view('admin.penjualan.faktur-penjualan.modal-list-promo');
    }
}
