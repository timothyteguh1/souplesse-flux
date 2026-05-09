<?php

namespace App\Traits\Livewire;

use App\Exceptions\GeneralException;
use App\Exports\TemplateImportDataExports;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

trait WithImportForm
{
    use HasCheckPermissionGate;
    use WithFileUploads;

    protected $layout = 'admin.components.layouts.import';
    protected $redirectRoute = null;

    private function getExportView()
    {
        if (empty($this->template_view)) {
            throw new GeneralException('$template_view must be defined in ' . __CLASS__);
        }

        return $this->template_view;
    }

    private function getExportFilename($extension = 'xlsx')
    {
        if (empty($this->template_filename)) {
            return 'Template.' . $extension;
        }

        return $this->template_filename . '.' . $extension;
    }

    public function downloadTemplate()
    {
        $view = $this->getExportView();
        $filename = $this->getExportFilename('xlsx');

        return Excel::download(new TemplateImportDataExports($view), $filename);
    }

    public function submitDefault()
    {
        $this->submitAndBackToIndex();
    }

    public function submitAndCreate()
    {
        $this->processSubmit('create');
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
            $count = $this->submit($validated);
            DB::commit();

            session()->flash(
                'flash_success',
                sprintf(
                    '%s telah di-import. (%s Item)',
                    $this->menuTitle,
                    $count,
                ),
            );

            if ($this->redirectRoute) {
                return redirect()->to($this->redirectRoute);
            }

            if ($to == 'index') {
                return redirect()->to($this->model::routeIndex());
            } elseif ($to == 'create') {
                return redirect()->to($this->model::routeCreate());
            }

            return redirect()->to($this->model::routeIndex());
        } catch (Exception $exception) {
            DB::rollBack();
            $this->dispatch('page-to-top');

            if ($exception instanceof \Maatwebsite\Excel\Validators\ValidationException) {
                $failures = $exception->failures();

                foreach ($failures as $failure) {
                    $baris = $failure->row();
                    $attribute = $failure->attribute();
                    $values = $failure->values();
                    $value = $values[$attribute];

                    foreach ($failure->errors() as $index => $error) {
                        $message = sprintf(
                            "Baris ke-%s: %s",
                            $baris,
                            $error,
                        );

                        if ($value) {
                            $message .= sprintf(
                                " [%s]",
                                $value,
                            );
                        }

                        $this->addError('baris_' . $baris . '_' . ++$index, $message);
                    }
                }

                return false;
            }

            if ($exception instanceof ValidationException) {
                throw $exception;
            }

            $this->addError('flash_danger', _get_exception_message($exception));

            return false;
        }
    }
}
