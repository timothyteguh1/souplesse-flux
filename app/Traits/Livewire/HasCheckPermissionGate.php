<?php

namespace App\Traits\Livewire;

use App\Exceptions\GeneralException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

trait HasCheckPermissionGate
{
    public function checkPermissionIndexGate()
    {
        abort_if(Gate::none([$this->getModel()::permissionIndex()]), Response::HTTP_FORBIDDEN);
    }

    public function checkPermissionShowGate()
    {
        abort_if(Gate::none([$this->getModel()::permissionShow()]), Response::HTTP_FORBIDDEN);
        if (isset($this->obj)) {
            abort_if(! $this->obj->canShow(), Response::HTTP_FORBIDDEN, 'The requested object is not available [Cannot Show].');
        }
    }

    public function checkPermissionCreateGate()
    {
        abort_if(Gate::none([$this->getModel()::permissionCreate()]), Response::HTTP_FORBIDDEN);
    }

    public function checkPermissionEditGate()
    {
        abort_if(Gate::none([$this->getModel()::permissionEdit()]), Response::HTTP_FORBIDDEN);
        if (isset($this->obj)) {
            abort_if(! $this->obj->canEdit(), Response::HTTP_FORBIDDEN, 'The requested object is not available [Cannot Edit].');
        }
    }

    public function checkPermissionDeleteGate()
    {
        abort_if(Gate::none([$this->getModel()::permissionDelete()]), Response::HTTP_FORBIDDEN);
        if (isset($this->obj)) {
            abort_if(! $this->obj->canDelete(), Response::HTTP_FORBIDDEN, 'The requested object is not available [Cannot Delete].');
        }
    }

    private function getModel()
    {
        if (empty($this->model)) {
            throw new GeneralException('$model must be defined in ' . __CLASS__);
        }

        return $this->model;
    }
}
