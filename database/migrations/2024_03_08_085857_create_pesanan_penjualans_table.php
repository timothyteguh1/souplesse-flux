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
        Schema::create('pesanan_penjualans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cabang_id')->index()->constrained('cabangs');
            $table->string('jenis_transaksi')->nullable();
            $table->string('kode')->index();
            $table->unique(['cabang_id', 'kode']);
            $table->datetime('tanggal');
            $table->foreignUuid('customer_id')->index()->constrained('customers');
            $table->boolean('is_pkp')->default(true);
            $table->boolean('is_include_ppn')->default(true);
            $table->decimal('ppn_percent', 18, 4);
            $table->string('diskon_type')->nullable();
            $table->decimal('diskon', 18, 4)->default(0);
            $table->text('batal_alasan')->nullable();
            $table->foreignUuid('batal_user_id')->index()->nullable()->constrained('users');
            $table->datetime('batal_at')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status')->index()->default(Const_Status::PESANAN_PENJUALAN_BELUM_DIKIRIM);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_penjualans');
    }
};
