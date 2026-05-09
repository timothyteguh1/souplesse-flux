<?php

namespace App\Services\Master;

use App\Models\Master\Customer;
use App\Models\Master\CustomerDiskon;

class CustomerDiskonService
{
    public static function create(Customer $obj, array $data = []): CustomerDiskon
    {
        return $obj->customerDiskons()->create($data);
    }

    public static function update(CustomerDiskon $objDetail, array $data = []): bool
    {
        return $objDetail->update($data);
    }

    public static function destroy(CustomerDiskon $objDetail): bool
    {
        return $objDetail->delete();
    }
}
