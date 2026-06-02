<?php

namespace App\Traits\Livewire;

use ReflectionClass;

trait WithModalForm
{
    use HasCheckPermissionGate;

    public function showModal($form_id = null)
    {
        if ($form_id) {
            $this->dispatch('show' . $form_id);
        } else {
            $this->dispatch('show' . (new ReflectionClass($this))->getShortName());
        }
    }

    public function closeModal($form_id = null)
    {
        if ($form_id) {
            $this->dispatch('close' . $form_id);
        } else {
            $this->dispatch('close' . (new ReflectionClass($this))->getShortName());
        }
    }

    public function checkPermissionCreate()
    {
        if (auth()->user()->can($this->model::permissionCreate())) {
            return true;
        }

        $this->dispatch('showNotification', [
            'message' => 'Anda tidak memiliki akses untuk menambah data.',
        ]);

        return false;
    }
}
