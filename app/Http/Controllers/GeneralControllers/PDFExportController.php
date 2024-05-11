<?php

namespace App\Http\Controllers\GeneralControllers;

use App\Http\Controllers\Controller;
use App\Models\Branch\StudentApplianceStatus;

class PDFExportController extends Controller
{
    public function tuitionCardEnExport($appliance_id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $applianceStatus=StudentApplianceStatus::find($appliance_id);

        return view('GeneralPages.PDF.TuitionCard.2024.tuition_card_en',compact('applianceStatus'));
//        $pdf=PDF::loadView('GeneralPages.PDF.tuition_card');
//        return $pdf->download('document.pdf');
    }
}
