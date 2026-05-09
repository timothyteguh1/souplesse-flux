<?php

use App\Utilities\Constants\Const_Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Builder::morphUsingUuids();

        Schema::create('mutasi_stoks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->datetime('tanggal');
            $table->foreignUuid('cabang_id')->index()->constrained('cabangs');
            $table->foreignUuid('gudang_id')->index()->constrained('gudangs');
            $table->foreignUuid('produk_id')->index()->constrained('produks');
            $table->foreignUuid('satuan_id')->index()->constrained('satuans');
            $table->foreignUuid('satuan_transaksi_id')->index()->constrained('satuans');
            $table->morphs('reference');
            $table->morphs('header');
            $table->string('jenis_transaksi');
            $table->date('expired_date')->nullable();
            $table->string('no_batch')->nullable();
            $table->decimal('jumlah', 18, 4);
            $table->decimal('jumlah_transaksi', 18, 4);
            $table->decimal('harga', 18, 4);
            $table->decimal('harga_transaksi', 18, 4);
            $table->text('keterangan')->nullable();
            $table->string('status')->index()->default(Const_Status::AKTIF);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi_stoks');
    }
};
