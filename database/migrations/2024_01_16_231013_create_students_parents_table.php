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
        Schema::create('students_parents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('users');
            $table->unsignedBigInteger('parent_father_id');
            $table->foreign('parent_father_id')->references('id')->on('users');
            $table->unsignedBigInteger('parent_mother_id');
            $table->foreign('parent_mother_id')->references('id')->on('users');
            $table->unsignedBigInteger('editor');
            $table->foreign('editor')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students_parents');
    }
};
