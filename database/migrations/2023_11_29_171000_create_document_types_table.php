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

        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        $query = "INSERT INTO `document_types` (`name`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
('Old Type', 0, '2024-01-19 06:22:23', '2024-01-19 06:22:23', NULL),
('Personal picture', 1, '2024-01-19 06:22:23', '2024-01-19 06:22:23', NULL),
('Passport photo - page 1', 1, '2024-01-19 06:22:23', '2024-01-19 06:22:23', NULL),
('Passport photo - page 2', 1, '2024-01-19 06:22:23', '2024-01-19 06:22:23', NULL),
('Passport photo - page 3', 1, '2024-01-19 06:22:23', '2024-01-19 06:22:23', NULL),
('National card photo', 1, '2024-01-19 06:22:23', '2024-01-19 06:22:23', NULL)
;";
        DB::statement($query);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_types');
    }
};
