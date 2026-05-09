<?php

namespace App\Services\Penjualan;

use App\Models\Master\Customer;
use App\Exceptions\GeneralException;
use App\Models\Penjualan\SuratJalan;
use App\Models\System\MutasiTransaksi;
use App\Utilities\Constants\Const_Umum;
use App\Models\Penjualan\FakturPenjualan;
use App\Utilities\Constants\Const_Status;
use App\Models\Penjualan\FakturPenjualanBeban;
use App\Models\Penjualan\FakturPenjualanDetail;
use App\Services\System\MutasiTransaksiService;
use App\Utilities\Functions\TransactionFunction;
use App\Models\Penjualan\FakturPenjualanPembayaran;
use App\Models\Penjualan\PesananPenjualan;

class FakturPenjualanService
{
    public static function create(array $data = []): FakturPenjualan
    {
        if (!FakturPenjualan::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }

        if ($data['jenis_transaksi'] == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_LUNAS) {
            $data['status'] = Const_Status::FAKTUR_PENJUALAN_LUNAS;
        }

        $obj = FakturPenjualan::create($data);

        // detail
        foreach ($data['items'] as $item) {
            FakturPenjualanDetailService::create($obj, $item);
        }

        // beban
        foreach ($data['items_beban'] as $item) {
            FakturPenjualanBebanService::create($obj, $item);
        }

        if ($data['jenis_transaksi'] == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_LUNAS) {
            // pembayaran
            foreach ($data['items_pembayaran'] as $item) {
                FakturPenjualanPembayaranService::create($obj, $item);
            }
        } else {
            MutasiTransaksiService::increase(
                $obj->tanggal,
                $obj->cabang_id,
                Const_Umum::JENIS_MUTASI_TRANSAKSI_PIUTANG,
                Customer::class,
                $obj->customer_id,
                FakturPenjualan::class,
                $obj->id,
                FakturPenjualan::class,
                $obj->id,
                $obj->jenis_transaksi,
                $obj->grandtotal,
                'Faktur Penjualan: [' . $obj->kode . ']',
            );
        }

        $suratJalan = SuratJalan::find($obj->surat_jalan_id);
        if ($suratJalan) {
            SuratJalanService::updateStatusSelesai($suratJalan);
        }

        $pesananPenjualan = PesananPenjualan::find($obj->pesanan_penjualan_id);
        if ($pesananPenjualan) {
            PesananPenjualanService::updateStatusSelesai($pesananPenjualan);
        }

        return $obj;
    }

