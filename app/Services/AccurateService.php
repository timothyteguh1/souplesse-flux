<?php

namespace App\Services;

use App\Models\Master\Perusahaan;
use App\Services\Accurate\AccurateTokenService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AccurateService
{
    protected string $baseUrl;

    public function __construct(protected AccurateTokenService $tokenService)
    {
        $this->baseUrl = config('accurate.base_url', 'https://account.accurate.id');
    }

    public function getDatabaseList(Perusahaan $perusahaan): array
    {
        $token = $this->tokenService->getValidToken($perusahaan);
        if (!$token) return [];

        $response = Http::withToken($token)->get("{$this->baseUrl}/api/db-list.do");
        if (!$response->successful()) return [];
        
        return $response->json('d') ?? [];
    }

    public function selectDatabase(Perusahaan $perusahaan, string $dbId, string $dbAlias): bool
    {
        $token = $this->tokenService->getValidToken($perusahaan);
        if (!$token) return false;

        $response = Http::withToken($token)->get("{$this->baseUrl}/api/open-db.do", ['id' => $dbId]);
        if (!$response->successful()) return false;

        $data = $response->json();
        $host = str_replace(['https://', 'http://'], '', $data['host'] ?? '');

        $perusahaan->update([
            'accurate_db_id'    => $data['session'] ?? $dbId, 
            'accurate_db_alias' => $dbAlias,
            'accurate_host'     => $host,
        ]);

        return true;
    }

    // ==========================================
    // API HELPER (BUG FIXED)
    // ==========================================

    public function apiGet(Perusahaan $perusahaan, string $endpoint, array $params = []): ?array
    {
        $token = $this->tokenService->getValidToken($perusahaan);
        if (!$token || !$perusahaan->accurate_host) return null;

        // FIXED: Menambahkan sisipan '/accurate/api' pada URL
        $url = "https://{$perusahaan->accurate_host}/accurate/api{$endpoint}";

        $response = Http::withToken($token)
            ->withHeaders(['X-Session-ID' => $perusahaan->accurate_db_id])
            ->get($url, $params);

        if (!$response->successful()) {
            Log::error("Accurate API GET {$endpoint} error", [
                'status' => $response->status(), 
                'body' => $response->body()
            ]);
            return null;
        }

        return $response->json();
    }

    public function apiPost(Perusahaan $perusahaan, string $endpoint, array $data = []): ?array
    {
        $token = $this->tokenService->getValidToken($perusahaan);
        if (!$token || !$perusahaan->accurate_host) return null;

        $url = "https://{$perusahaan->accurate_host}/accurate/api{$endpoint}";

        // Tambahkan ->timeout(60) agar PHP lebih sabar menunggu respon Accurate
        $response = Http::withToken($token)
            ->withHeaders(['X-Session-ID' => $perusahaan->accurate_db_id])
            ->timeout(60) // <--- TAMBAHAN INI
            ->asForm()
            ->post($url, $data);

        if (!$response->successful()) {
            Log::error("Accurate API POST {$endpoint} error", [
                'status' => $response->status(), 
                'body' => $response->body()
            ]);
            return null;
        }

        return $response->json();
    }

    // ==========================================
    // SYNC DATA MASTER
    // ==========================================

    public function getItems(Perusahaan $perusahaan): array|false
    {
        $response = $this->apiGet($perusahaan, '/item/list.do', [
            // KITA TAMBAHKAN unitPrice, vendorPrice, dan quantity
            'fields' => 'id,no,name,itemCategory,unit1Name,unitPrice,vendorPrice,quantity'
        ]);

        if (isset($response['s']) && $response['s'] === true) {
            return $response['d']; 
        }

        return false;
    }
}