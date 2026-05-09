<?php

namespace App\Traits\Livewire;

trait WithModalForm
{
    public function showModal()
    {
        $this->dispatch('show' . (new \ReflectionClass($this))->getShortName());
    }

    public function closeModal()
    {
        $this->dispatch('close' . (new \ReflectionClass($this))->getShortName());
    }
}
