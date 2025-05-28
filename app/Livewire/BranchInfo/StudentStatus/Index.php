<?php

namespace App\Livewire\BranchInfo\StudentStatus;

use App\Models\Branch\Dropout;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\Finance\Tuition;
use App\Models\StudentInformation;
use App\Models\UserAccessInformation;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\LivewireFilepond\WithFilePond;

class Index extends Component
{
    use WithPagination, WithFilePond;

    public $academic_years = [];
    #[Url]
    #[Validate('required|integer|exists:academic_years,id')]
    public $academic_year = '';
    public $students;
    public $description;
    public $files;
    public $selected_student;
    protected $queryString = ['academic_year'];

    /**
     * Mount the component
     * @return void
     */
    public function mount(): void
    {
        $students = [];
        if (auth()->user()->hasRole('Super Admin')) {
            $this->academic_years = AcademicYear::get();
        } elseif (auth()->user()->hasExactRoles(['Parent'])) {
            $students = StudentInformation::whereGuardian(auth()->user()->id)
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
            //            $students = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->with('documentSeconder')
            //                ->whereIn('academic_year', $academicYears)
            //                ->orderBy('academic_year', 'desc')->paginate(150);
            $this->academic_years = AcademicYear::whereIn('id', $academicYears)->get();
        }
    }

    /**
     * Fetch students by academic year
     * @return void
     */
    public function search(): void
    {
        $this->fetchStudents();
        $this->dispatch('initialize-data-table');
    }

    public function fetchStudents()
    {
        $this->validate();

        $students = [];
        if (auth()->user()->hasRole('Super Admin')) {
            $this->students = StudentApplianceStatus::with(['studentInfo', 'academicYearInfo', 'documentSeconder'])
                ->where('academic_year', $this->academic_year)
                ->orderBy('academic_year', 'desc')->get();
        } elseif (auth()->user()->hasExactRoles(['Parent'])) {
            $data = StudentInformation::whereGuardian(auth()->user()->id)
                ->with('studentInfo')
                ->with('nationalityInfo')
                ->with('identificationTypeInfo')
                ->with('generalInformations')
                ->whereAcademicYear($this->academic_year);

            $students = $data->orderBy('academic_year', 'desc')->get();
        } elseif (auth()->user()->hasRole(['Principal', 'Admissions Officer'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
            $data = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->with('documentSeconder')
                ->whereIn('academic_year', $academicYears)
                ->whereAcademicYear($this->academic_year);
            $this->students = $data->orderBy('academic_year', 'desc')->get();
        }
    }

    /**
     * Open dropout modal
     * @param $student_id
     * @return void
     */
    public function openDropoutModal($student_id): void
    {
        $this->selected_student = StudentApplianceStatus::findOrFail($student_id);

        if (auth()->user()->hasRole(['Principal', 'Financial Manager'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
        }

        $this->description = '';
        $this->files = null;
    }

    /**
     * Dropout the student
     * @return void
     */
    public function dropout(): void
    {
        $this->validate([
            'description' => 'required|string',
            'files' => 'nullable|file|max:4096',
            'files.*' => 'mimes:pdf,jpg,bmp,jpeg,png',
        ]);

        $paths = [];
        if ($this->files) {
            try {
                $path = $this->files->store('public/uploads/Documents/' . $this->selected_student->student_id . '/dropouts');
                $paths[] = str_replace('public/', '', $path);
            } catch (\Exception $e) {
                LivewireAlert::error('Error: ' . $e->getMessage());
                return;
            }
        }

        if ($this->selected_student) {
            $dropout = new Dropout();
            $dropout->appliance_id = $this->selected_student->id;
            $dropout->description = $this->description;
            $dropout->files = json_encode($paths);
            $dropout->user = auth()->user()->id;
            $dropout->save();

            $this->selected_student->approval_status = 3;
            $this->selected_student->save();

            $this->closeDropoutModal();
            LivewireAlert::title('Successful')
                ->text("Student has been marked as dropout successfully.")
                ->withCancelButton('Close')
                ->success()
                ->show();
            $this->fetchStudents();
        }

    }

    /**
     * Close dropout modal
     * @return void
     */
    public function closeDropoutModal(): void
    {
        $this->selected_student = null;
        $this->description = '';
        $this->files = null;
    }

    /**
     * Render the component
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function render(): Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View|Application
    {
        if (request()->has('academic_year')) {
            $this->academic_year = request('academic_year');
            $this->fetchStudents();
        }
        return view('livewire.branch-info.student-status.index');
    }
}
