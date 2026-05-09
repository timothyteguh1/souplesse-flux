<?php

use App\Utilities\Constants\Const_Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cabangs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode')->unique()->index();
            $table->string('nama');
            $table->foreignUuid('perusahaan_id')->index()->constrained('perusahaans');
            $table->text('alamat')->nullable();
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('telp')->nullable();
            $table->string('email', 200)->nullable();
            $table->string('ktp_nama')->nullable();
            $table->string('ktp_nomor')->nullable();
            $table->string('npwp_nama')->nullable();
            $table->string('npwp_nomor')->nullable();
            $table->string('sia_nama')->nullable();
            $table->string('sia_nomor')->nullable();
            $table->date('sia_berlaku')->nullable();
            $table->string('sipa_nama')->nullable();
            $table->string('sipa_nomor')->nullable();
            $table->date('sipa_berlaku')->nullable();
            $table->boolean('is_pkp')->default(true);
            $table->boolean('is_include_ppn')->default(false);
            $table->decimal('ppn_percent', 18, 4)->default(0);
            $table->text('keterangan')->nullable();
            $table->string('status')->index()->default(Const_Status::AKTIF);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cabangs');
    }
};
