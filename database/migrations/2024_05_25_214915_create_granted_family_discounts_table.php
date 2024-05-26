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
        Schema::create('granted_family_discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appliance_id')->unique();
            $table->foreign('appliance_id')->references('id')->on('student_appliance_statuses')->onDelete('cascade');
            $table->unsignedBigInteger('level');
            $table->foreign('level')->references('id')->on('levels');
            $table->integer('discount_percent');
            $table->integer('discount_price');
            $table->integer('signed_child_number');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('granted_family_discounts');
    }
};
