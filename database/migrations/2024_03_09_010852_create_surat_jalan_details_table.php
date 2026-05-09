<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surat_jalan_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('surat_jalan_id')->index()->constrained('surat_jalans');
            $table->foreignUuid('pesanan_penjualan_detail_id')->index()->nullable()->constrained('pesanan_penjualan_details');
            $table->foreignUuid('produk_id')->index()->constrained('produks');
            $table->foreignUuid('satuan_id')->index()->constrained('satuans');
            $table->decimal('jumlah', 18, 4);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_jalan_details');
    }
};
