<?php

namespace App\Traits\Livewire;

use App\Exceptions\GeneralException;
use DB;
use Exception;
use Illuminate\Validation\ValidationException;

trait WithEditForm
{
    use HasCheckPermissionGate;

    protected $layout = 'admin.components.layouts.edit';
    protected $redirectRoute = null;
    protected $confirmationAction = 'submitDefault';
    protected $confirmationMessage = '';
    protected $modalAction = 'refreshInfo';
    protected $modalDialog = null;

    public function submitDefault()
    {
        $this->submitAndShow();
    }

    public function submitAndCreate()
    {
        $this->processSubmit('create');
    }

    public function submitAndShow()
    {
        $this->processSubmit('show');
    }

    public function submitAndBackToIndex()
    {
        $this->processSubmit('index');
    }

    private function processSubmit($to)
    {
        try {
            $validated = $this->validate();

            DB::beginTransaction();
            $obj = $this->submit($validated);
            DB::commit();

            session()->flash('flash_success', $this->menuTitle . ' telah diubah.');

            if ($this->redirectRoute) {
                return redirect()->to($this->redirectRoute);
            }

            if ($to == 'index') {
                return redirect()->to($obj->getRouteIndex());
            } elseif ($to == 'show') {
                return redirect()->to($obj->getRouteShow());
            } elseif ($to == 'create') {
                return redirect()->to($obj->getRouteCreate());
            }

            return redirect()->to($obj->getRouteIndex());
        } catch (Exception $exception) {
            DB::rollBack();
            $this->dispatch('page-to-top');

            if ($exception instanceof ValidationException) {
                throw $exception;
            }

            $this->addError('flash_danger', _get_exception_message($exception));

            return false;
        }
    }

    public function submit($validated)
    {
        throw new GeneralException('submit function must be defined in ' . __CLASS__);
    }

    public function submitConfirmation(): void
    {
        try {
            $validated = $this->validate();

            $this->confirmationAction = 'submitDefault';
            $this->confirmationMessage = '';

            $isConfirmed = $this->confirmation($validated);
            if (!$isConfirmed) {
                $this->dispatch('confirmation', [
                    'action' => $this->confirmationAction,
                    'message' => $this->confirmationMessage,
                ]);

                return;
            }

            $this->submitDefault();
        } catch (Exception $exception) {
            $this->dispatch('page-to-top');

            if ($exception instanceof ValidationException) {
                throw $exception;
            }

            $this->addError('flash_danger', _get_exception_message($exception));
        }
    }

    public function confirmation($validated): bool
    {
        throw new GeneralException('confirmation function must be defined in ' . __CLASS__);
    }

    public function submitModal(): void
    {
        try {
            $validated = $this->validate();

            $this->modalAction = 'refreshInfo';
            $this->modalDialog = null;

            $parameters = $this->modalParameters($validated);
            $this->dispatch($this->modalAction, $parameters)->to($this->modalDialog);

            $this->skipRender();
        } catch (Exception $exception) {
            $this->dispatch('page-to-top');

            if ($exception instanceof ValidationException) {
                throw $exception;
            }

            $this->addError('flash_danger', _get_exception_message($exception));
        }
    }

    public function modalParameters($validated): array
    {
        throw new GeneralException('confirmation function must be defined in ' . __CLASS__);
    }
}
