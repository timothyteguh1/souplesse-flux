<?php

use App\Utilities\Constants\Const_Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cabang_id')->index()->constrained('cabangs');
            $table->string('kode')->index();
            $table->unique(['cabang_id', 'kode']);
            $table->string('nama');
            $table->foreignUuid('jenis_produk_id')->index()->nullable()->constrained('jenis_produks'); //
            $table->foreignUuid('kategori_produk_id')->index()->nullable()->constrained('kategori_produks');
            $table->foreignUuid('model_produk_id')->index()->nullable()->constrained('model_produks');
            $table->foreignUuid('satuan_id')->index()->nullable()->constrained('satuans');
            $table->foreignUuid('default_satuan_beli_id')->index()->nullable()->constrained('satuans');
            $table->foreignUuid('default_satuan_jual_id')->index()->nullable()->constrained('satuans');
            $table->boolean('is_have_expired_date')->default(false);
            $table->decimal('minimal_order', 18, 4)->default(0);
            $table->decimal('harga_beli', 18, 4)->default(0);
            $table->decimal('harga_jual', 18, 4)->default(0);
            $table->decimal('stok_minimum', 18, 4)->default(0);
            $table->text('deskripsi')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status')->index()->default(Const_Status::AKTIF);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
