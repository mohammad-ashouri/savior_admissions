<?php

namespace App\Http\Controllers\GeneralControllers;

use App\Http\Controllers\Controller;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\StudentInformation;
use App\Models\User;
use Stimulsoft\StiHandler;

class PDFExportController extends Controller
{
    public function tuitionCardEnExport($appliance_id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $me=auth()->user()->id;
        $applianceStatus=StudentApplianceStatus::find($appliance_id);

        $me=User::find($me);
        if ($me){
            if ($me->hasRole('Parent')) {
                $checkGuardian = StudentInformation::where('guardian', $me->id)->where('student_id', $applianceStatus->student_id)->exists();
            }elseif($me->hasRole('Principal') or $me->hasRole('Financial Manager') or $me->hasRole('Admissions Officer')){
                $checkGuardian = StudentApplianceStatus::where('student_id', $applianceStatus->student_id)->whereIn('academic_year',$this->getActiveAcademicYears())->exists();
            }elseif ($me->hasRole('Super Admin')){
                $checkGuardian = StudentApplianceStatus::where('student_id', $applianceStatus->student_id)->exists();
            }
            if (!$checkGuardian){
                abort(403);
            }
        }
        $allDiscounts=$this->getAllDiscounts($applianceStatus->student_id);
        $allFamilyDiscounts=$this->getAllFamilyDiscounts();
        return view('GeneralPages.PDF.TuitionCard.2024.tuition_card_en',compact('applianceStatus','allDiscounts','allFamilyDiscounts'));
    }
    public function tuitionCardFaExport($appliance_id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $me=auth()->user()->id;
        $applianceStatus=StudentApplianceStatus::find($appliance_id);

        $me=User::find($me);
        if ($me){
            if ($me->hasRole('Parent')) {
                $checkGuardian = StudentInformation::where('guardian', $me->id)->where('student_id', $applianceStatus->student_id)->exists();
            }elseif($me->hasRole('Principal') or $me->hasRole('Financial Manager') or $me->hasRole('Admissions Officer')){
                $checkGuardian = StudentApplianceStatus::where('student_id', $applianceStatus->student_id)->whereIn('academic_year',$this->getActiveAcademicYears())->exists();
            }elseif ($me->hasRole('Super Admin')){
                $checkGuardian = StudentApplianceStatus::where('student_id', $applianceStatus->student_id)->exists();
            }
            if (!$checkGuardian){
                abort(403);
            }
        }
        $applianceStatus=StudentApplianceStatus::find($appliance_id);
        $allDiscounts=$this->getAllDiscounts($applianceStatus->student_id);
        $allFamilyDiscounts=$this->getAllFamilyDiscounts();
        return view('GeneralPages.PDF.TuitionCard.2024.tuition_card_fa',compact('applianceStatus','allDiscounts','allFamilyDiscounts'));
    }
}
