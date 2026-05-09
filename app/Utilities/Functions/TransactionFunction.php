<?php

namespace App\Utilities\Functions;

use Carbon\Carbon;
use App\Models\Master\Customer;
use App\Models\Master\Supplier;
use App\Models\System\MutasiTransaksi;
use App\Utilities\Constants\Const_Umum;
use Illuminate\Database\Eloquent\Model;
use App\Utilities\Constants\Const_Status;
use App\Models\Utang\PembayaranUtangDetail;
use App\Models\Utang\PembayaranUtangPiutang;
use App\Models\Piutang\PenerimaanPiutangUtang;
use App\Models\Piutang\PenerimaanPiutangDetail;

class TransactionFunction
{
    public static function getVendorSaldo($cabang_id, Model $vendor, $jenis, ?Carbon $tanggal = null, $isTanggalInclusive = true)
    {
        $total = MutasiTransaksi::query()
            ->where('cabang_id', $cabang_id)
            ->where('vendor_type', $vendor::class)
            ->where('vendor_id', $vendor->id)
            ->where('jenis', $jenis)
            ->where('status', Const_Status::AKTIF)
            ->when($tanggal, function ($query) use ($tanggal, $isTanggalInclusive) {
                return $query->where('tanggal', $isTanggalInclusive ? '<=' : '<', $tanggal);
            })
            ->sum('jumlah');

        return _round($total);
    }

    public static function getSisaUtang($mutasi_transaksi_id, ?Carbon $tanggal = null, $isTanggalInclusive = false)
    {
        $total = PembayaranUtangDetail::query()
            ->where('mutasi_transaksi_id', $mutasi_transaksi_id)
            ->when($tanggal, function ($query) use ($tanggal, $isTanggalInclusive) {
                return $query->whereRelation('pembayaranUtang', 'tanggal', $isTanggalInclusive ? '<=' : '<', $tanggal);
            })
            ->sum('nominal');

        $total += PenerimaanPiutangUtang::query()
            ->where('mutasi_transaksi_id', $mutasi_transaksi_id)
            ->when($tanggal, function ($query) use ($tanggal, $isTanggalInclusive) {
                return $query->whereRelation('penerimaanPiutang', 'tanggal', $isTanggalInclusive ? '<=' : '<', $tanggal);
            })
            ->sum('nominal');

        return _round($total);
    }

    public static function getSisaPiutang($mutasi_transaksi_id, ?Carbon $tanggal = null, $isTanggalInclusive = false)
    {
        $total = PembayaranUtangPiutang::query()
            ->where('mutasi_transaksi_id', $mutasi_transaksi_id)
            ->when($tanggal, function ($query) use ($tanggal, $isTanggalInclusive) {
                return $query->whereRelation('pembayaranUtang', 'tanggal', $isTanggalInclusive ? '<=' : '<', $tanggal);
            })
            ->sum('nominal');

        $total += PenerimaanPiutangDetail::query()
            ->where('mutasi_transaksi_id', $mutasi_transaksi_id)
            ->when($tanggal, function ($query) use ($tanggal, $isTanggalInclusive) {
                return $query->whereRelation('penerimaanPiutang', 'tanggal', $isTanggalInclusive ? '<=' : '<', $tanggal);
            })
            ->sum('nominal');

        return _round($total);
    }

    public static function getPiutangCustomer($customer_id)
    {
        $total = MutasiTransaksi::query()
            ->where('vendor_type', Customer::class)
            ->where('vendor_id', $customer_id)
            ->where('jenis', Const_Umum::JENIS_MUTASI_TRANSAKSI_PIUTANG)
            ->where('status', Const_Status::AKTIF)
            ->sum('jumlah');

        return _round($total);
    }

    public static function getUtangSupplier($supplier_id)
    {
        $total = MutasiTransaksi::query()
            ->where('vendor_type', Supplier::class)
            ->where('vendor_id', $supplier_id)
            ->where('jenis', Const_Umum::JENIS_MUTASI_TRANSAKSI_UTANG)
            ->where('status', Const_Status::AKTIF)
            ->sum('jumlah');

        return _round($total);
    }
}
