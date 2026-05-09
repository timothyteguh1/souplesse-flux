<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transfer_persediaan_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transfer_persediaan_id')->index()->constrained('transfer_persediaans');
            $table->foreignUuid('produk_id')->index()->constrained('produks');
            $table->foreignUuid('satuan_id')->index()->constrained('satuans');
            $table->date('expired_date')->nullable();
            $table->string('no_batch')->nullable();
            $table->decimal('jumlah', 18, 4);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_persediaan_details');
    }
};
