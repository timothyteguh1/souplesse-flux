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

// --- TAMBAHAN NAMESPACE UNTUK ACCURATE ---
use App\Models\Master\Perusahaan;
use App\Services\AccurateService;
use Illuminate\Support\Facades\Log;

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
        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                FakturPenjualanDetailService::create($obj, $item);
            }
        }

        // beban
        if (isset($data['items_beban'])) {
            foreach ($data['items_beban'] as $item) {
                FakturPenjualanBebanService::create($obj, $item);
            }
        }

        if ($data['jenis_transaksi'] == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_LUNAS && isset($data['items_pembayaran'])) {
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

        $obj->refresh();

        // --- TRIGGER PUSH FAKTUR KE ACCURATE ---
        self::pushToAccurate($obj);

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
        
        if (isset($data['items'])) {
            self::updateDetail($obj, $data['items']);
        }
        
        if (isset($data['items_beban'])) {
            self::updateBeban($obj, $data['items_beban']);
        }

        if ($data['jenis_transaksi'] == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_LUNAS && isset($data['items_pembayaran'])) {
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

        $obj->refresh();

        // --- TRIGGER PUSH FAKTUR KE ACCURATE ---
        self::pushToAccurate($obj);

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

    // ========================================================
    // LOGIKA PUSH FAKTUR PENJUALAN (SALES INVOICE) KE ACCURATE
    // ========================================================
    protected static function pushToAccurate(FakturPenjualan $fp): void
    {
        try {
            Log::info("=== MULAI PUSH FAKTUR PENJUALAN '{$fp->kode}' KE ACCURATE ===");

            $perusahaan = Perusahaan::whereNotNull('accurate_host')->first(); 
            if (!$perusahaan) return; 

            // Pastikan relasi penting di-load
            $fp->loadMissing(['details.produk', 'customer', 'suratJalan', 'pesananPenjualan']);

            $accurateService = app(AccurateService::class);

            // Mapping header
            $payload = [
                'number'       => $fp->kode,
                'transDate'    => $fp->tanggal ? \Carbon\Carbon::parse(str_replace('/', '-', $fp->tanggal))->format('d/m/Y') : date('d/m/Y'),
                'description'  => $fp->keterangan ?? '',
                'cashDiscount' => $fp->diskon_rupiah,
                'inclusiveTax' => $fp->is_include_ppn ? 'true' : 'false',
                'tax1Name'     => $fp->is_pkp ? 'PPN' : '',
            ];

            // Mapping Customer
            if ($fp->customer) {
                $payload['customerNo'] = $fp->customer->kode;
            }

            // MAPPING JALUR FAKTUR (3 in 1)
            if ($fp->jenis_transaksi == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_SJ) {
                // Jalur 1: Tarik dari Surat Jalan
                if ($fp->suratJalan && $fp->suratJalan->accurate_id) {
                    $payload['deliveryOrderId'] = $fp->suratJalan->accurate_id;
                }
            } elseif ($fp->jenis_transaksi == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_SO) {
                // Jalur 2: Tarik dari Pesanan Penjualan
                if ($fp->pesananPenjualan && $fp->pesananPenjualan->accurate_id) {
                    $payload['salesOrderId'] = $fp->pesananPenjualan->accurate_id;
                }
            }

            // Jika update
            if ($fp->accurate_id) {
                $payload['id'] = $fp->accurate_id;
            }

            // Mapping Detail Barang (Java Binding)
            $index = 0;
            foreach ($fp->details as $detail) {
                if ($detail->produk) {
                    $payload["detailItem[$index].itemNo"]    = $detail->produk->kode;
                    $payload["detailItem[$index].unitPrice"] = $detail->harga;
                    $payload["detailItem[$index].quantity"]  = $detail->qty;
                    $index++;
                }
            }

            // Tembak API save Sales Invoice
            $response = $accurateService->apiPost($perusahaan, '/sales-invoice/save.do', $payload);

            if ($response === null) {
                Log::error("BATAL PUSH FAKTUR: Fungsi apiPost mengembalikan nilai NULL.");
                return;
            }

            Log::info("Jawaban Server Accurate untuk Faktur Penjualan:", $response);

            if (isset($response['s']) && $response['s'] === true && !$fp->accurate_id) {
                $accurateId = $response['r']['id'] ?? ($response['d']['id'] ?? null);
                if ($accurateId) {
                    $fp->updateQuietly(['accurate_id' => $accurateId]); 
                    Log::info("SUKSES! Faktur Penjualan masuk dengan Accurate ID: " . $accurateId);
                }
            } else if (!isset($response['s']) || $response['s'] !== true) {
                 Log::error('DITOLAK ACCURATE! Alasan:', $response);
            }

        } catch (\Exception $e) {
            Log::error('GAGAL FATAL saat Push Faktur Penjualan ke Accurate: ' . $e->getMessage());
        }
    }
}