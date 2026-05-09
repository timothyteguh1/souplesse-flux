<?php

namespace App\Services;

use App\Models\Billing;
use App\Models\BillingDetail;

class BillingDetailService
{
    public static function create(Billing $obj, array $data = []): BillingDetail
    {
        $detail = $obj->details()->create($data);
        return $detail;
    }

    public static function update(BillingDetail $objDetail, array $data = []): bool
    {
        $objDetail->update($data);

        return true;
    }

    public static function destroy(BillingDetail $objDetail): bool
    {
        $objDetail->delete();

        return true;
    }
}
