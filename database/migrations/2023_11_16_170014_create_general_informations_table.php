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
        Schema::create('general_informations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('father_name')->nullable();
            $table->string('gender')->nullable();
            $table->date('birthdate')->nullable();
            $table->unsignedBigInteger('birthplace')->nullable();
            $table->foreign('birthplace')->references('id')->on('countries');
            $table->unsignedBigInteger('nationality')->nullable();
            $table->foreign('nationality')->references('id')->on('countries');
            $table->string('passport_number')->nullable();
            $table->string('faragir_code')->nullable();
            $table->unsignedBigInteger('country')->nullable();
            $table->foreign('country')->references('id')->on('countries');
            $table->string('state_city')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('adder')->nullable();
            $table->string('editor')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_informations');
    }
};
