<?php

namespace App\Livewire\BranchInfo;

use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\UserAccessInformation;
use App\Traits\CheckPermissions;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Interview Confirmation | Savior Schools')]
class ConfirmInterview extends Component
{
    use CheckPermissions;

    public $student_appliances;

    protected $listeners = ['fetch' => 'fetchData'];

    public function confirmInterview($appliance_id, $status)
    {
        if ($status != 'Accept' and $status != 'Reject') {
            abort(422);
        }

        LivewireAlert::title('Are you sure?')
            ->withOptions([
                'input' => 'textarea',
                'inputPlaceholder' => 'Enter description',
            ])
            ->withConfirmButton('Confirm')
            ->onConfirm('setStatus', [
                'appliance_id' => $appliance_id,
                'status' => $status
            ])
            ->asConfirm()
            ->show();

        $this->skipRender();
    }


    public function setStatus($data)
    {
        if (auth()->user()->hasRole('Super Admin')) {
            $academicYears = AcademicYear::pluck('id')->toArray();
        } elseif (auth()->user()->hasRole('Principal')) {
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesP($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
        }
        $student_appliance = StudentApplianceStatus::whereIn('academic_year', $academicYears)
            ->where('id', $data['appliance_id'])
            ->where('interview_status', 'Pending For Principal Confirmation')
            ->first();

        if ($data['status'] == 'Accept') {
            $student_appliance->interview_status = 'Admitted';
        } else {
            $student_appliance->interview_status = 'Rejected';
        }
        $student_appliance->description = $data['value'];
        $student_appliance->save();
        session()->flash('success', 'Interview status updated successfully.');
    }

    public function mount()
    {
        if (!auth()->user()->can('confirm-interview')) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function render()
    {
        $this->fetchData();
        return view('livewire.branch-info.confirm-interview', [
            'studentAppliances' => $this->student_appliances
        ]);
    }

    public function fetchData()
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
            ->where('interview_status', 'Pending For Principal Confirmation')
            ->get();
    }
}
