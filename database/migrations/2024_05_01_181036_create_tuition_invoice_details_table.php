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
        Schema::create('tuition_invoice_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tuition_invoice_id');
            $table->foreign('tuition_invoice_id')->references('id')->on('tuition_invoices')->cascadeOnDelete();
            $table->unsignedBigInteger('invoice_id');
            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->integer('amount');
            $table->boolean('is_paid')->default(2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuition_invoice_details');
    }
};
