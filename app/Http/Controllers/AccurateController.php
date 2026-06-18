<?php

namespace App\Http\Controllers;

use App\Services\AccurateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccurateController extends Controller
{
    public function __construct(protected AccurateService $accurateService) {}

    public function connect()
    {
        $url = $this->accurateService->getAuthorizationUrl();
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
        $success = $this->accurateService->exchangeCodeForToken($code);

        if (!$success) {
            Log::error('Accurate Token Exchange Failed', ['code' => $code]);
            return redirect()->route('admin.system.accurate.index')
                ->with('error', 'Gagal mendapatkan token dari Accurate.');
        }

        return redirect()->route('admin.system.accurate.index')
            ->with('success', 'Berhasil terhubung ke Accurate! Silakan pilih database.');
    }
}