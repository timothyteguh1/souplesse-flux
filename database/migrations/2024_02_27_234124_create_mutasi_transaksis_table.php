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

        Schema::create('mutasi_transaksis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->datetime('tanggal');
            $table->foreignUuid('cabang_id')->index()->constrained('cabangs');
            $table->string('jenis');
            $table->morphs('vendor');
            $table->morphs('reference');
            $table->morphs('header');
            $table->string('jenis_transaksi');
            $table->decimal('jumlah', 18, 4);
            $table->text('keterangan')->nullable();
            $table->string('status')->index()->default(Const_Status::AKTIF);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi_transaksis');
    }
};
