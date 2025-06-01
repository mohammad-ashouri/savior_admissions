<?php

namespace App\Livewire\BranchInfo\Students;

use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\StudentInformation;
use App\Models\UserAccessInformation;
use Livewire\Component;

class Index extends Component
{
    public $students;
    public $academicYears;

    public function mount()
    {
        if (!auth()->user()->can('students-list')) {
            abort(403, 'Unauthorized action.');
        }

        if (auth()->user()->hasRole('Super Admin')) {
            $this->students = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')
                ->whereTuitionPaymentStatus('Paid')
                ->orderBy('academic_year', 'desc')->get();
            $this->academicYears = AcademicYear::get();

        } elseif (auth()->user()->hasExactRoles(['Parent'])) {
            $this->students = StudentInformation::whereGuardian(auth()->user()->id)
                ->with('studentInfo')
                ->with('nationalityInfo')
                ->with('identificationTypeInfo')
                ->with('generalInformations')
                ->orderBy('student_id', 'asc')->get();
        } elseif (auth()->user()->hasRole(['Principal', 'Admissions Officer'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
            $this->students = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')
                ->whereIn('academic_year', $academicYears)
                ->whereTuitionPaymentStatus('Paid')
                ->orderBy('academic_year', 'desc')->get();
            $this->academicYears = AcademicYear::whereIn('id', $academicYears)->get();
        }
    }

    public function render()
    {
        return view('livewire.branch-info.students.index');
    }
}
