<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('customers', 'accurate_id')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('accurate_id')->nullable();
                $table->string('accurate_no')->nullable();
                $table->timestamp('accurate_synced_at')->nullable();
            });
        }

        if (!Schema::hasColumn('produks', 'accurate_id')) {
            Schema::table('produks', function (Blueprint $table) {
                $table->string('accurate_id')->nullable();
                $table->string('accurate_no')->nullable();
                $table->timestamp('accurate_synced_at')->nullable();
            });
        }

        if (!Schema::hasColumn('faktur_penjualans', 'accurate_id')) {
            Schema::table('faktur_penjualans', function (Blueprint $table) {
                $table->string('accurate_id')->nullable();
                $table->string('accurate_no')->nullable();
                $table->timestamp('accurate_synced_at')->nullable();
                $table->text('accurate_sync_error')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('customers', 'accurate_id')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn(['accurate_id', 'accurate_no', 'accurate_synced_at']);
            });
        }

        if (Schema::hasColumn('produks', 'accurate_id')) {
            Schema::table('produks', function (Blueprint $table) {
                $table->dropColumn(['accurate_id', 'accurate_no', 'accurate_synced_at']);
            });
        }

        if (Schema::hasColumn('faktur_penjualans', 'accurate_id')) {
            Schema::table('faktur_penjualans', function (Blueprint $table) {
                $table->dropColumn(['accurate_id', 'accurate_no', 'accurate_synced_at', 'accurate_sync_error']);
            });
        }
    }
};