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
            $table->json('full_payment_ministry')->nullable()->after('full_payment');
            $table->json('two_installment_payment_ministry')->nullable()->after('two_installment_payment');
            $table->json('four_installment_payment_ministry')->nullable()->after('four_installment_payment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tuition_details', function (Blueprint $table) {
            $table->dropColumn('full_payment_ministry');
            $table->dropColumn('two_installment_payment_ministry');
            $table->dropColumn('four_installment_payment_ministry');
        });
    }
};
