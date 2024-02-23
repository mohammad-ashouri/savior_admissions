<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tuition', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('academic_year');
            $table->foreign('academic_year')->references('id')->on('academic_years');
            $table->unsignedBigInteger('level');
            $table->foreign('level')->references('id')->on('levels');
            $table->float('price');
            $table->float('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuition');
    }
};
