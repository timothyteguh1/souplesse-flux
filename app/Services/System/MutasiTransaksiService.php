<?php

namespace App\Services\System;

use App\Exceptions\GeneralException;
use App\Models\System\MutasiTransaksi;
use App\Utilities\Constants\Const_Umum;
use App\Utilities\Functions\TransactionFunction;

class MutasiTransaksiService
{
    public static function increase(
        $tanggal,
        $cabang_id,
        $jenis,
        $vendor_type,
        $vendor_id,
        $reference_type,
        $reference_id,
        $header_type,
        $header_id,
        $jenis_transaksi,
        $jumlah,
        $keterangan,
    ): MutasiTransaksi {
        if ($jumlah <= 0) {
            throw new GeneralException('Jumlah mutasi transaksi harus lebih besar dari 0.');
        }

        $tanggal = _datetime_carbon_db($tanggal);

        $data = [
            'tanggal' => $tanggal,
            'cabang_id' => $cabang_id,
            'jenis' => $jenis,
            'vendor_type' => $vendor_type,
            'vendor_id' => $vendor_id,
            'reference_type' => $reference_type,
            'reference_id' => $reference_id,
            'header_type' => $header_type,
            'header_id' => $header_id,
            'jenis_transaksi' => $jenis_transaksi,
            'jumlah' => $jumlah,
            'keterangan' => $keterangan,
        ];

        return MutasiTransaksi::create($data);
    }

    public static function decrease(
        $tanggal,
        $cabang_id,
        $jenis,
        $vendor_type,
        $vendor_id,
        $reference_type,
        $reference_id,
        $header_type,
        $header_id,
        $jenis_transaksi,
        $jumlah,
        $keterangan,
    ): MutasiTransaksi {
        if ($jumlah >= 0) {
            throw new GeneralException('Jumlah mutasi transaksi harus lebih kecil dari 0.');
        }

        $tanggal = _datetime_carbon_db($tanggal);

        $data = [
            'tanggal' => $tanggal,
            'cabang_id' => $cabang_id,
            'jenis' => $jenis,
            'vendor_type' => $vendor_type,
            'vendor_id' => $vendor_id,
            'reference_type' => $reference_type,
            'reference_id' => $reference_id,
            'header_type' => $header_type,
            'header_id' => $header_id,
            'jenis_transaksi' => $jenis_transaksi,
            'jumlah' => $jumlah,
            'keterangan' => $keterangan,
        ];

        //        $isCanMinus = in_array(
        //            $jenis,
        //            [
        //                Const_Umum::JENIS_MUTASI_TRANSAKSI_KAS,
        //                Const_Umum::JENIS_MUTASI_TRANSAKSI_PENDAPATAN,
        //                Const_Umum::JENIS_MUTASI_TRANSAKSI_BEBAN,
        //            ],
        //        );
        //
        //        if ($isCanMinus) {
        //            return MutasiTransaksi::create($data);
        //        }

        // saldo transaksi pada saat transaksi
        $vendor = $vendor_type::findOrFail($vendor_id);
        $saldoTransaksi = TransactionFunction::getVendorSaldo($cabang_id, $vendor, $jenis, $tanggal);

        if ($saldoTransaksi + $jumlah < 0) {
            throw new GeneralException(
                sprintf(
                    'Qty baru dari %s pada saat transaksi tidak boleh kurang dari 0.',
                    optional($vendor)->nama,
                ),
            );
        }

        // saldo transaksi aktual
        $saldoTransaksi = TransactionFunction::getVendorSaldo($cabang_id, $vendor, $jenis);

        if ($saldoTransaksi + $jumlah < 0) {
            throw new GeneralException(
                sprintf(
                    'Qty baru dari %s tidak boleh kurang dari 0.',
                    optional($vendor)->nama,
                ),
            );
        }

        return MutasiTransaksi::create($data);
    }

    public static function destroy(?MutasiTransaksi $mutasiTransaksi = null): bool
    {
        if (!$mutasiTransaksi) {
            return true;
        }

        //        $isCanMinus = in_array(
        //            $mutasiTransaksi->jenis,
        //            [
        //                Const_Umum::JENIS_MUTASI_TRANSAKSI_KAS,
        //                Const_Umum::JENIS_MUTASI_TRANSAKSI_PENDAPATAN,
        //                Const_Umum::JENIS_MUTASI_TRANSAKSI_BEBAN,
        //            ],
        //        );
        //
        //        if ($isCanMinus) {
        //            return $mutasiTransaksi->delete();
        //        }

        $mutasiTransaksi->loadMissing('vendor');
        $vendor = $mutasiTransaksi->vendor;

        // saldo transaksi pada saat transaksi
        $saldoTransaksi = TransactionFunction::getVendorSaldo($mutasiTransaksi->cabang_id, $vendor, $mutasiTransaksi->jenis, _datetime_carbon_db($mutasiTransaksi->tanggal));

        if ($saldoTransaksi < $mutasiTransaksi->jumlah) {
            throw new GeneralException(
                sprintf(
                    'Qty baru dari %s pada saat transaksi tidak boleh kurang dari 0.',
                    optional($vendor)->nama,
                ),
            );
        }

        // saldo transaksi aktual
        $saldoTransaksi = TransactionFunction::getVendorSaldo($mutasiTransaksi->cabang_id, $vendor, $mutasiTransaksi->jenis);
        if ($saldoTransaksi < $mutasiTransaksi->jumlah) {
            throw new GeneralException(
                sprintf(
                    'Qty baru dari %s tidak boleh kurang dari 0.',
                    optional($vendor)->nama,
                ),
            );
        }

        return $mutasiTransaksi->delete();
    }
}
