<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('code_counters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('cabang_id')->index()->nullable();
            $table->string('model');
            $table->string('prefix');
            $table->integer('length');
            $table->bigInteger('counter')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('code_counters');
    }
};
