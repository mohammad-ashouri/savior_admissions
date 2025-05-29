<?php

namespace App\Livewire\BranchInfo;

use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\UserAccessInformation;
use App\Traits\CheckPermissions;
use Livewire\Component;

class ConfirmAppliance extends Component
{
    use CheckPermissions;

    public $student_appliances;

    public function mount()
    {
        if (auth()->user()->hasRole('Super Admin')) {
            $academicYears = AcademicYear::pluck('id')->toArray();
        } elseif (auth()->user()->hasRole('Principal')) {
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesP($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
        }
        $this->student_appliances = StudentApplianceStatus::with('studentInfo')
            ->with('academicYearInfo')
            ->whereIn('academic_year', $academicYears)
            ->where('approval_status', 0)
            ->where('documents_uploaded', 1)
            ->where('interview_status', 'Pending For Principal Confirmation')
            ->get();
    }

    public function render()
    {
        return view('livewire.branch-info.confirm-appliance', [
            'studentAppliances' => $this->student_appliances
        ]);
    }
}
