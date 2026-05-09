<?php

namespace App\Services\Persediaan;

use App\Exceptions\GeneralException;
use App\Models\Persediaan\PenambahanPersediaan;
use App\Models\Persediaan\PenambahanPersediaanDetail;

class PenambahanPersediaanService
{
    public static function create(array $data = []): PenambahanPersediaan
    {
        $obj = PenambahanPersediaan::create($data);
        // detail
        foreach ($data['items'] as $item) {
            PenambahanPersediaanDetailService::create($obj, $item);
        }

        return $obj;
    }

    public static function update(PenambahanPersediaan $obj, array $data = []): bool
    {
        if (! $obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        $obj->update($data);

        self::updateDetail($obj, $data['items']);

        return true;
    }

    public static function updateDetail(PenambahanPersediaan $obj, array $data = []): bool
    {
        $collects = collect([
            $data,
        ]);

        $updatedIds = $collects->pluck('*.id')->flatten()->whereNotNull();
        $dataLamas = $obj->details()->whereNotIn('id', $updatedIds)->get();

        foreach ($dataLamas as $dataLama) {
            PenambahanPersediaanDetailService::destroy($dataLama);
        }

        // insert data baru
        foreach ($data as $item) {
            $penambahanPersediaanDetail = PenambahanPersediaanDetail::find($item['id']);
            if ($penambahanPersediaanDetail) {
                PenambahanPersediaanDetailService::update($penambahanPersediaanDetail, $item);
            } else {
                PenambahanPersediaanDetailService::create($obj, $item);
            }
        }

        return true;
    }

    public static function destroy(PenambahanPersediaan $obj): bool
    {
        if (! $obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        //hapus jurnal lama
        foreach ($obj->details as $detail) {
            PenambahanPersediaanDetailService::destroy($detail);
        }

        return $obj->delete();
    }
}
