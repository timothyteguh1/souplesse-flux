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
        Schema::create('faktur_penjualans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cabang_id')->index()->constrained('cabangs');
            $table->string('jenis_transaksi')->nullable();
            $table->string('kode')->index();
            $table->unique(['cabang_id', 'kode']);
            $table->datetime('tanggal');
            $table->datetime('tanggal_jatuh_tempo')->nullable();
            $table->foreignUuid('surat_jalan_id')->index()->nullable()->constrained('surat_jalans');
            $table->foreignUuid('pesanan_penjualan_id')->index()->nullable()->constrained('pesanan_penjualans');
            $table->foreignUuid('customer_id')->index()->constrained('customers');
            $table->foreignUuid('gudang_id')->index()->nullable()->constrained('gudangs');
            $table->boolean('is_pkp')->default(true);
            $table->boolean('is_include_ppn')->default(true);
            $table->decimal('ppn_percent', 18, 4)->default(0);
            $table->string('diskon_type')->nullable();
            $table->decimal('diskon', 18, 4)->default(0);
            $table->text('batal_alasan')->nullable();
            $table->foreignUuid('batal_user_id')->index()->nullable()->constrained('users');
            $table->datetime('batal_at')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status')->index()->default(Const_Status::FAKTUR_PENJUALAN_BELUM_LUNAS);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faktur_penjualans');
    }
};
