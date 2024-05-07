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
            $table->dateTime('date_of_payment')->nullable()->after('payment_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tuition_invoice_details', function (Blueprint $table) {
            $table->dropColumn('date_of_payment');
        });
    }
};
