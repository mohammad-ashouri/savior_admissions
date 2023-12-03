<?php

namespace Database\Seeders;

use App\Models\Catalogs\DocumentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DocumentType::query()->insert([
//            ['name' => 'Personal Image'],
//            ['name' => 'Passport Page 1'],
//            ['name' => 'Passport Page 2'],
//            ['name' => 'Passport Page 3'],
//            ['name' => 'Passport Page 4'],
//            ['name' => 'Payment'],
//            ['name' => 'Letter'],
        ]);
    }
}
