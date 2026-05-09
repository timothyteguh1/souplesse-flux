<?php

namespace App\Traits\Livewire;

use App\Exceptions\GeneralException;
use DB;
use Exception;
use App\Utilities\Constants\Const_Umum;

trait WithShowForm
{
    use HasCetakBrowser;
    use HasCheckPermissionGate;

    protected $layout = 'admin.components.layouts.show';

    public function delete($params)
    {
        $this->checkPermissionDeleteGate();

        try {
            DB::beginTransaction();
            $this->processDelete($params['id']);
            DB::commit();

            session()->flash('flash_success', $this->menuTitle . ' telah dihapus.');

            if ($this->obj->jenis_transaksi == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_KREDIT || $this->obj->jenis_transaksi == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_LUNAS) {
                redirect()->route('admin.penjualan.faktur-penjualan.index');
            } elseif ($this->obj->jenis_transaksi == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_SJ) {
                redirect()->route('admin.penjualan.faktur-penjualan-via-sj.index');
            } elseif ($this->obj->jenis_transaksi == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_SO) {
                redirect()->route('admin.penjualan.faktur-penjualan-via-so.index');
            }

            return redirect()->to($this->model::routeIndex());
        } catch (Exception $exception) {
            DB::rollBack();
            $this->addError('flash_danger', _get_exception_message($exception));
        }
    }

    public function processDelete($id)
    {
        throw new GeneralException('processDelete function must be defined in ' . __CLASS__);
    }
}
