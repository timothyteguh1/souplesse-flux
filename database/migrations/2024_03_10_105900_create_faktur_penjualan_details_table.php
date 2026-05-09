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
        Schema::create('faktur_penjualan_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('faktur_penjualan_id')->index()->constrained('faktur_penjualans');
            $table->foreignUuid('pesanan_penjualan_detail_id')->index()->nullable()->constrained('pesanan_penjualan_details');
            $table->foreignUuid('surat_jalan_detail_id')->index()->nullable()->constrained('surat_jalan_details');
            $table->foreignUuid('produk_id')->index()->constrained('produks');
            $table->foreignUuid('satuan_id')->index()->constrained('satuans');
            $table->decimal('jumlah', 18, 4);
            $table->decimal('harga_satuan', 18, 4);
            $table->string('diskon_satuan_type_1')->nullable();
            $table->decimal('diskon_satuan_1', 18, 4)->default(0);
            $table->string('diskon_satuan_type_2')->nullable();
            $table->decimal('diskon_satuan_2', 18, 4)->default(0);
            $table->string('diskon_satuan_type_3')->nullable();
            $table->decimal('diskon_satuan_3', 18, 4)->default(0);
            $table->string('diskon_satuan_type_4')->nullable();
            $table->decimal('diskon_satuan_4', 18, 4)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faktur_penjualan_details');
    }
};
