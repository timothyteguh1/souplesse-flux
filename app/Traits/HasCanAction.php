<?php

namespace App\Traits;

use App\Exceptions\GeneralException;

trait HasCanAction
{
    public function canShow(): bool
    {
        if (auth()->user()) {
            return auth()->user()->can($this->getPermissionShow());
        }

        return true;
    }

    public static function canPermissionCreate(): bool
    {
        return (new self())->canCreate();
    }

    public function canCreate(): bool
    {
        if (auth()->user()) {
            return auth()->user()->can($this->getPermissionCreate());
        }

        return true;
    }

    public static function canPermissionEdit(): bool
    {
        return (new self())->canEdit();
    }

    public function canEdit(): bool
    {
        if (auth()->user()) {
            $cabang = session()->get('cabang_id');
            $cabang_id = $this->getCabangId();
            if ($cabang && $cabang_id && $cabang != $cabang_id) {
                return false;
            }

            return auth()->user()->can($this->getPermissionEdit());
        }

        return true;
    }

    public static function canPermissionDelete(): bool
    {
        return (new self())->canDelete();
    }

    public function canDelete(): bool
    {
        if (auth()->user()) {
            $cabang = session()->get('cabang_id');
            $cabang_id = $this->getCabangId();
            if ($cabang && $cabang_id && $cabang != $cabang_id) {
                return false;
            }

            return auth()->user()->can($this->getPermissionDelete());
        }

        return true;
    }

    private function getCabangId()
    {
        if (empty($this->cabang_id) && $this->isCabang) {
            throw new GeneralException('$cabang_id must be defined in ' . __CLASS__);
        }

        return $this->cabang_id;
    }
}
