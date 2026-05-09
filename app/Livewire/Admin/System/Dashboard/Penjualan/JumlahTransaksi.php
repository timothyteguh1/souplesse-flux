<?php

namespace App\Livewire\Admin\System\Dashboard\Penjualan;

use Livewire\Component;
use App\Models\Penjualan\ReturPenjualan;
use App\Models\Penjualan\FakturPenjualan;
use App\Models\Penjualan\PesananPenjualan;
use App\Utilities\QueryHelpers\QH_DateTime;
use App\Models\Penjualan\SuratJalan;

class JumlahTransaksi extends Component
{
    public $cabang_id;
    public $jumlah_pesanan;
    public $jumlah_surat_jalan;
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
        $this->jumlah_pesanan = PesananPenjualan::query()
            ->when($tanggal, function ($query) use ($tanggal) {
                return QH_DateTime::scopeDateRange($query, $tanggal, 'tanggal');
            })
            ->where('cabang_id', $this->cabang_id)
            ->count();

        $this->jumlah_surat_jalan = SuratJalan::query()
            ->when($tanggal, function ($query) use ($tanggal) {
                return QH_DateTime::scopeDateRange($query, $tanggal, 'tanggal');
            })
            ->where('cabang_id', $this->cabang_id)
            ->count();

        $this->jumlah_faktur = FakturPenjualan::query()
            ->when($tanggal, function ($query) use ($tanggal) {
                return QH_DateTime::scopeDateRange($query, $tanggal, 'tanggal');
            })
            ->where('cabang_id', $this->cabang_id)
            ->count();

        $this->jumlah_retur = ReturPenjualan::query()
            ->when($tanggal, function ($query) use ($tanggal) {
                return QH_DateTime::scopeDateRange($query, $tanggal, 'tanggal');
            })
            ->where('cabang_id', $this->cabang_id)
            ->count();

        return view('admin.system.dashboard.penjualan.jumlah-transaksi')
            ->layout('admin.components.layouts.app');
    }
}
