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
        Schema::create('perusahaans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode')->unique()->index();
            $table->string('nama');
            $table->text('alamat')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kota')->nullable();
            $table->string('kode_pos')->nullable();

            $table->foreignUuid('user_id')->index()->constrained('users');
            $table->foreignUuid('plan_id')->index()->constrained('plans');

            $table->string('telp')->nullable();
            $table->string('fax')->nullable();
            $table->string('email', 200)->nullable();
            $table->string('logo', 255)->nullable();

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
        Schema::dropIfExists('perusahaans');
    }
};
