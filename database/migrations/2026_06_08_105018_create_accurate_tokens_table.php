<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accurate_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('access_token', 1000);
            $table->string('refresh_token', 1000)->nullable();
            $table->string('token_type')->default('Bearer');
            $table->integer('expires_in')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('db_id')->nullable();       // database Accurate yang dipilih
            $table->string('db_alias')->nullable();    // nama database Accurate
            $table->string('db_host')->nullable();     // host API database Accurate
            $table->boolean('is_connected')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accurate_tokens');
    }
};