<?php

namespace App\Livewire\Admin\System\Dashboard\Keuangan;

use App\Models\Keuangan\KasBon;
use App\Models\Keuangan\KasKeluar;
use Livewire\Component;
use App\Models\Keuangan\KasMasuk;
use App\Models\Keuangan\TransferKas;
use App\Utilities\QueryHelpers\QH_DateTime;

class JumlahTransaksi extends Component
{
    public $cabang_id;
    public $jumlah_kas_masuk;
    public $jumlah_kas_keluar;
    public $jumlah_transfer_kas;
    public $jumlah_kas_bon;
    public $tanggal;

    public function mount()
    {
        $this->cabang_id = session()->get('cabang_id');
        $this->tanggal = _get_default_date_range();
    }

    public function render()
    {
        $tanggal = $this->tanggal;

        $this->jumlah_kas_masuk = KasMasuk::query()
            ->when($tanggal, function ($query) use ($tanggal) {
                return QH_DateTime::scopeDateRange($query, $tanggal, 'tanggal');
            })
            ->where('cabang_id', $this->cabang_id)
            ->count();

        $this->jumlah_kas_keluar = KasKeluar::query()
            ->when($tanggal, function ($query) use ($tanggal) {
                return QH_DateTime::scopeDateRange($query, $tanggal, 'tanggal');
            })
            ->where('cabang_id', $this->cabang_id)
            ->count();

        $this->jumlah_transfer_kas = TransferKas::query()
            ->when($tanggal, function ($query) use ($tanggal) {
                return QH_DateTime::scopeDateRange($query, $tanggal, 'tanggal');
            })
            ->where('cabang_id', $this->cabang_id)
            ->count();

        $this->jumlah_kas_bon = KasBon::query()
            ->when($tanggal, function ($query) use ($tanggal) {
                return QH_DateTime::scopeDateRange($query, $tanggal, 'tanggal');
            })
            ->where('cabang_id', $this->cabang_id)
            ->count();

        return view('admin.system.dashboard.keuangan.jumlah-transaksi')
            ->layout('admin.components.layouts.app');
    }
}
