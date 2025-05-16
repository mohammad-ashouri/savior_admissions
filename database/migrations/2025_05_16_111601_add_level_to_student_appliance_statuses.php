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
            $table->unsignedBigInteger('level')->nullable()->after('academic_year');
            $table->foreign('level')->references('id')->on('levels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_appliance_statuses', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }
};
