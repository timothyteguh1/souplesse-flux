<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            // Menambahkan kolom accurate_id (boleh kosong/nullable) setelah kolom id
            $table->string('accurate_id')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            // Menghapus kolom jika database di-rollback
            $table->dropColumn('accurate_id');
        });
    }
};