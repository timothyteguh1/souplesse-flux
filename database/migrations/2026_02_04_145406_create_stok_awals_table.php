<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Utilities\Constants\Const_Status;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stok_awals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cabang_id')->index()->constrained('cabangs');
            $table->string('kode')->index();
            $table->unique(['cabang_id', 'kode']);
            $table->foreignUuid('gudang_id')->index()->constrained('gudangs');
            $table->foreignUuid('produk_id')->index()->constrained('produks');
            $table->foreignUuid('satuan_id')->index()->constrained('satuans');
            $table->date('expired_date')->nullable();
            $table->string('no_batch')->nullable();
            $table->decimal('jumlah', 18, 4);
            $table->decimal('harga_satuan', 18, 4);
            $table->text('keterangan')->nullable();
            $table->string('status')->index()->default(Const_Status::AKTIF);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_awals');
    }
};
