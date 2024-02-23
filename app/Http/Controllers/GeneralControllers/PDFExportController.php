<?php

namespace App\Http\Controllers\GeneralControllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
class PDFExportController extends Controller
{
    public function tuitionCardExport()
    {
//        return view('GeneralPages.PDF.tuition_card');
        $pdf=Pdf::loadView('GeneralPages.PDF.tuition_card');
        return $pdf->download('document.pdf');
    }
}
