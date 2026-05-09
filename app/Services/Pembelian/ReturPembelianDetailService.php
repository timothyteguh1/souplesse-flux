<?php

namespace App\Services\Pembelian;

use App\Models\Pembelian\FakturPembelianDetail;
use App\Models\Pembelian\ReturPembelian;
use App\Models\Pembelian\ReturPembelianDetail;
use App\Exceptions\GeneralException;
use App\Services\System\MutasiStokService;
use App\Utilities\Constants\Const_Umum;

class ReturPembelianDetailService
{
    public static function create(ReturPembelian $obj, array $data = []): ReturPembelianDetail
    {
        $fakturPembelianDetail = FakturPembelianDetail::find($data['faktur_pembelian_detail_id']);
        $jumlahReturPembelianDetails = ReturPembelianDetail::where('faktur_pembelian_detail_id', $data['faktur_pembelian_detail_id'])->sum('jumlah');

        if ($data['jumlah'] > $fakturPembelianDetail->jumlah - $jumlahReturPembelianDetails) {
            throw new GeneralException("Jumlah retur " . $fakturPembelianDetail->produk->nama . " melebihi jumlah pada faktur pembelian. Maksimal " . _number($fakturPembelianDetail->jumlah - $jumlahReturPembelianDetails));
        }

        $detail = $obj->details()->create($data);
        MutasiStokService::decrease(
            $obj->tanggal,
            $obj->cabang_id,
            ReturPembelianDetail::class,
            $detail->id,
            ReturPembelian::class,
            $obj->id,
            Const_Umum::JENIS_TRANSAKSI_RETUR_PEMBELIAN,
            $obj->gudang_id,
            $detail->produk_id,
            $detail->satuan_id,
            _date_format_db($fakturPembelianDetail->expired_date),
            $fakturPembelianDetail->no_batch,
            -$detail->jumlah,
            'Retur Pembelian: [' . $obj->kode . ']',
            $detail->dpp_satuan,
        );

        return $detail;
    }

    public static function update(ReturPembelianDetail $objDetail, array $data = []): bool
    {
        MutasiStokService::destroy($objDetail->mutasiStok);
        $fakturPembelianDetail = FakturPembelianDetail::find($data['faktur_pembelian_detail_id']);
        $jumlahReturPembelianDetails = ReturPembelianDetail::where('faktur_pembelian_detail_id', $data['faktur_pembelian_detail_id'])->whereNot('faktur_pembelian_detail_id', $objDetail->faktur_pembelian_detail_id)->sum('jumlah');
        if ($data['jumlah'] > $fakturPembelianDetail->jumlah - $jumlahReturPembelianDetails) {
            throw new GeneralException("Jumlah retur " . $fakturPembelianDetail->produk->nama . " melebihi jumlah pada faktur pembelian. Maksimal " . _number($fakturPembelianDetail->jumlah - $jumlahReturPembelianDetails));
        }

        $objDetail->update($data);
        $objDetail->refresh();

        MutasiStokService::decrease(
            $objDetail->header->tanggal,
            $objDetail->header->cabang_id,
            ReturPembelianDetail::class,
            $objDetail->id,
            ReturPembelian::class,
            $objDetail->header->id,
            Const_Umum::JENIS_TRANSAKSI_RETUR_PEMBELIAN,
            $objDetail->header->gudang_id,
            $objDetail->produk_id,
            $objDetail->satuan_id,
            _date_format_db($fakturPembelianDetail->expired_date),
            $fakturPembelianDetail->no_batch,
            -$objDetail->jumlah,
            'Retur Pembelian: [' . $objDetail->returPembelian->kode . ']',
            $objDetail->dpp_satuan,
        );

        return true;
    }

    public static function destroy(ReturPembelianDetail $objDetail): bool
    {
        $objDetail->loadMissing('mutasiStok');
        MutasiStokService::destroy($objDetail->mutasiStok);

        return $objDetail->delete();
    }
}
