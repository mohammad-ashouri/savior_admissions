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
        Schema::create('guardian_student_relationships', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        $query = "insert into guardian_student_relationships (name) values ('Father'),('Mother'),('Other')";
        DB::statement($query);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guardian_student_relationships');
    }
};
