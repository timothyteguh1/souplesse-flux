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
        Schema::create('retur_pembelians', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cabang_id')->index()->constrained('cabangs');
            $table->string('kode')->index();
            $table->unique(['cabang_id', 'kode']);
            $table->datetime('tanggal');
            $table->foreignUuid('supplier_id')->index()->constrained('suppliers');
            $table->foreignUuid('gudang_id')->index()->constrained('gudangs');
            $table->boolean('is_pkp')->default(true);
            $table->boolean('is_include_ppn')->default(true);
            $table->decimal('ppn_percent', 18, 4)->default(0);
            $table->text('keterangan')->nullable();
            $table->string('status')->index()->default(Const_Status::RETUR_PEMBELIAN_BELUM_LUNAS);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retur_pembelians');
    }
};
