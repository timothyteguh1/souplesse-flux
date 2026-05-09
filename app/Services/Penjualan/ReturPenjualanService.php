<?php

namespace App\Services\Penjualan;

use App\Models\Master\Customer;
use App\Exceptions\GeneralException;
use App\Models\System\MutasiTransaksi;
use App\Utilities\Constants\Const_Umum;
use App\Models\Penjualan\ReturPenjualan;
use App\Utilities\Constants\Const_Status;
use App\Models\Penjualan\ReturPenjualanDetail;
use App\Services\System\MutasiTransaksiService;
use App\Utilities\Functions\TransactionFunction;

class ReturPenjualanService
{
    public static function create(array $data = []): ReturPenjualan
    {
        if (!ReturPenjualan::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }

        $obj = ReturPenjualan::create($data);

        // detail
        foreach ($data['items'] as $item) {
            ReturPenjualanDetailService::create($obj, $item);
        }

        MutasiTransaksiService::increase(
            $obj->tanggal,
            $obj->cabang_id,
            Const_Umum::JENIS_MUTASI_TRANSAKSI_UTANG,
            Customer::class,
            $obj->customer_id,
            ReturPenjualan::class,
            $obj->id,
            ReturPenjualan::class,
            $obj->id,
            Const_Umum::JENIS_TRANSAKSI_RETUR_PENJUALAN,
            $obj->grandtotal,
            'Retur Penjualan: [' . $obj->kode . ']',
        );

        return $obj;
    }

    public static function update(ReturPenjualan $obj, array $data = []): bool
    {
        if (!$obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        MutasiTransaksiService::destroy($obj->mutasiTransaksi);

        $obj->update($data);

        self::updateDetail($obj, $data['items']);

        //ubah status baru
        $obj->refresh();

        MutasiTransaksiService::increase(
            $obj->tanggal,
            $obj->cabang_id,
            Const_Umum::JENIS_MUTASI_TRANSAKSI_UTANG,
            Customer::class,
            $obj->customer_id,
            ReturPenjualan::class,
            $obj->id,
            ReturPenjualan::class,
            $obj->id,
            Const_Umum::JENIS_TRANSAKSI_RETUR_PENJUALAN,
            $obj->grandtotal,
            'Retur Penjualan: [' . $obj->kode . ']',
        );

        return true;
    }

    public static function updateStatus(MutasiTransaksi $mutasiTransaksi): bool
    {
        $obj = ReturPenjualan::find($mutasiTransaksi->reference_id);
        $terbayar = TransactionFunction::getSisaUtang($mutasiTransaksi->id);

        if ($obj->grandtotal == $terbayar && $obj->status != Const_Status::RETUR_PENJUALAN_LUNAS) {
            $obj->status = Const_Status::RETUR_PENJUALAN_LUNAS;

            return $obj->save();
        } elseif ($obj->grandtotal != $terbayar && $obj->status == Const_Status::RETUR_PENJUALAN_LUNAS) {
            $obj->status = Const_Status::RETUR_PENJUALAN_BELUM_LUNAS;

            return $obj->save();
        }

        return true;
    }

    public static function updateDetail(ReturPenjualan $obj, array $data = []): bool
    {
        $collects = collect([
            $data,
        ]);

        $updatedIds = $collects->pluck('*.id')->flatten()->whereNotNull();

        $dataLamas = $obj->details()->whereNotIn('id', $updatedIds)->get();

        foreach ($dataLamas as $dataLama) {
            ReturPenjualanDetailService::destroy($dataLama);
        }

        // insert data baru
        foreach ($data as $item) {
            $returPenjualanDetail = ReturPenjualanDetail::find($item['id']);
            if ($returPenjualanDetail) {
                ReturPenjualanDetailService::update($returPenjualanDetail, $item);
            } else {
                ReturPenjualanDetailService::create($obj, $item);
            }
        }

        return true;
    }

    public static function destroy(ReturPenjualan $obj): bool
    {
        if (!$obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        MutasiTransaksiService::destroy($obj->mutasiTransaksi);

        foreach ($obj->details as $detail) {
            ReturPenjualanDetailService::destroy($detail);
        }

        return $obj->delete();
    }
}
