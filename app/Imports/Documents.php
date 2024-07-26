<?php
namespace App\Imports;

use App\Models\Catalogs\DocumentType;
use App\Models\Document;
use Maatwebsite\Excel\Concerns\ToModel;

class Documents implements ToModel
{
    public function model(array $row)
    {
        // Define how each row in the Excel file should be mapped to your database model
        $documentType=DocumentType::whereName($row[1])->first();
        return new Document([
            'user_id' => $row[0],
            'document_type_id' => $documentType->id,
            'description' => $row[2],
            'src' => $row[5],
        ]);
    }
}
