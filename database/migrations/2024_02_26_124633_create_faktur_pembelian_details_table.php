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
        Schema::create('faktur_pembelian_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('faktur_pembelian_id')->index()->constrained('faktur_pembelians');
            $table->foreignUuid('pesanan_pembelian_id')->index()->nullable()->constrained('pesanan_pembelians');
            $table->foreignUuid('pesanan_pembelian_detail_id')->index()->nullable()->constrained('pesanan_pembelian_details');
            $table->foreignUuid('produk_id')->index()->constrained('produks');
            $table->foreignUuid('satuan_id')->index()->constrained('satuans');
            $table->date('expired_date')->nullable();
            $table->string('no_batch')->nullable();
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
        Schema::dropIfExists('faktur_pembelian_details');
    }
};
