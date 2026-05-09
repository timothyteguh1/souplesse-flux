<?php

namespace App\Services;

use App\Models\Billing;
use App\Models\BillingDetail;
use App\Exceptions\GeneralException;

class BillingService
{
    public static function create(array $data = []): Billing
    {
        if (!Billing::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }

        $data['ppn_percent'] = $data['ppn_percent'] ?: 0;
        $data['diskon'] = $data['diskon'] ?: 0;
        $data['beban_lain'] = $data['beban_lain'] ?: 0;
        $obj = Billing::create($data);

        // detail
        foreach ($data['items'] as $item) {
            BillingDetailService::create($obj, $item);
        }

        return $obj;
    }

    public static function update(Billing $obj, array $data = []): bool
    {
        if (!$obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        $obj->update($data);
        self::updateDetail($obj, $data['items']);
        $obj->refresh();

        return true;
    }

    public static function updateDetail(Billing $obj, array $data = []): bool
    {
        $collects = collect([
            $data,
        ]);

        $updatedIds = $collects->pluck('*.id')->flatten()->whereNotNull();
        $dataLamas = $obj->details()->whereNotIn('id', $updatedIds)->get();

        foreach ($dataLamas as $dataLama) {
            BillingDetailService::destroy($dataLama);
        }

        // insert data baru
        foreach ($data as $item) {
            $billingDetail = BillingDetail::find($item['id']);
            if ($billingDetail) {
                BillingDetailService::update($billingDetail, $item);
            } else {
                BillingDetailService::create($obj, $item);
            }
        }

        return true;
    }

    public static function destroy(Billing $obj): bool
    {
        if (!$obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        return $obj->delete();
    }
}
