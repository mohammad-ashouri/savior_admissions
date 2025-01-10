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
            $table->string('tracking_code')->after('date_of_payment')->nullable();
            $table->text('financial_manager_description')->after('tracking_code')->nullable();
            $table->unsignedBigInteger('editor')->nullable()->after('tracking_code');
            $table->foreign('editor')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tuition_invoice_details', function (Blueprint $table) {
            $table->dropColumn('tracking_code');
            $table->dropColumn('financial_manager_description');
            $table->dropColumn('editor');
        });
    }
};