    public static function update(FakturPenjualan $obj, array $data = []): bool
    {
        if (!$obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        if ($data['jenis_transaksi'] == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_LUNAS) {
            $data['status'] = Const_Status::FAKTUR_PENJUALAN_LUNAS;
        } else {
            $data['status'] = Const_Status::FAKTUR_PENJUALAN_BELUM_LUNAS;
        }

        $obj->update($data);
        self::updateDetail($obj, $data['items']);
        self::updateBeban($obj, $data['items_beban']);

        if ($data['jenis_transaksi'] == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_LUNAS) {
            self::updatePembayaran($obj, $data['items_pembayaran']);
        } else {
            foreach ($obj->pembayarans as $value) {
                FakturPenjualanPembayaranService::destroy($value);
            }

            MutasiTransaksiService::destroy($obj->mutasiTransaksi);
            $obj->refresh();

            MutasiTransaksiService::increase(
                $obj->tanggal,
                $obj->cabang_id,
                Const_Umum::JENIS_MUTASI_TRANSAKSI_PIUTANG,
                Customer::class,
                $obj->customer_id,
                FakturPenjualan::class,
                $obj->id,
                FakturPenjualan::class,
                $obj->id,
                $obj->jenis_transaksi,
                $obj->grandtotal,
                'Faktur Penjualan: [' . $obj->kode . ']',
            );
        }

        return true;
    }

    public static function updateDetail(FakturPenjualan $obj, array $data = []): bool
    {
        $collects = collect([
            $data,
        ]);

        $updatedIds = $collects->pluck('*.id')->flatten()->whereNotNull();

        $dataLamas = $obj->details()->whereNotIn('id', $updatedIds)->get();

        foreach ($dataLamas as $dataLama) {
            FakturPenjualanDetailService::destroy($dataLama);
        }
        // insert data baru
        foreach ($data as $item) {
            $fakturPenjualanDetail = FakturPenjualanDetail::find($item['id']);
            if ($fakturPenjualanDetail) {
                FakturPenjualanDetailService::update($fakturPenjualanDetail, $item);
            } else {
                FakturPenjualanDetailService::create($obj, $item);
            }
        }

        return true;
    }

    public static function updateBeban(FakturPenjualan $obj, array $data = []): bool
    {
        $collects = collect([
            $data,
        ]);

        $updatedIds = $collects->pluck('*.id')->flatten()->whereNotNull();
        $dataLamas = $obj->fakturPenjualanBebans()->whereNotIn('id', $updatedIds)->get();

        foreach ($dataLamas as $dataLama) {
            FakturPenjualanBebanService::destroy($dataLama);
        }

        // insert data baru
        foreach ($data as $item) {
            $item['faktur_penjualan_id'] = $obj->id;

            $fakturPenjualanBeban = FakturPenjualanBeban::find($item['id']);
            if ($fakturPenjualanBeban) {
                FakturPenjualanBebanService::update($obj, $item);
            } else {
                FakturPenjualanBebanService::create($obj, $item);
            }
        }

        return true;
    }

    public static function updatePembayaran(FakturPenjualan $obj, array $data = []): bool
    {
        $collects = collect([
            $data,
        ]);

        $updatedIds = $collects->pluck('*.id')->flatten()->whereNotNull();

        $dataLamas = $obj->pembayarans()->whereNotIn('id', $updatedIds)->get();

        foreach ($dataLamas as $dataLama) {
            FakturPenjualanPembayaranService::destroy($dataLama);
        }

        // insert data baru
        foreach ($data as $item) {
            $fakturPenjualanPembayaran = FakturPenjualanPembayaran::find($item['id']);
            if ($fakturPenjualanPembayaran) {
                FakturPenjualanPembayaranService::update($fakturPenjualanPembayaran, $item);
            } else {
                FakturPenjualanPembayaranService::create($obj, $item);
            }
        }

        return true;
    }

    public static function updateStatus(MutasiTransaksi $mutasiTransaksi): bool
    {
        $obj = FakturPenjualan::find($mutasiTransaksi->reference_id);
        $terbayar = TransactionFunction::getSisaPiutang($mutasiTransaksi->id, null);

        if ($obj->grandtotal == $terbayar && $obj->status != Const_Status::FAKTUR_PENJUALAN_LUNAS) {
            $obj->status = Const_Status::FAKTUR_PENJUALAN_LUNAS;

            return $obj->save();
        } elseif ($obj->grandtotal != $terbayar && $obj->status == Const_Status::FAKTUR_PENJUALAN_LUNAS) {
            $obj->status = Const_Status::FAKTUR_PENJUALAN_BELUM_LUNAS;

            return $obj->save();
        }

        return true;
    }

    public static function updatePajak(FakturPenjualan $obj, array $data = []): bool
    {
        $data['tanggal_faktur_pajak'] = $data['tanggal_faktur_pajak'] ? date('Y-m-d', strtotime(str_replace('/', '-', $data['tanggal_faktur_pajak']))) : null;
        $obj->update($data);

        return true;
    }

    public static function destroy(FakturPenjualan $obj): bool
    {
        if (!$obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        //hapus jurnal lama
        MutasiTransaksiService::destroy($obj->mutasiTransaksi);

        $pesananPenjualan = $obj->pesananPenjualan;

        if ($pesananPenjualan) {
            PesananPenjualanService::updateStatus($pesananPenjualan);
        }

        foreach ($obj->details as $detail) {
            FakturPenjualanDetailService::destroy($detail);
        }

        foreach ($obj->pembayarans as $detail) {
            FakturPenjualanPembayaranService::destroy($detail);
        }

        return $obj->delete();
    }

    public static function updateStatusPengirimanBelumDikirim(FakturPenjualan $obj)
    {
        $obj->status_pengiriman = Const_Status::PENGIRIMAN_DETAIL_BELUM_DIKIRIM;
        $obj->save();
    }

    public static function updateStatusPengirimanDalamPerjalanan(FakturPenjualan $obj)
    {
        $obj->status_pengiriman = Const_Status::PENGIRIMAN_DETAIL_DALAM_PERJALANAN;
        $obj->save();
    }
}
