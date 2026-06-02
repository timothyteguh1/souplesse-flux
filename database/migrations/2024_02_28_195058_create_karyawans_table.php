<?php

use App\Utilities\Constants\Const_Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cabang_id')->index()->constrained('cabangs');
            $table->string('kode')->index();
            $table->unique(['cabang_id', 'kode']);
            $table->string('nama');
            $table->string('level')->nullable();
            $table->decimal('komisi', 18, 4)->default(0);

            $table->string('telp')->nullable();
            $table->string('handphone')->nullable();
            $table->string('email')->nullable();

            // $table->foreignUuid('jabatan_id')->index()->nullable()->constrained('jabatans');
            $table->foreignUuid('user_id')->index()->nullable()->constrained('users');
            $table->string('no_ktp')->nullable();
            $table->datetime('tanggal_masuk')->nullable();
            $table->text('alamat')->nullable();
            $table->string('kota')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status')->index()->default(Const_Status::AKTIF);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
