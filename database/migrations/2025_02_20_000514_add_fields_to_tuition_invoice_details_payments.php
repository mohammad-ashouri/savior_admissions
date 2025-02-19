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
        Schema::table('tuition_invoice_details_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('seconder')->nullable();
            $table->foreign('seconder')->references('id')->on('users');
            $table->dateTime('approval_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tuition_invoice_details_payments', function (Blueprint $table) {
            $table->dropColumn(['seconder', 'approval_date']);
        });
    }
};
