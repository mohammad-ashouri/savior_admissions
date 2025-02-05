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
        Schema::create('tuition_invoice_details_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_details_id')->nullable();
            $table->foreign('invoice_details_id')->references('id')->on('tuition_invoice_details');
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->integer('amount');
            $table->unsignedBigInteger('adder')->nullable();
            $table->foreign('adder')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuition_invoice_details_payments');
    }
};
