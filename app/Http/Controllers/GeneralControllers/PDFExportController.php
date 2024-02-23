<?php

namespace App\Http\Controllers\GeneralControllers;

use App\Http\Controllers\Controller;
class PDFExportController extends Controller
{
    public function tuitionCardExport()
    {
        return view('GeneralPages.PDF.tuition_card');
//        $pdf=PDF::loadView('GeneralPages.PDF.tuition_card');
//        return $pdf->download('document.pdf');
    }
}
