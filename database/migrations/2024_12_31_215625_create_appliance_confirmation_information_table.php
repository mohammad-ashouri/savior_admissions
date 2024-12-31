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
        Schema::create('appliance_confirmation_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appliance_id');
            $table->foreign('appliance_id')->references('id')->on('student_appliance_statuses');
            $table->dateTime('date_of_referral')->nullable();
            $table->dateTime('date_of_confirm')->nullable();
            $table->string('status')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('referrer')->nullable();
            $table->foreign('referrer')->references('id')->on('users');
            $table->unsignedBigInteger('seconder')->nullable();
            $table->foreign('seconder')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appliance_confirmation_information');
    }
};
