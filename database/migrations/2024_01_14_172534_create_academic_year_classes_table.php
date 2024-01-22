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
        Schema::create('academic_year_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('academic_year');
            $table->foreign('academic_year')->references('id')->on('academic_years');
            $table->unsignedBigInteger('level');
            $table->foreign('level')->references('id')->on('levels');
            $table->unsignedBigInteger('education_type');
            $table->foreign('education_type')->references('id')->on('education_types');
            $table->integer('capacity');
            $table->unsignedBigInteger('education_gender');
            $table->foreign('education_gender')->references('id')->on('genders');
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_year_classes');
    }
};
