<?php

use Illuminate\Support\Facades\Schema;
use App\Utilities\Constants\Const_Status;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surat_jalans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cabang_id')->index()->constrained('cabangs');
            $table->string('jenis_transaksi')->nullable();
            $table->string('kode')->index();
            $table->unique(['cabang_id', 'kode']);
            $table->datetime('tanggal');
            $table->foreignUuid('pesanan_penjualan_id')->index()->constrained('pesanan_penjualans');
            $table->foreignUuid('customer_id')->index()->constrained('customers');
            $table->string('no_polisi')->nullable();
            $table->foreignUuid('gudang_id')->index()->constrained('gudangs');
            $table->text('keterangan')->nullable();
            $table->string('status')->index()->default(Const_Status::SURAT_JALAN_BELUM_SELESAI);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_jalans');
    }
};
