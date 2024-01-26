<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
        $query="insert into levels (name) values ('Kindergarten 1'),('Kindergarten 2'),('Grade 1'),('Grade 2'),('Grade 3'),('Grade 4'),('Grade 5'),('Grade 6'),('Grade 7'),('Grade 8'),('Grade 9'),('Grade 10'),('Grade 11'),('Grade 12')";
        DB::statement($query);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
