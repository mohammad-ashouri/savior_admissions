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
        Schema::create('tuition_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tuition_id');
            $table->foreign('tuition_id')->references('id')->on('tuitions');
            $table->unsignedBigInteger('level');
            $table->foreign('level')->references('id')->on('levels');
            $table->float('price')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuition_details');
    }
};
