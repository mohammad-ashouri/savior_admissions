<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('general_informations', function (Blueprint $table) {
            $table->string('fida_code')->nullable()->after('faragir_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_informations', function (Blueprint $table) {
            $table->dropColumn('fida_code');
        });
    }
};
