<?php

namespace App\Livewire\BranchInfo\Students;

use App\Models\Catalogs\AcademicYear;
use App\Models\StudentInformation;
use App\Models\UserAccessInformation;
use Livewire\Component;

class Show extends Component
{
    public $studentInformations;

    public function mount($student_id)
    {
        if (auth()->user()->hasExactRoles(['Parent'])) {
            $this->studentInformations = StudentInformation::whereGuardian(auth()->user()->id)
                ->with('studentInfo')
                ->with('nationalityInfo')
                ->with('identificationTypeInfo')
                ->with('generalInformations')
                ->with('extraInformations')
                ->with('userInfo')
                ->whereStudentId($student_id)
                ->first();
            if (empty($this->studentInformations)) {
                abort(403);
            }
        } elseif (auth()->user()->hasRole('Super Admin')) {
            $this->studentInformations = StudentInformation::with('studentInfo')
                ->with('nationalityInfo')
                ->with('identificationTypeInfo')
                ->with('generalInformations')
                ->with('userInfo')
                ->with('extraInformations')
                ->whereStudentId($student_id)
                ->first();
            if (empty($studentInformations)) {
                abort(403);
            }

        } elseif (auth()->user()->hasRole(['Principal', 'Admissions Officer'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
            $this->studentInformations = StudentInformation::with('studentInfo')
                ->with('nationalityInfo')
                ->with('identificationTypeInfo')
                ->with('generalInformations')
                ->with('extraInformations')
                ->with('userInfo')
                ->join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->join('applications', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->join('application_timings', 'applications.application_timing_id', '=', 'application_timings.id')
                ->where('student_appliance_statuses.student_id', $student_id)
                ->whereIn('application_timings.academic_year', $academicYears)
                ->first();
        }
    }

    public function render()
    {
        return view('livewire.branch-info.students.show');
    }
}
