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
        Schema::create('pesanan_pembelians', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cabang_id')->index()->constrained('cabangs');
            $table->string('kode')->index();
            $table->unique(['cabang_id', 'kode']);
            $table->datetime('tanggal');
            $table->foreignUuid('supplier_id')->index()->constrained('suppliers');
            $table->boolean('is_pkp')->default(true);
            $table->boolean('is_include_ppn')->default(true);
            $table->decimal('ppn_percent', 18, 4);
            $table->decimal('pembulatan_rupiah', 18, 4)->nullable();
            $table->string('diskon_type')->nullable();
            $table->decimal('diskon', 18, 4)->default(0);
            $table->text('keterangan')->nullable();
            $table->string('status')->index()->default(Const_Status::PESANAN_PEMBELIAN_MENUNGGU_PERSETUJUAN);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_pembelians');
    }
};
