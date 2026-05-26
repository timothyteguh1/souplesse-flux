<?php

namespace App\Services\Pembelian;

use App\Exceptions\GeneralException;
use App\Models\Pembelian\PesananPembelian;
use App\Models\Pembelian\PesananPembelianDetail;
use App\Utilities\Constants\Const_Status;

class PesananPembelianService
{
    public static function create(array $data = []): PesananPembelian
    {
        if (!PesananPembelian::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }

        $obj = PesananPembelian::create($data);
        // detail
        foreach ($data['items'] as $item) {
            PesananPembelianDetailService::create($obj, $item);
        }

        return $obj;
    }

    public static function update(PesananPembelian $obj, array $data = []): bool
    {
        if (!$obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        $obj->update($data);
        self::updateDetail($obj, $data['items']);
        $obj->refresh();

        return true;
    }

    public static function updateDetail(PesananPembelian $obj, array $data = []): bool
    {
        $collects = collect([
            $data,
        ]);

        $updatedIds = $collects->pluck('*.id')->flatten()->whereNotNull();
        $dataLamas = $obj->details()->whereNotIn('id', $updatedIds)->get();

        foreach ($dataLamas as $dataLama) {
            PesananPembelianDetailService::destroy($dataLama);
        }

        // insert data baru
        foreach ($data as $item) {
            $item['pesanan_pembelian_id'] = $obj->id;

            $pesananPembelianDetail = PesananPembelianDetail::find($item['id']);
            if ($pesananPembelianDetail) {
                PesananPembelianDetailService::update($obj, $item);
            } else {
                PesananPembelianDetailService::create($obj, $item);
            }
        }

        return true;
    }

    public static function updateStatusTolak(PesananPembelian $obj)
    {
        $obj->status = Const_Status::PESANAN_PEMBELIAN_DITOLAK;
        $obj->save();
    }

    public static function updateStatusTerima(PesananPembelian $obj)
    {
        $obj->status = Const_Status::PESANAN_PEMBELIAN_BELUM_DITERIMA;
        $obj->save();
    }

    public static function updateStatusTutup(PesananPembelian $obj)
    {
        $obj->status = Const_Status::PESANAN_PEMBELIAN_DITUTUP;
        $obj->save();
    }

    public static function updateStatusDalamPengiriman(PesananPembelian $obj)
    {
        $obj->status = Const_Status::PESANAN_PEMBELIAN_DALAM_PENGIRIMAN;
        $obj->save();
    }

    public static function updateStatusSelesai(PesananPembelian $obj)
    {
        $obj->status = Const_Status::PESANAN_PEMBELIAN_SELESAI;
        $obj->save();
        foreach ($obj->details as $detail) {
            PesananPembelianDetailService::selesai($obj, $detail);
        }
    }

    public static function updateStatus(PesananPembelian $obj): bool
    {
        if ($obj->is_terpenuhi) {
            $obj->status = Const_Status::PESANAN_PEMBELIAN_SELESAI;
        } else {
            $obj->status = Const_Status::PESANAN_PEMBELIAN_BELUM_DITERIMA;
        }

        if ($obj->isDirty('status')) {
            $obj->save();
        }

        return true;
    }

    public static function destroy(PesananPembelian $obj): bool
    {
        if (!$obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        foreach ($obj->details as $detail) {
            PesananPembelianDetailService::destroy($detail);
        }

        return $obj->delete();
    }
}
