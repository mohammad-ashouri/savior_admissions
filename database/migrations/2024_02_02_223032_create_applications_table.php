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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_timing_id');
            $table->foreign('application_timing_id')->references('id')->on('application_timings');
            $table->date('date');
            $table->time('start_from');
            $table->time('ends_to');
            $table->unsignedBigInteger('first_interviewer');
            $table->foreign('first_interviewer')->references('id')->on('users');
            $table->unsignedBigInteger('second_interviewer');
            $table->foreign('second_interviewer')->references('id')->on('users');
            $table->boolean('reserved')->default(0);
            $table->boolean('Interviewed')->default(0);
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
        Schema::dropIfExists('applications');
    }
};
