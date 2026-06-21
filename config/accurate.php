<?php

return [
    // Dari .env — satu client untuk seluruh app
    'client_id'     => env('ACCURATE_CLIENT_ID', ''),
    'client_secret' => env('ACCURATE_CLIENT_SECRET', ''),
    'redirect_uri'  => env('ACCURATE_REDIRECT_URI', ''),

    // Base URL untuk OAuth & API dasar (db-list, dll)
    'base_url'  => env('ACCURATE_BASE_URL', 'https://account.accurate.id'),
    'api_url'   => env('ACCURATE_API_URL', 'https://account.accurate.id/api'),

    // OAuth endpoints
    'auth_url'  => env('ACCURATE_BASE_URL', 'https://account.accurate.id') . '/oauth/authorize',
    'token_url' => env('ACCURATE_BASE_URL', 'https://account.accurate.id') . '/oauth/token',
];