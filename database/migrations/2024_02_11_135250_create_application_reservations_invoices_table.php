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
        Schema::create('application_reservations_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('a_reservation_id');
            $table->foreign('a_reservation_id')->references('id')->on('application_reservations');
            $table->json('payment_information');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_reservations_invoices');
    }
};
