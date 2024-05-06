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
        Schema::table('application_timings', function (Blueprint $table) {
            $table->string('meeting_link')->after('fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_timings', function (Blueprint $table) {
            $table->dropColumn('meeting_link');
        });
    }
};
