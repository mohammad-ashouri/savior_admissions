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
        Schema::table('tuition_invoice_edit_histories', function (Blueprint $table) {
            $table->json('file')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tuition_invoice_edit_histories', function (Blueprint $table) {
            $table->dropColumn('file');
        });
    }
};
