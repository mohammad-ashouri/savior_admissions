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
        Schema::table('tuition_invoice_details', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_method')->after('tuition_invoice_id');
            $table->foreign('payment_method')->references('id')->on('payment_methods');
            $table->string('description')->nullable()->after('is_paid');
            $table->unsignedBigInteger('invoice_id')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tuition_invoice_details', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->dropColumn('description');
            $table->unsignedBigInteger('invoice_id')->nullable(false)->change();
        });
    }
};
