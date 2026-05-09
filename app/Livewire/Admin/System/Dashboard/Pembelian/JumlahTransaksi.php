<?php

namespace App\Livewire\Admin\System\Dashboard\Pembelian;

use Livewire\Component;
use App\Models\Pembelian\ReturPembelian;
use App\Models\Pembelian\FakturPembelian;
use App\Utilities\QueryHelpers\QH_DateTime;

class JumlahTransaksi extends Component
{
    public $cabang_id;
    public $jumlah_pesanan;
    public $jumlah_penerimaan;
    public $jumlah_faktur;
    public $jumlah_retur;
    public $tanggal;

    public function mount()
    {
        $this->cabang_id = session()->get('cabang_id');
        $this->tanggal = _get_default_date_range();
    }

    public function render()
    {
        $tanggal = $this->tanggal;
        $this->jumlah_faktur = FakturPembelian::query()
            ->when($tanggal, function ($query) use ($tanggal) {
                return QH_DateTime::scopeDateRange($query, $tanggal, 'tanggal');
            })
            ->where('cabang_id', $this->cabang_id)
            ->count();

        $this->jumlah_retur = ReturPembelian::query()
            ->when($tanggal, function ($query) use ($tanggal) {
                return QH_DateTime::scopeDateRange($query, $tanggal, 'tanggal');
            })
            ->where('cabang_id', $this->cabang_id)
            ->count();

        return view('admin.system.dashboard.pembelian.jumlah-transaksi')
            ->layout('admin.components.layouts.app');
    }
}
