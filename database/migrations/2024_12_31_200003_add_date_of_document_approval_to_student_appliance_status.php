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
        Schema::table('student_appliance_statuses', function (Blueprint $table) {
            $table->date('date_of_document_approval')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_appliance_statuses', function (Blueprint $table) {
            $table->dropColumn('date_of_document_approval');
        });
    }
};
