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
        Schema::create('plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode')->unique()->index();
            $table->string('nama');
            $table->integer('jumlah_cabang')->default(0);
            $table->integer('jumlah_user')->default(0);
            $table->decimal('harga', 18, 4)->default(0);
            $table->integer('masa_aktif_hari')->default(0);
            $table->text('keterangan')->nullable();
            $table->string('status')->index()->default(Const_Status::AKTIF);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
