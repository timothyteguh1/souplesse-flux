<?php

namespace App\Services\Penjualan;

use App\Exceptions\GeneralException;
use App\Utilities\Constants\Const_Status;
use App\Models\Penjualan\PesananPenjualan;
use App\Models\Penjualan\PesananPenjualanDetail;

class PesananPenjualanService
{
    public static function create(array $data = []): PesananPenjualan
    {
        if (!PesananPenjualan::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }

        $obj = PesananPenjualan::create($data);
        // detail
        foreach ($data['items'] as $item) {
            PesananPenjualanDetailService::create($obj, $item);
        }

        return $obj;
    }

    public static function update(PesananPenjualan $obj, array $data = []): bool
    {
        if (!$obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        $obj->update($data);

        self::updateDetail($obj, $data['items']);
        $obj->refresh();

        return true;
    }

    public static function updateDetail(PesananPenjualan $obj, array $data = []): bool
    {
        $collects = collect([
            $data,
        ]);

        $updatedIds = $collects->pluck('*.id')->flatten()->whereNotNull();
        $dataLamas = $obj->details()->whereNotIn('id', $updatedIds)->get();

        foreach ($dataLamas as $dataLama) {
            PesananPenjualanDetailService::destroy($dataLama);
        }

        // insert data baru
        foreach ($data as $item) {
            $item['pesanan_penjualan_id'] = $obj->id;

            $pesananPenjualanDetail = PesananPenjualanDetail::find($item['id']);
            if ($pesananPenjualanDetail) {
                PesananPenjualanDetailService::update($obj, $item);
            } else {
                PesananPenjualanDetailService::create($obj, $item);
            }
        }

        return true;
    }

    public static function updateStatusBelumDicetak(PesananPenjualan $obj)
    {
        $obj->status = Const_Status::PESANAN_PENJUALAN_BELUM_DICETAK;
        $obj->save();
    }

    public static function updateStatusBelumDikirim(PesananPenjualan $obj)
    {
        $obj->status = Const_Status::PESANAN_PENJUALAN_BELUM_DIKIRIM;
        $obj->save();
    }

    public static function updateStatusSelesai(PesananPenjualan $obj)
    {
        $obj->status = Const_Status::PESANAN_PENJUALAN_SELESAI;
        $obj->save();
    }

    public static function updateStatusBatal(PesananPenjualan $obj)
    {
        $obj->status = Const_Status::PESANAN_PENJUALAN_BATAL;
        $obj->save();
    }

    public static function updateStatus(PesananPenjualan $obj): bool
    {
        if ($obj->is_terpenuhi) {
            $obj->status = Const_Status::PESANAN_PENJUALAN_SELESAI;
        } else {
            $obj->status = Const_Status::PESANAN_PENJUALAN_BELUM_SELESAI;
        }

        if ($obj->isDirty('status')) {
            $obj->save();
        }

        return true;
    }

    public static function destroy(PesananPenjualan $obj): bool
    {
        if (!$obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        foreach ($obj->details as $detail) {
            PesananPenjualanDetailService::destroy($detail);
        }

        return $obj->delete();
    }
}
