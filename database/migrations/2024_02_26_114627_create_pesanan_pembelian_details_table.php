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
        Schema::create('pesanan_pembelian_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pesanan_pembelian_id')->index()->constrained('pesanan_pembelians');
            $table->foreignUuid('produk_id')->index()->constrained('produks');
            $table->foreignUuid('satuan_id')->index()->constrained('satuans');
            $table->decimal('jumlah', 18, 4);
            $table->decimal('harga_satuan', 18, 4);
            $table->string('diskon_satuan_type')->nullable();
            $table->decimal('diskon_satuan', 18, 4)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_pembelian_details');
    }
};
