<?php

namespace App\Services;

use App\Models\AccurateToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AccurateService
{
    protected string $clientId;
    protected string $clientSecret;
    protected string $redirectUri;
    protected string $baseUrl;

    public function __construct()
    {
        $this->clientId     = config('services.accurate.client_id');
        $this->clientSecret = config('services.accurate.client_secret');
        $this->redirectUri  = config('services.accurate.redirect_uri');
        $this->baseUrl      = config('services.accurate.base_url');
    }

    // ==========================================
    // OAUTH
    // ==========================================

    public function getAuthorizationUrl(): string
    {
        $params = http_build_query([
            'client_id'     => $this->clientId,
            'response_type' => 'code',
            'redirect_uri'  => $this->redirectUri,
            'scope'         => 'item_view item_save customer_view customer_save vendor_view vendor_save sales_invoice_view sales_invoice_save purchase_order_view purchase_order_save',
        ]);

        return "{$this->baseUrl}/oauth/authorize?{$params}";
    }

    public function exchangeCodeForToken(string $code): bool
    {
        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->post("{$this->baseUrl}/oauth/token", [
                'code'         => $code,
                'grant_type'   => 'authorization_code',
                'redirect_uri' => $this->redirectUri,
            ]);

        if (!$response->successful()) {
            Log::error('Accurate OAuth error', $response->json() ?? []);
            return false;
        }

        $data = $response->json();

        AccurateToken::updateOrCreate(
            ['id' => 1],
            [
                'access_token'  => $data['access_token'],
                'refresh_token' => $data['refresh_token'] ?? null,
                'token_type'    => $data['token_type'] ?? 'Bearer',
                'expires_in'    => $data['expires_in'] ?? null,
                'expires_at'    => now()->addSeconds($data['expires_in'] ?? 3600),
                'is_connected'  => false,
            ]
        );

        return true;
    }

    public function refreshToken(): bool
    {
        $token = AccurateToken::getInstance();
        if (!$token || !$token->refresh_token) return false;

        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->post("{$this->baseUrl}/oauth/token", [
                'grant_type'    => 'refresh_token',
                'refresh_token' => $token->refresh_token,
            ]);

        if (!$response->successful()) {
            Log::error('Accurate refresh token error', $response->json() ?? []);
            return false;
        }

        $data = $response->json();

        $token->update([
            'access_token'  => $data['access_token'],
            'refresh_token' => $data['refresh_token'] ?? $token->refresh_token,
            'expires_in'    => $data['expires_in'] ?? null,
            'expires_at'    => now()->addSeconds($data['expires_in'] ?? 3600),
        ]);

        return true;
    }

    // ==========================================
    // DATABASE ACCURATE
    // ==========================================

public function getDatabaseList(): array
{
    $token = $this->getValidToken();
    if (!$token) return [];

    $response = Http::withToken($token->access_token)
        ->get("{$this->baseUrl}/api/db-list.do");

    // temporary debug
    Log::info('Accurate db-list response', [
        'status' => $response->status(),
        'body'   => $response->body(),
    ]);

    if (!$response->successful()) {
        Log::error('Accurate get db list error', $response->json() ?? []);
        return [];
    }

    return $response->json('d') ?? [];
}

    public function selectDatabase(string $dbId, string $dbAlias): bool
    {
        $token = $this->getValidToken();
        if (!$token) return false;

        // 1. Wajib tembak open-db.do untuk mendapatkan SESSION ID dan HOST SERVER
        $response = Http::withToken($token->access_token)
            ->get("{$this->baseUrl}/api/open-db.do", [
                'id' => $dbId
            ]);

        if (!$response->successful()) {
            Log::error('Accurate open-db error', $response->json() ?? []);
            return false;
        }

        $data = $response->json();
        
        // Bersihkan tulisan https:// agar host murni (contoh: zeus.accurate.id)
        $host = str_replace(['https://', 'http://'], '', $data['host'] ?? '');

        // 2. Simpan SESSION dari Accurate ke kolom db_id, dan host ke db_host
        $token->update([
            'db_id'        => $data['session'] ?? $dbId, 
            'db_alias'     => $dbAlias,
            'db_host'      => $host,
            'is_connected' => true,
        ]);

        return true;
    }

    // ==========================================
    // API HELPER
    // ==========================================

    public function getValidToken(): ?AccurateToken
    {
        $token = AccurateToken::getInstance();
        if (!$token) return null;

        if ($token->isExpired()) {
            $refreshed = $this->refreshToken();
            if (!$refreshed) return null;
            $token->refresh();
        }

        return $token;
    }

    public function apiGet(string $endpoint, array $params = []): ?array
    {
        $token = $this->getValidToken();
        if (!$token || !$token->db_host) return null;

        $response = Http::withToken($token->access_token)
            ->withHeaders(['X-Session-ID' => $token->db_id])
            ->get("https://{$token->db_host}/api{$endpoint}", $params);

        if (!$response->successful()) {
            Log::error("Accurate API GET {$endpoint} error", $response->json() ?? []);
            return null;
        }

        return $response->json();
    }

    // ==========================================
    // SYNC DATA MASTER
    // ==========================================

    public function getItems(): array|false
    {
        // Accurate membutuhkan parameter 'fields' untuk menentukan kolom apa saja yang mau ditarik.
        // Kita tarik id, no (Kode Barang), name (Nama Barang), itemCategory, dan unit1Name (Satuan).
        $response = $this->apiGet('/item/list.do', [
            'fields' => 'id,no,name,itemCategory,unit1Name'
        ]);

        // API Accurate selalu mengembalikan array dengan key 's' (boolean success) dan 'd' (data array)
        if (isset($response['s']) && $response['s'] === true) {
            return $response['d']; 
        }

        Log::error('Accurate Get Items Failed', $response ?? []);
        return false;
    }

    public function apiPost(string $endpoint, array $data = []): ?array
    {
        $token = $this->getValidToken();
        if (!$token || !$token->db_host) return null;

        $response = Http::withToken($token->access_token)
            ->withHeaders(['X-Session-ID' => $token->db_id])
            ->asForm()
            ->post("https://{$token->db_host}/api{$endpoint}", $data);

        if (!$response->successful()) {
            Log::error("Accurate API POST {$endpoint} error", $response->json() ?? []);
            return null;
        }

        return $response->json();
    }

    public function disconnect(): void
    {
        AccurateToken::truncate();
    }
}