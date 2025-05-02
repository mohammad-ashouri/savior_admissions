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
        Schema::table('tuition_details', function (Blueprint $table) {
            $table->json('three_installment_payment')->nullable()->after('two_installment_payment');
            $table->json('three_installment_payment_ministry')->nullable()->after('three_installment_payment');
            $table->json('seven_installment_payment')->nullable()->after('four_installment_payment');
            $table->json('seven_installment_payment_ministry')->nullable()->after('seven_installment_payment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tuition_details', function (Blueprint $table) {
            $table->dropColumn(['three_installment_payment','seven_installment_payment','three_installment_payment_ministry','seven_installment_payment_ministry']);
        });
    }
};
