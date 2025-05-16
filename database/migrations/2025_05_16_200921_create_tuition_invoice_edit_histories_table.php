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
        Schema::create('tuition_invoice_edit_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_details_id');
            $table->foreign('invoice_details_id')->references('id')->on('tuition_invoice_details');
            $table->json('description');
            $table->unsignedBigInteger('user');
            $table->foreign('user')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuition_invoice_edit_histories');
    }
};
