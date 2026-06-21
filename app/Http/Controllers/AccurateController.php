<?php

namespace App\Http\Controllers;

use App\Models\Master\Perusahaan;
use App\Services\Accurate\AccurateTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccurateController extends Controller
{
    public function __construct(protected AccurateTokenService $tokenService) {}

    public function connect(Perusahaan $perusahaan)
    {
        $url = $this->tokenService->getAuthorizationUrl($perusahaan->id);
        return redirect($url);
    }

    public function callback(Request $request)
    {
        if ($request->has('error')) {
            Log::warning('Accurate OAuth Error/Cancelled', $request->all());
            return redirect()->route('admin.system.accurate.index')
                ->with('error', 'Koneksi Accurate dibatalkan.');
        }

        $code = $request->get('code');
        $perusahaanId = $request->get('state'); 
        
        $perusahaan = Perusahaan::find($perusahaanId);

        if (!$perusahaan) {
            return redirect()->route('admin.system.accurate.index')
                ->with('error', 'Perusahaan tidak ditemukan saat proses otentikasi.');
        }

        $success = $this->tokenService->exchangeCodeForToken($code, $perusahaan);

        if (!$success) {
            Log::error('Accurate Token Exchange Failed', ['code' => $code, 'perusahaan_id' => $perusahaanId]);
            return redirect()->route('admin.system.accurate.index')
                ->with('error', 'Gagal mendapatkan token dari Accurate.');
        }

        return redirect()->route('admin.system.accurate.index')
            ->with('success', 'Berhasil terhubung ke Accurate! Silakan pilih database.');
    }
}