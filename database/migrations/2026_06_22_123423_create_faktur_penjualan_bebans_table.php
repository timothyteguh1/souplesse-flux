<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('faktur_penjualan_bebans', function (Blueprint $table) {
            // Menggunakan UUID karena tabel faktur_penjualans milikmu menggunakan UUID
            $table->uuid('id')->primary();
            
            // Relasi ke tabel faktur_penjualans
            $table->foreignUuid('faktur_penjualan_id')->constrained('faktur_penjualans')->cascadeOnDelete();
            
            // Nama beban/biaya (contoh: Ongkos Kirim, Biaya Asuransi, dll)
            $table->string('nama')->nullable();
            
            // Jumlah nominal beban
            $table->double('jumlah')->default(0);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faktur_penjualan_bebans');
    }
};
