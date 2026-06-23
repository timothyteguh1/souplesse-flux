<?php

namespace App\Services\Pembelian;

use App\Models\Master\Supplier;
use App\Exceptions\GeneralException;
use App\Models\System\MutasiTransaksi;
use App\Utilities\Constants\Const_Umum;
use App\Models\Pembelian\ReturPembelian;
use App\Utilities\Constants\Const_Status;
use App\Models\Pembelian\ReturPembelianDetail;
use App\Services\System\MutasiTransaksiService;
use App\Utilities\Functions\TransactionFunction;

// --- TAMBAHAN NAMESPACE UNTUK INTEGRASI ACCURATE ---
use App\Models\Master\Perusahaan;
use App\Services\AccurateService;
use Illuminate\Support\Facades\Log;

class ReturPembelianService
{
    public static function create(array $data = []): ReturPembelian
    {
        if (!ReturPembelian::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }

        $obj = ReturPembelian::create($data);

        // detail
        foreach ($data['items'] as $item) {
            ReturPembelianDetailService::create($obj, $item);
        }

        MutasiTransaksiService::increase(
            $obj->tanggal,
            $obj->cabang_id,
            Const_Umum::JENIS_MUTASI_TRANSAKSI_PIUTANG,
            Supplier::class,
            $obj->supplier_id,
            ReturPembelian::class,
            $obj->id,
            ReturPembelian::class,
            $obj->id,
            Const_Umum::JENIS_TRANSAKSI_RETUR_PEMBELIAN,
            $obj->grandtotal,
            'Retur Pembelian: [' . $obj->kode . ']',
        );

        // --- TRIGGER PUSH RETUR KE ACCURATE ---
        self::pushToAccurate($obj);

        return $obj;
    }

