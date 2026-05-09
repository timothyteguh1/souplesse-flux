<?php

namespace App\Services\Penjualan;

use App\Models\Penjualan\FakturPenjualanDetail;
use App\Models\Penjualan\ReturPenjualan;
use App\Models\Penjualan\ReturPenjualanDetail;
use App\Models\System\MutasiStok;
use App\Exceptions\GeneralException;
use App\Services\System\MutasiStokService;
use App\Utilities\Constants\Const_Umum;

class ReturPenjualanDetailService
{
    public static function create(ReturPenjualan $obj, array $data = []): ReturPenjualanDetail
    {
        $fakturPenjualanDetail = FakturPenjualanDetail::find($data['faktur_penjualan_detail_id']);
        $jumlahReturPenjualanDetails = ReturPenjualanDetail::where('faktur_penjualan_detail_id', $data['faktur_penjualan_detail_id'])->sum('jumlah');

        if ($data['jumlah'] > $fakturPenjualanDetail->jumlah - $jumlahReturPenjualanDetails) {
            throw new GeneralException("Jumlah retur " . $fakturPenjualanDetail->produk->nama . " melebihi jumlah pada faktur penjualan. Maksimal " . _number($fakturPenjualanDetail->jumlah - $jumlahReturPenjualanDetails));
        }

        $mutasiStok = MutasiStok::where('reference_id', $fakturPenjualanDetail->id)->first();

        $detail = $obj->details()->create($data);
        MutasiStokService::increase(
            $obj->tanggal,
            $obj->cabang_id,
            ReturPenjualanDetail::class,
            $detail->id,
            ReturPenjualan::class,
            $obj->id,
            Const_Umum::JENIS_TRANSAKSI_RETUR_PENJUALAN,
            $obj->gudang_id,
            $detail->produk_id,
            $detail->satuan_id,
            _date_format_db($mutasiStok->expired_date),
            // null,
            null,
            // $mutasiStok->no_batch,
            $detail->jumlah,
            $detail->dpp_satuan,
            'Retur Penjualan: [' . $obj->kode . ']',
        );

        return $detail;
    }

    public static function update(ReturPenjualanDetail $objDetail, array $data = []): bool
    {
        $fakturPenjualanDetail = FakturPenjualanDetail::find($data['faktur_penjualan_detail_id']);
        $jumlahReturPenjualanDetails = ReturPenjualanDetail::where('faktur_penjualan_detail_id', $data['faktur_penjualan_detail_id'])->whereNot('faktur_penjualan_detail_id', $objDetail->faktur_penjualan_detail_id)->sum('jumlah');
        if ($data['jumlah'] > $fakturPenjualanDetail->jumlah - $jumlahReturPenjualanDetails) {
            throw new GeneralException("Jumlah retur " . $fakturPenjualanDetail->produk->nama . " melebihi jumlah pada faktur penjualan. Maksimal " . _number($fakturPenjualanDetail->jumlah - $jumlahReturPenjualanDetails));
        }

        $mutasiStok = MutasiStok::where('reference_id', $data['id'])->first();
        MutasiStokService::destroy($objDetail->mutasiStok);
        $objDetail->update($data);
        $objDetail->refresh();

        MutasiStokService::increase(
            $objDetail->header->tanggal,
            $objDetail->header->cabang_id,
            ReturPenjualanDetail::class,
            $objDetail->id,
            ReturPenjualan::class,
            $objDetail->header->id,
            Const_Umum::JENIS_TRANSAKSI_RETUR_PENJUALAN,
            $objDetail->header->gudang_id,
            $objDetail->produk_id,
            $objDetail->satuan_id,
            _date_format_db($mutasiStok->expired_date),
            // null,
            null,
            // $mutasiStok->no_batch,
            $objDetail->jumlah,
            $objDetail->dpp_satuan,
            'Retur Penjualan: [' . $objDetail->returPenjualan->kode . ']',
        );

        return true;
    }

    public static function destroy(ReturPenjualanDetail $objDetail): bool
    {
        $objDetail->loadMissing('mutasiStok');
        MutasiStokService::destroy($objDetail->mutasiStok);

        return $objDetail->delete();
    }
}
