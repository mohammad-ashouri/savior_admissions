<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_informations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('users');
            $table->unsignedBigInteger('parent_father_id')->nullable();
            $table->foreign('parent_father_id')->references('id')->on('users');
            $table->unsignedBigInteger('parent_mother_id')->nullable();
            $table->foreign('parent_mother_id')->references('id')->on('users');
            $table->unsignedBigInteger('guardian')->nullable();
            $table->foreign('guardian')->references('id')->on('users');
            $table->unsignedBigInteger('guardian_student_relationship')->nullable();
            $table->foreign('guardian_student_relationship')->references('id')->on('guardian_student_relationships');
            $table->unsignedBigInteger('current_nationality')->nullable();
            $table->foreign('current_nationality')->references('id')->on('countries');
            $table->unsignedBigInteger('current_identification_type')->nullable();
            $table->foreign('current_identification_type')->references('id')->on('current_identification_types');
            $table->string('current_identification_code')->nullable();
            $table->unsignedBigInteger('status')->nullable();
            $table->foreign('status')->references('id')->on('student_statuses');
            $table->timestamps();
        });
        DB::unprepared(file_get_contents('database/migrations/student_informations.sql'));

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_informations');
    }
};
