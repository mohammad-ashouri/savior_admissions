<?php

namespace Database\Seeders;

use App\Models\Catalogs\DocumentType;
use App\Models\Document;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseImportSql extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::unprepared(file_get_contents('database/migrations/countries.sql'));
        DB::unprepared(file_get_contents('database/migrations/users.sql'));
        DB::unprepared(file_get_contents('database/migrations/general_informations.sql'));
        DB::unprepared(file_get_contents('database/migrations/student_informations.sql'));
        DB::unprepared(file_get_contents('database/migrations/model_has_roles.sql'));
//        DB::unprepared(file_get_contents('database/migrations/role_has_permissions.sql'));
        DB::unprepared(file_get_contents('database/migrations/documents.sql'));

        //Set all old document type name to document description
        $allDocuments=Document::get();
        foreach ($allDocuments as $document){

        }

        //Add new document type for old documents
        $oldDocumentTypeID=new DocumentType();
        $oldDocumentTypeID->name='Old Type';
        $oldDocumentTypeID->status=0;
        $oldDocumentTypeID->save();

        $resetDocumentsTypeID=Document::query()->update(['document_type_id'=>$oldDocumentTypeID->id]);
    }
}
