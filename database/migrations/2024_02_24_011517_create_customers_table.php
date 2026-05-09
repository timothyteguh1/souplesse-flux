<?php

use App\Utilities\Constants\Const_Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cabang_id')->index()->constrained('cabangs');
            $table->string('kode')->index();
            $table->unique(['cabang_id', 'kode']);
            $table->string('nama');

            $table->string('telp')->nullable();
            $table->string('handphone')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->string('kota')->nullable();

            $table->boolean('is_blacklist')->default(false);
            $table->boolean('is_pkp')->default(true);
            $table->boolean('is_include_ppn')->default(false);

            $table->string('npwp_kode')->nullable();
            $table->string('npwp_nik')->nullable();
            $table->string('npwp_wajib_pajak')->nullable();
            $table->string('npwp_blok')->nullable();
            $table->string('npwp_nomor')->nullable();
            $table->text('npwp_alamat')->nullable();
            $table->string('npwp_kota')->nullable();
            $table->string('npwp_kode_pos')->nullable();
            $table->string('npwp_provinsi')->nullable();
            $table->string('npwp_negara')->nullable();

            $table->integer('jatuh_tempo')->default(0);
            $table->decimal('limit_piutang', 18, 4)->default(0);
            $table->string('rekening_bank')->nullable();
            $table->string('rekening_nomor')->nullable();
            $table->string('rekening_nama')->nullable();

            $table->text('keterangan')->nullable();
            $table->string('status')->index()->default(Const_Status::AKTIF);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
