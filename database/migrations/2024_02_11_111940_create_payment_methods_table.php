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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('description')->nullable();
            $table->boolean('status')->default(1);
        });

        $offlinePaymentDescription = json_encode([
            "bank_name" => 'Mellat',
            "card_number" => "6104 1234 4566 7897",
            "shaba" => "IR123456789456789456789465",
            "bank_account_number" => "021125456487"
        ]);

        $query = "INSERT INTO payment_methods (name, description) VALUES ('Offline Payment', ?), ('Online Gateway', NULL)";
        DB::statement($query, [$offlinePaymentDescription]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