    public static function update(ReturPembelian $obj, array $data = []): bool
    {
        if (!$obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        MutasiTransaksiService::destroy($obj->mutasiTransaksi);

        $obj->update($data);

        self::updateDetail($obj, $data['items']);

        //ubah status baru
        $obj->refresh();

        MutasiTransaksiService::increase(
            $obj->tanggal,
            $obj->cabang_id,
            Const_Umum::JENIS_MUTASI_TRANSAKSI_PIUTANG,
            Supplier::class,
            $obj->supplier_id,
            ReturPembelian::class,
            $obj->id,
            ReturPembelian::class,
            $obj->id,
            Const_Umum::JENIS_TRANSAKSI_RETUR_PEMBELIAN,
            $obj->grandtotal,
            'Retur Pembelian: [' . $obj->kode . ']',
        );

        // --- TRIGGER PUSH RETUR KE ACCURATE ---
        self::pushToAccurate($obj);

        return true;
    }

    public static function updateStatus(MutasiTransaksi $mutasiTransaksi): bool
    {
        $obj = ReturPembelian::find($mutasiTransaksi->reference_id);
        $terbayar = TransactionFunction::getSisaPiutang($mutasiTransaksi->id, null);

        if ($obj->grandtotal == $terbayar && $obj->status != Const_Status::RETUR_PEMBELIAN_LUNAS) {
            $obj->status = Const_Status::RETUR_PEMBELIAN_LUNAS;

            return $obj->save();
        } elseif ($obj->grandtotal != $terbayar && $obj->status == Const_Status::RETUR_PEMBELIAN_LUNAS) {
            $obj->status = Const_Status::RETUR_PEMBELIAN_BELUM_LUNAS;

            return $obj->save();
        }

        return true;
    }

    public static function updateDetail(ReturPembelian $obj, array $data = []): bool
    {
        $collects = collect([
            $data,
        ]);

        $updatedIds = $collects->pluck('*.id')->flatten()->whereNotNull();

        $dataLamas = $obj->details()->whereNotIn('id', $updatedIds)->get();

        foreach ($dataLamas as $dataLama) {
            ReturPembelianDetailService::destroy($dataLama);
        }

        // insert data baru
        foreach ($data as $item) {
            $returPembelianDetail = ReturPembelianDetail::find($item['id']);
            if ($returPembelianDetail) {
                ReturPembelianDetailService::update($returPembelianDetail, $item);
            } else {
                ReturPembelianDetailService::create($obj, $item);
            }
        }

        return true;
    }

    public static function destroy(ReturPembelian $obj): bool
    {
        if (!$obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        if ($obj->status == Const_Status::PIUTANG_LUNAS) {
            throw new GeneralException('Tidak dapat menghapus item yang sudah Lunas. Jika tetap ingin menghapus, mohon untuk menghapus terlebih dahulu data pelunasan terkait item ini.');
        }

        MutasiTransaksiService::destroy($obj->mutasiTransaksi);

        foreach ($obj->details as $detail) {
            ReturPembelianDetailService::destroy($detail);
        }

        return $obj->delete();
    }

    // ========================================================
    // LOGIKA PUSH RETUR PEMBELIAN KE ACCURATE (TIPE: NO_INVOICE)
    // ========================================================
    protected static function pushToAccurate(ReturPembelian $retur): void
    {
        try {
            Log::info("=== MULAI PUSH RETUR PEMBELIAN '{$retur->kode}' KE ACCURATE (TIPE: NO_INVOICE) ===");

            $perusahaan = Perusahaan::whereNotNull('accurate_host')->first();
            if (!$perusahaan) return;

            $retur->loadMissing(['details.pesananPembelianDetail.pesananPembelian', 'details.pesananPembelianDetail.produk', 'supplier']);
            $accurateService = app(AccurateService::class);

            $tanggalFormat = $retur->tanggal ? \Carbon\Carbon::parse(str_replace('/', '-', $retur->tanggal))->format('d/m/Y') : date('d/m/Y');

            $payload = [
                'number'       => $retur->kode,
                'transDate'    => $tanggalFormat,
                'description'  => $retur->keterangan ?? 'Retur otomatis dari sistem lokal (Tanpa Referensi Dokumen)',
                'taxDate'      => $tanggalFormat,
                
                // --- PERBAIKAN FINAL SESUAI DOKUMENTASI API ---
                'returnType'   => 'NO_INVOICE', 
            ];

            // Masukkan data Vendor
            if ($retur->supplier) {
                $payload['vendorNo'] = $retur->supplier->kode;
            }

            $index = 0;
            foreach ($retur->details as $detail) {
                if ($detail->pesananPembelianDetail && $detail->pesananPembelianDetail->produk) {
                    $payload["detailItem[$index].itemNo"]    = $detail->pesananPembelianDetail->produk->kode;
                    $payload["detailItem[$index].unitPrice"] = $detail->dpp_satuan ?? 0;
                    $payload["detailItem[$index].quantity"]  = $detail->jumlah;
                    
                    // Kita tidak mengirimkan receiveItemNumber karena tipenya NO_INVOICE
                    
                    $index++;
                }
            }

            Log::info("=== DEBUG PAYLOAD FINAL RETUR PEMBELIAN '{$retur->kode}' ===");
            Log::info(json_encode($payload, JSON_PRETTY_PRINT));

            $response = $accurateService->apiPost($perusahaan, '/purchase-return/save.do', $payload);

            if ($response === null) {
                Log::error("BATAL PUSH RETUR PEMBELIAN: Fungsi apiPost mengembalikan nilai NULL.");
                return;
            }

            Log::info("Jawaban Server Accurate untuk Retur Pembelian:", $response);

            if (isset($response['s']) && $response['s'] === true) {
                Log::info("SUKSES! Retur Pembelian berhasil dibuat di Accurate. Stok otomatis dikurangi.");
            } else {
                Log::error('DITOLAK ACCURATE (Retur Pembelian)! Alasan:', $response);
            }

        } catch (\Exception $e) {
            Log::error('GAGAL FATAL saat Push Retur Pembelian ke Accurate: ' . $e->getMessage());
        }
    }
}