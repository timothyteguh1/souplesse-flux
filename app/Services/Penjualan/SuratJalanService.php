<?php

namespace App\Services\Penjualan;

use App\Models\Penjualan\PesananPenjualan;
use App\Models\Penjualan\SuratJalan;
use App\Models\Penjualan\SuratJalanDetail;
use App\Utilities\Constants\Const_Status;

class SuratJalanService
{
    public static function create(array $data = []): SuratJalan
    {
        $obj = SuratJalan::create($data);
        // detail
        foreach ($data['items'] as $item) {
            SuratJalanDetailService::create($obj, $item);
        }

        $pesananPenjualan = PesananPenjualan::find($obj->pesanan_penjualan_id);
        PesananPenjualanService::updateStatusSelesai($pesananPenjualan);

        return $obj;
    }

    public static function update(SuratJalan $obj, array $data = []): bool
    {
        $obj->update($data);
        self::updateDetail($obj, $data['items']);
        $obj->refresh();

        return true;
    }

    public static function updateDetail(SuratJalan $obj, array $data = []): bool
    {
        $collects = collect([
            $data,
        ]);

        $updatedIds = $collects->pluck('*.id')->flatten()->whereNotNull();
        $dataLamas = $obj->details()->whereNotIn('id', $updatedIds)->get();

        foreach ($dataLamas as $dataLama) {
            SuratJalanDetailService::destroy($dataLama);
        }

        // insert data baru
        foreach ($data as $item) {
            $pesananPenjualanDetail = SuratJalanDetail::find($item['id']);
            if ($pesananPenjualanDetail) {
                SuratJalanDetailService::update($pesananPenjualanDetail, $item);
            } else {
                SuratJalanDetailService::create($obj, $item);
            }
        }

        return true;
    }

    public static function updateStatusBelumSelesai(SuratJalan $obj)
    {
        $obj->status = Const_Status::SURAT_JALAN_BELUM_SELESAI;
        $obj->save();
    }

    public static function updateStatusSelesai(SuratJalan $obj)
    {
        $obj->status = Const_Status::SURAT_JALAN_SELESAI;
        $obj->save();
    }

    public static function updateStatus(SuratJalan $obj): bool
    {
        if ($obj->is_terfaktur_semua) {
            $obj->status = Const_Status::SURAT_JALAN_SELESAI;
        } else {
            $obj->status = Const_Status::SURAT_JALAN_BELUM_SELESAI;
        }

        if ($obj->isDirty('status')) {
            $obj->save();
        }

        return true;
    }

    public static function destroy(SuratJalan $obj): bool
    {
        foreach ($obj->details as $detail) {
            SuratJalanDetailService::destroy($detail);
        }

        $pesananPenjualan = PesananPenjualan::find($obj->pesanan_penjualan_id);
        PesananPenjualanService::updateStatusBelumDikirim($pesananPenjualan);

        return $obj->delete();
    }
}
