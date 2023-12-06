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
        Schema::create('education_years', function (Blueprint $table) {
            $table->id();
            $table->date('start');
            $table->date('finish')->nullable();
            $table->boolean('active')->default(1);
            $table->unsignedBigInteger('starter');
            $table->foreign('starter')->references('id')->on('users');
            $table->unsignedBigInteger('finisher')->nullable();
            $table->foreign('finisher')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_years');
    }
};
