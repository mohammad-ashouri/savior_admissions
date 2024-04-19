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
        Schema::create('application_timings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('academic_year');
            $table->foreign('academic_year')->references('id')->on('academic_years');
//            $table->enum('students_application_type', ['All' , 'Presently Studying'])->default('All');
            $table->date('start_date');
            $table->time('start_time');
            $table->date('end_date');
            $table->time('end_time');
            $table->integer('interview_time');
            $table->integer('delay_between_reserve');
            $table->unsignedBigInteger('first_interviewer');
            $table->foreign('first_interviewer')->references('id')->on('users');
            $table->unsignedBigInteger('second_interviewer');
            $table->foreign('second_interviewer')->references('id')->on('users');
            $table->integer('fee');
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
        Schema::dropIfExists('application_timings');
    }
};
