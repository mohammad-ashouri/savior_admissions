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
            $table->tinyInteger('payment_method')->after('invoice_id');
            $table->tinyInteger('status')->default(0)->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tuition_invoice_details_payments', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'status']);
        });
    }
};
