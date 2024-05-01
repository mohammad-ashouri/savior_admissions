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
        Schema::create('tuition_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appliance_id');
            $table->foreign('appliance_id')->references('id')->on('student_appliance_statuses');
            $table->string('payment_type');
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuition_invoices');
    }
};
