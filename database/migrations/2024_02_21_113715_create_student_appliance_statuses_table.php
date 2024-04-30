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
        Schema::create('student_appliance_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('users');
            $table->unsignedBigInteger('academic_year');
            $table->foreign('academic_year')->references('id')->on('academic_years');
            $table->string('interview_status')->nullable();
            $table->boolean('documents_uploaded')->nullable();
            $table->string('documents_uploaded_approval')->nullable();
            $table->unsignedBigInteger('documents_uploaded_seconder')->nullable();
            $table->foreign('documents_uploaded_seconder')->references('id')->on('users');
            $table->string('seconder_description')->nullable();
            $table->string('tuition_payment_status')->nullable();
            $table->boolean('approval_status')->default(0);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_appliance_statuses');
    }
};
