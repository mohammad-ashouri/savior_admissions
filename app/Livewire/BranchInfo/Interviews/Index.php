<?php

namespace App\Livewire\BranchInfo\Interviews;

use App\Models\Branch\ApplicationReservation;
use App\Models\Branch\Applications;
use App\Models\Branch\ApplicationTiming;
use App\Models\Branch\Interview;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\StudentInformation;
use App\Models\UserAccessInformation;
use App\Traits\CheckPermissions;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\View\View;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Index extends Component
{
    use CheckPermissions;

    /**
     * Academic years
     * @var array
     */
    public $academic_years = [];

    /**
     * Interviews
     * @var array
     */
    public $interviews = [];

    /**
     * Selected academic year
     * @var string
     */
    #[Validate('required|exists:academic_years,id')]
    public $academic_year = '';

    /**
     * Interview
     * @var Interview
     */
    public Interview $interview;

    /**
     * List interviews after selecting academic year
     * @return void
     */
    public function search(): void
    {
        $this->validate();
        $this->fetchValues();
    }

    /**
     * Fetch values after all operations
     * @return void
     */
    public function fetchValues(): void
    {
        if ($this->academic_year != null) {
            if (auth()->user()->hasExactRoles(['Parent'])) {
                $myStudents = StudentInformation::whereGuardian(auth()->user()->id)->pluck('student_id')->toArray();
                $reservations = ApplicationReservation::whereIn('student_id', $myStudents)->pluck('application_id')->toArray();
                $this->interviews = Applications::with('applicationTimingInfo')
                    ->with('firstInterviewerInfo')
                    ->with('secondInterviewerInfo')
                    ->with('reservationInfo')
                    ->with('interview')
                    ->whereReserved(1)
                    ->whereIn('id', $reservations)
                    ->orderBy('interviewed', 'asc') // Corrected column name
                    ->orderBy('date', 'asc')
                    ->orderBy('ends_to', 'asc')
                    ->orderBy('start_from', 'asc')
                    ->get();
            }

            if (auth()->user()->hasRole('Super Admin')) {
                $this->academic_years = AcademicYear::get();

                $this->interviews = Applications::with('firstInterviewerInfo')
                    ->with('secondInterviewerInfo')
                    ->with('reservationInfo')
                    ->with('interview')
                    ->with(['applicationTimingInfo' => function ($query) {
                        $query->where('academic_year', $this->academic_year);
                    }])
                    ->whereHas('applicationTimingInfo', function ($query) {
                        $query->where('academic_year', $this->academic_year);
                    })
                    ->whereReserved(1)
                    ->orderBy('interviewed', 'asc') // Corrected column name
                    ->orderBy('date', 'asc')
                    ->orderBy('ends_to', 'asc')
                    ->orderBy('start_from', 'asc')
                    ->get();
            } elseif (auth()->user()->hasRole(['Principal', 'Financial Manager'])) {
                // Convert accesses to arrays and remove duplicates
                $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
                $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

                // Finding academic years with status 1 in the specified schools
                $this->academic_years = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();

                // Finding application timings based on academic years
                $applicationTimings = ApplicationTiming::whereIn('academic_year', $this->academic_years)->pluck('id')->toArray();

                // Finding applications related to the application timings
                $this->interviews = Applications::with('firstInterviewerInfo')
                    ->with('secondInterviewerInfo')
                    ->with('reservationInfo')
                    ->with('interview')
                    ->with(['applicationTimingInfo' => function ($query) {
                        $query->where('academic_year', $this->academic_year);
                    }])
                    ->whereHas('applicationTimingInfo', function ($query) {
                        $query->where('academic_year', $this->academic_year);
                    })
                    ->whereReserved(1)
                    ->whereIn('application_timing_id', $applicationTimings)
                    ->whereReserved(1)
                    ->orderBy('interviewed', 'asc') // Corrected column name
                    ->orderBy('date', 'asc')
                    ->orderBy('ends_to', 'asc')
                    ->orderBy('start_from', 'asc')
                    ->get();

            } elseif (auth()->user()->hasRole('Interviewer')) {
                $this->interviews = Applications::with('firstInterviewerInfo')
                    ->with('secondInterviewerInfo')
                    ->with('reservationInfo')
                    ->with('interview')
                    ->with(['applicationTimingInfo' => function ($query) {
                        $query->where('academic_year', $this->academic_year);
                    }])
                    ->whereHas('applicationTimingInfo', function ($query) {
                        $query->where('academic_year', $this->academic_year);
                    })
                    ->whereReserved(1)
                    ->where(function ($query) {
                        $query->where('first_interviewer', auth()->user()->id)
                            ->orWhere('second_interviewer', auth()->user()->id);
                    })
                    ->orderBy('interviewed', 'asc') // Corrected column name
                    ->orderBy('date', 'asc')
                    ->orderBy('ends_to', 'asc')
                    ->orderBy('start_from', 'asc')
                    ->get();
            }
        }

        if (auth()->user()->hasRole('Super Admin')) {
            $this->academic_years = AcademicYear::orderByDesc('id')->get();
        } elseif (auth()->user()->hasRole(['Principal', 'Financial Manager'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $this->academic_years = AcademicYear::whereIn('school_id', $filteredArray)->orderByDesc('id')->get();
        } elseif (auth()->user()->hasRole('Interviewer')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesI($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $this->academic_years = AcademicYear::whereIn('school_id', $filteredArray)->orderByDesc('id')->get();
        }
    }

    /**
     * Absence interview after user confirmed sweetalert
     * @return void
     */
    public function absence(): void
    {
        $applicationId = $this->interview->id;

        if (auth()->user()->hasRole('Super Admin')) {
            $application = Applications::where('reserved', 1)
                ->whereId($applicationId)
                ->first();
        } elseif (auth()->user()->hasRole('Interviewer')) {
            $application = Applications::where('reserved', 1)
                ->where('first_interviewer', auth()->user()->id)
                ->whereId($applicationId)
                ->first();
        }

        $application->interviewed = 2;
        $application->save();

        $studentStatus = StudentApplianceStatus::whereStudentId($application->reservationInfo->student_id)
            ->whereAcademicYear($application->applicationTimingInfo->academic_year)
            ->first();
        $studentStatus->interview_status = 'Absence';
        $studentStatus->save();

        LivewireAlert::title('Success')
            ->text('Operation completed successfully.')
            ->success()
            ->timer(3000) // Dismisses after 3 seconds
            ->show();
    }

    /**
     * Submitting interview absence
     * @param $interview_id
     * @return void
     */
    public function submitAbsence($interview_id): void
    {
        $this->interview = Interview::findOrFail($interview_id);
        LivewireAlert::title('Are you sure?')
            ->text('Do you want to proceed with this action?')
            ->asConfirm()
            ->onConfirm('absence')
            ->show();
        $this->skipRender();
    }

    /**
     * Render the component
     * @return Factory|Application|\Illuminate\Contracts\View\View|View|\Illuminate\Contracts\Foundation\Application
     */
    public function render(): Factory|Application|\Illuminate\Contracts\View\View|View|\Illuminate\Contracts\Foundation\Application
    {
        $this->fetchValues();
        $this->dispatch('initialize-data-table');
        return view('livewire.branch-info.interviews.index');
    }
}
