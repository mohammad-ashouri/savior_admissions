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
            "card_number" => "6104 3388 0014 6892",
            "shaba" => "IR880120000000009651522568",
            "bank_account_number" => "9651522568"
        ]);

        $query = "INSERT INTO payment_methods (name, description) VALUES ('Offline Payment', ?), ('Online Gateway (Iran Bank)', NULL)";
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
