<?php
namespace App\Imports;

use App\Models\Catalogs\DocumentType;
use Maatwebsite\Excel\Concerns\ToModel;

class DocumentTypesImport implements ToModel
{
    public function model(array $row)
    {
        // Define how each row in the Excel file should be mapped to your database model
        return new DocumentType([
            'name' => $row[0],
        ]);
    }
}
