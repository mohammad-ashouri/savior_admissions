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
            $table->string('status')->nullable();
            $table->text('description')->nullable();
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
