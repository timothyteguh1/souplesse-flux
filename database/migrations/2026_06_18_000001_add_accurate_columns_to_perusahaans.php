<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perusahaans', function (Blueprint $table) {
            $table->text('accurate_access_token')->nullable();
            $table->text('accurate_refresh_token')->nullable();
            $table->timestamp('accurate_token_expires_at')->nullable();
            $table->string('accurate_db_id')->nullable();
            $table->string('accurate_db_alias')->nullable();
            $table->string('accurate_host')->nullable();
            $table->boolean('accurate_sync_customer')->default(false);
            $table->boolean('accurate_sync_produk')->default(false);
            $table->boolean('accurate_sync_faktur')->default(false);
            $table->timestamp('accurate_last_sync_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('perusahaans', function (Blueprint $table) {
            $table->dropColumn([
                'accurate_access_token',
                'accurate_refresh_token',
                'accurate_token_expires_at',
                'accurate_db_id',
                'accurate_db_alias',
                'accurate_host',
                'accurate_sync_customer',
                'accurate_sync_produk',
                'accurate_sync_faktur',
                'accurate_last_sync_at',
            ]);
        });
    }
};