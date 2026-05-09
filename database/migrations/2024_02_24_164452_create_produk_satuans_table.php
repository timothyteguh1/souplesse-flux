<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('produk_satuans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('produk_id')->index()->constrained('produks');
            $table->foreignUuid('satuan_id')->index()->constrained('satuans');
            $table->decimal('konversi', 18, 4);
            $table->decimal('harga_beli', 18, 4)->default(0);
            $table->decimal('harga_jual', 18, 4)->default(0);
            $table->string('barcode')->nullable();
            $table->boolean('is_satuan_dasar')->default(false);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk_satuans');
    }
};
