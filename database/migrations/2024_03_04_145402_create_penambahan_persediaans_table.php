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
        Schema::create('penambahan_persediaans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cabang_id')->index()->constrained('cabangs');
            $table->string('kode')->index();
            $table->unique(['cabang_id', 'kode']);
            $table->datetime('tanggal');
            $table->foreignUuid('gudang_id')->index()->constrained('gudangs');
            $table->text('keterangan')->nullable();
            $table->string('status')->index()->default(Const_Status::PENAMBAHAN_PERSEDIAAN_AKTIF);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penambahan_persediaans');
    }
};
