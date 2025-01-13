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
        Schema::table('appliance_confirmation_information', function (Blueprint $table) {
            $table->dateTime('date_of_referral')->nullable();
            $table->dateTime('date_of_confirm')->nullable();
            $table->unsignedBigInteger('referrer')->nullable();
            $table->foreign('referrer')->references('id')->on('users');
            $table->unsignedBigInteger('seconder')->nullable();
            $table->foreign('seconder')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appliance_confirmation_information', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_referral',
                'date_of_confirm',
                'referrer',
                'seconder',
            ]);
        });
    }
};
