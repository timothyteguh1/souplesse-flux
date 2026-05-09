<?php

namespace App\Services\Persediaan;

use App\Exceptions\GeneralException;
use App\Models\Persediaan\PenguranganPersediaan;
use App\Models\Persediaan\PenguranganPersediaanDetail;

class PenguranganPersediaanService
{
    public static function create(array $data = []): PenguranganPersediaan
    {
        $obj = PenguranganPersediaan::create($data);
        // detail
        foreach ($data['items'] as $item) {
            PenguranganPersediaanDetailService::create($obj, $item);
        }

        return $obj;
    }

    public static function update(PenguranganPersediaan $obj, array $data = []): bool
    {
        if (! $obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        $obj->update($data);

        self::updateDetail($obj, $data['items']);

        return true;
    }

    public static function updateDetail(PenguranganPersediaan $obj, array $data = []): bool
    {
        $collects = collect([
            $data,
        ]);

        $updatedIds = $collects->pluck('*.id')->flatten()->whereNotNull();
        $dataLamas = $obj->details()->whereNotIn('id', $updatedIds)->get();

        foreach ($dataLamas as $dataLama) {
            PenguranganPersediaanDetailService::destroy($dataLama);
        }

        // insert data baru
        foreach ($data as $item) {
            $penguranganPersediaanDetail = PenguranganPersediaanDetail::find($item['id']);
            if ($penguranganPersediaanDetail) {
                PenguranganPersediaanDetailService::update($penguranganPersediaanDetail, $item);
            } else {
                PenguranganPersediaanDetailService::create($obj, $item);
            }
        }

        return true;
    }

    public static function destroy(PenguranganPersediaan $obj): bool
    {
        if (! $obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        foreach ($obj->details as $detail) {
            PenguranganPersediaanDetailService::destroy($detail);
        }

        return $obj->delete();
    }
}
