<?php

use App\Utilities\Constants\Const_Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('faktur_pembelians', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cabang_id')->index()->constrained('cabangs');
            $table->string('jenis_transaksi')->nullable();
            $table->string('kode')->index();
            $table->unique(['cabang_id', 'kode']);
            $table->datetime('tanggal');
            $table->datetime('tanggal_jatuh_tempo');
            $table->foreignUuid('supplier_id')->index()->constrained('suppliers');
            $table->foreignUuid('gudang_id')->index()->constrained('gudangs');
            $table->boolean('is_pkp')->default(true);
            $table->boolean('is_include_ppn')->default(true);
            $table->decimal('ppn_percent', 18, 4)->default(0);
            $table->string('diskon_type')->nullable();
            $table->decimal('diskon', 18, 4)->default(0);
            $table->string('nsfp')->nullable();
            $table->date('tanggal_faktur_pajak')->nullable();
            $table->string('bukti_potong')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status')->index()->default(Const_Status::FAKTUR_PEMBELIAN_BELUM_LUNAS);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faktur_pembelians');
    }
};
