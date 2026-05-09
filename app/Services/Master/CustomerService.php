<?php

namespace App\Services\Master;

use App\Exceptions\GeneralException;
use App\Models\Master\Customer;
use App\Models\Master\CustomerDiskon;

class CustomerService
{
    public static function create(array $data = []): Customer
    {
        if (! Customer::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }

        $obj = Customer::create($data);

        foreach ($data['items'] as $item) {
            CustomerDiskonService::create($obj, $item);
        }

        return $obj;
    }

    public static function update(Customer $obj, array $data = []): bool
    {
        if (! $obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        self::updateDetail($obj, $data['items']);
        $obj->update($data);

        return true;
    }

    public static function updateDetail(Customer $obj, array $data = []): bool
    {
        $collects = collect([
            $data,
        ]);

        $updatedIds = $collects->pluck('*.id')->flatten()->whereNotNull();

        $dataLamas = $obj->customerDiskons()->whereNotIn('id', $updatedIds)->get();

        foreach ($dataLamas as $dataLama) {
            CustomerDiskonService::destroy($dataLama);
        }
        // insert data baru
        foreach ($data as $item) {
            $customerDiskon = CustomerDiskon::find($item['id']);
            if ($customerDiskon) {
                CustomerDiskonService::update($customerDiskon, $item);
            } else {
                CustomerDiskonService::create($obj, $item);
            }
        }

        return true;
    }

    public static function destroy(Customer $obj): bool
    {
        if (! $obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        return $obj->delete();
    }
}
