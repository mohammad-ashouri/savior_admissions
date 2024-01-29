<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('current_identification_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        $query = "insert into current_identification_types (name) values ('Iranian National ID'),('Passport'),('Other')";
        DB::statement($query);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('current_identification_types');
    }
};
