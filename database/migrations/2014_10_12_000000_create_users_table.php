<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'main';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('family');
            $table->string('mobile')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->tinyInteger('status')->default('1');
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });
//        $password=bcrypt('123');
//        $query="insert into users (name,family,mobile,email, password,type) values ('Mohammad','Ashouri','+989012682581','test@example.com' ,'$password',1)";
//        DB::statement($query);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
