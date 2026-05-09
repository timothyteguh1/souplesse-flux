<?php

namespace App\Utilities\Functions;

use App\Models\Master\Produk;
use App\Models\System\MutasiStok;
use App\Utilities\Constants\Const_Status;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InventoryFunction
{
    public static function getHpp($cabang_id, $produk_id, $tanggal = null)
    {
        $data = MutasiStok::selectRaw('sum(jumlah*harga) / sum(jumlah) AS harga')
            ->where('cabang_id', $cabang_id)
            ->where('produk_id', $produk_id)
            ->when($tanggal, function ($query) use ($tanggal) {
                return $query->where('tanggal', '<=', $tanggal);
            })
            ->first();

        return _round(optional($data)->harga) ?? 0;
    }

    public static function getStok($cabang_id, $produk_id, $gudang_id = null, ?Carbon $tanggal = null, $is_tanggal_inclusive = true, $is_date = false, $expired_date = null, $no_batch = null)
    {
        $total = MutasiStok::query()
            ->where('cabang_id', $cabang_id)
            ->where('produk_id', $produk_id)
            ->where('status', Const_Status::AKTIF)
            ->when($gudang_id, function ($query) use ($gudang_id) {
                return $query->where('gudang_id', $gudang_id);
            })
            ->when($expired_date, function ($query) use ($expired_date) {
                return $query->whereDate('expired_date', $expired_date);
            })
            ->when($no_batch, function ($query) use ($no_batch) {
                return $query->where('no_batch', $no_batch);
            })
            ->when($tanggal, function ($query) use ($tanggal, $is_tanggal_inclusive, $is_date) {
                if ($is_date) {
                    return $query->whereDate('tanggal', $is_tanggal_inclusive ? '<=' : '<', $tanggal);
                }

                return $query->where('tanggal', $is_tanggal_inclusive ? '<=' : '<', $tanggal);
            })
            ->sum('jumlah');

        return _round($total);
    }

    public static function getStoks($cabang_id, array $produk_ids, $gudang_id = null, ?Carbon $tanggal = null, $is_tanggal_inclusive = true, $is_date = false, $expired_date = null, $no_batch = null)
    {
        $objs = MutasiStok::query()
            ->where('cabang_id', $cabang_id)
            ->whereIn('produk_id', $produk_ids)
            ->where('status', Const_Status::AKTIF)
            ->when($gudang_id, function ($query) use ($gudang_id) {
                return $query->where('gudang_id', $gudang_id);
            })
            ->when($expired_date, function ($query) use ($expired_date) {
                return $query->whereDate('expired_date', $expired_date);
            })
            ->when($no_batch, function ($query) use ($no_batch) {
                return $query->where('no_batch', $no_batch);
            })
            ->when($tanggal, function ($query) use ($tanggal, $is_tanggal_inclusive, $is_date) {
                if ($is_date) {
                    return $query->whereDate('tanggal', $is_tanggal_inclusive ? '<=' : '<', $tanggal);
                }

                return $query->where('tanggal', $is_tanggal_inclusive ? '<=' : '<', $tanggal);
            })
            ->selectRaw('produk_id, SUM(jumlah) as total_jumlah')
            ->groupBy('produk_id')
            ->get()
            ->pluck('total_jumlah', 'produk_id');

        return $objs;
    }

    public static function getStokGroupByExpiredDate($cabang_id, $produk_id, $satuan_id, $gudang_id = null, ?Carbon $tanggal = null, $is_tanggal_inclusive = true, $is_date = false)
    {
        $results = MutasiStok::query()
            ->select('cabang_id', 'produk_id', 'gudang_id', 'satuan_id', 'expired_date', DB::raw('SUM(jumlah) as total_jumlah'))
            ->where('cabang_id', $cabang_id)
            ->where('produk_id', $produk_id)
            ->where('status', Const_Status::AKTIF)
            ->when($gudang_id, function ($query) use ($gudang_id) {
                return $query->where('gudang_id', $gudang_id);
            })
            ->when($tanggal, function ($query) use ($tanggal, $is_tanggal_inclusive, $is_date) {
                if ($is_date) {
                    return $query->whereDate('tanggal', $is_tanggal_inclusive ? '<=' : '<', $tanggal);
                }

                return $query->where('tanggal', $is_tanggal_inclusive ? '<=' : '<', $tanggal);
            })
            ->groupBy('cabang_id', 'produk_id', 'gudang_id', 'satuan_id', 'expired_date')
            ->orderByRaw('expired_date IS NULL')
            ->orderBy('expired_date')
            ->get();

        for ($i = 0; $i < count($results); $i++) {
            $results[$i]->total_jumlah = self::getStokSatuan($produk_id, $satuan_id, $results[$i]->total_jumlah);
        }

        return $results;
    }

    public static function getStokGroupByNoBatch($cabang_id, $produk_id, $satuan_id, $gudang_id = null, $expired_date = null, ?Carbon $tanggal = null, $is_tanggal_inclusive = true, $is_date = false)
    {
        $results = MutasiStok::query()
            ->select('cabang_id', 'produk_id', 'gudang_id', 'satuan_id', 'expired_date', 'no_batch', DB::raw('SUM(jumlah) as total_jumlah'))
            ->where('cabang_id', $cabang_id)
            ->where('produk_id', $produk_id)
            ->where('status', Const_Status::AKTIF)
            ->when($expired_date, function ($query, $expired_date) {
                return $query->whereDate('expired_date', _date_format_db($expired_date));
            }, function ($query) {
                return $query->whereNull('expired_date');
            })
            ->when($gudang_id, function ($query) use ($gudang_id) {
                return $query->where('gudang_id', $gudang_id);
            })
            ->when($tanggal, function ($query) use ($tanggal, $is_tanggal_inclusive, $is_date) {
                if ($is_date) {
                    return $query->whereDate('tanggal', $is_tanggal_inclusive ? '<=' : '<', $tanggal);
                }

                return $query->where('tanggal', $is_tanggal_inclusive ? '<=' : '<', $tanggal);
            })
            ->groupBy('cabang_id', 'produk_id', 'gudang_id', 'satuan_id', 'expired_date', 'no_batch')
            ->orderByRaw('expired_date IS NULL')
            ->orderBy('no_batch')
            ->get();
        for ($i = 0; $i < count($results); $i++) {
            $results[$i]->total_jumlah = self::getStokSatuan($produk_id, $satuan_id, $results[$i]->total_jumlah);
        }

        return $results;
    }

    public static function getStokMutasi($cabang_id, $produk_id, $gudang_id, $tanggal_awal, $tanggal_akhir, $is_tanggal_awal_inclusive = true, $is_tanggal_akhir_inclusive = true, $is_date = true)
    {
        $total = MutasiStok::query()
            ->where('cabang_id', $cabang_id)
            ->where('produk_id', $produk_id)
            ->where('gudang_id', $gudang_id)
            ->where('status', Const_Status::AKTIF)
            ->when($tanggal_awal, function ($query) use ($tanggal_awal, $is_tanggal_awal_inclusive, $is_date) {
                $tanggal_awal = _datetime_carbon_db($tanggal_awal);

                if ($is_date) {
                    return $query->whereDate('tanggal', $is_tanggal_awal_inclusive ? '>=' : '>', $tanggal_awal);
                }

                return $query->where('tanggal', $is_tanggal_awal_inclusive ? '>=' : '>', $tanggal_awal);
            })
            ->when($tanggal_akhir, function ($query) use ($tanggal_akhir, $is_tanggal_akhir_inclusive, $is_date) {
                $tanggal_akhir = _datetime_carbon_db($tanggal_akhir);

                if ($is_date) {
                    return $query->whereDate('tanggal', $is_tanggal_akhir_inclusive ? '<=' : '<', $tanggal_akhir);
                }

                return $query->where('tanggal', $is_tanggal_akhir_inclusive ? '<=' : '<', $tanggal_akhir);
            })
            ->sum('jumlah');

        return _round($total);
    }

    public static function getStokMultiSatuan($produk_id, $total_satuan_dasar)
    {
        $produk = Produk::findOrFail($produk_id);

        // urutkan satuan produk berdasarkan konversi, lalu bagi sampai habis
        $produkSatuans = $produk->produkSatuans()
            ->with(['satuan'])
            ->reorder()
            ->orderBy('konversi', 'desc')
            ->get();

        $result = [];
        foreach ($produkSatuans as $produkSatuan) {
            $konversi = $produkSatuan->konversi;
            $jumlah = floor($total_satuan_dasar / $konversi);
            $total_satuan_dasar = $total_satuan_dasar % $konversi;

            if ($jumlah > 0) {
                $result[] = [
                    'satuan_id' => $produkSatuan->satuan_id,
                    'satuan_kode' => $produkSatuan->satuan->kode,
                    'satuan_nama' => $produkSatuan->satuan->nama,
                    'jumlah' => $jumlah,
                ];
            }
        }

        return $result;
    }

    public static function getStokMultiSatuanAsString($produk_id, $total_satuan_dasar)
    {
        $result = self::getStokMultiSatuan($produk_id, $total_satuan_dasar);

        $result = collect($result)
            ->map(function ($item) {
                return sprintf(
                    '%s %s',
                    _number($item['jumlah']),
                    $item['satuan_nama'],
                );
            })
            ->implode(', ');

        return $result;
    }

    public static function getStokSatuan($produk_id, $satuan_id, $total_satuan_dasar)
    {
        $produk = Produk::findOrFail($produk_id);

        $produkSatuan = $produk
            ->where('satuan_id', $satuan_id)
            ->first();

        $konversi = $produkSatuan?->konversi ?? 0;
        $jumlah = $konversi == 0 ? 0 : floor($total_satuan_dasar / $konversi);

        return $jumlah;
    }

    public static function getHargaBeliTertinggi($cabang_id, $produk_id)
    {
        $result = DB::table("faktur_pembelians AS A")
            ->join(
                "faktur_pembelian_details AS B",
                "A.id",
                "=",
                "B.faktur_pembelian_id",
            )
            ->where("A.cabang_id", $cabang_id)
            ->where("B.produk_id", $produk_id)
            ->selectRaw("MAX(B.harga_satuan) AS harga_tertinggi")
            ->value("harga_tertinggi");

        return $result;
    }
}
