<?php

namespace App\Http\Controllers;

use App\Models\Branch\ApplicationReservation;
use App\Models\Branch\Applications;
use App\Models\Branch\ApplicationTiming;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\Level;
use App\Models\Catalogs\PaymentMethod;
use App\Models\Document;
use App\Models\Finance\ApplicationReservationsInvoices;
use App\Models\StudentInformation;
use App\Models\User;
use App\Models\UserAccessInformation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:applications-list', ['only' => ['index']]);
        $this->middleware('permission:new-application-reserve', ['only' => ['create', 'store']]);
        $this->middleware('permission:show-application-reserve', ['only' => ['show']]);
        $this->middleware('permission:edit-application-reserve', ['only' => ['edit', 'update']]);
        $this->middleware('permission:remove-application', ['only' => ['destroy']]);
        $this->middleware('permission:remove-application-from-reserve', ['only' => ['removeFromReserve']]);
        $this->middleware('permission:change-status-of-application', ['only' => ['changeInterviewStatus']]);
    }

    public function index()
    {
        $me = User::find(session('id'));
        $applications = [];
        if ($me->hasRole('Parent(Father)') or $me->hasRole('Parent(Mother)')) {
            $myStudents = StudentInformation::where('guardian', $me->id)->pluck('student_id')->toArray();
            $applications = ApplicationReservation::with('applicationInfo')->with('studentInfo')->with('reservatoreInfo')->whereIn('student_id', $myStudents)->paginate(30);
        } elseif ($me->hasRole('Super Admin')) {
            $applications = ApplicationReservation::with('applicationInfo')->with('studentInfo')->with('reservatoreInfo')->paginate(30);
        } elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $principalAccess = explode('|', $myAllAccesses->principal);
            $admissionsOfficerAccess = explode('|', $myAllAccesses->admissions_officer);
            $filteredArray = array_filter(array_unique(array_merge($principalAccess, $admissionsOfficerAccess)));

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $applications = Applications::whereIn('application_timing_id', $applicationTimings)
                ->pluck('id')
                ->toArray();

            // Getting reservations of applications along with related information
            $applications = ApplicationReservation::with('applicationInfo')
                ->with('studentInfo')
                ->with('reservatoreInfo')
                ->whereIn('application_id', $applications)
                ->paginate(30);
        }

        if (empty($applications)) {
            $applications = [];
        }

        return view('Applications.index', compact('applications','me'));

    }

    public function create()
    {
        $me = User::find(session('id'));
        if ($me->hasRole('Parent(Father)') or $me->hasRole('Parent(Mother)')) {
            $myStudents = StudentInformation::with('generalInformations')->where('guardian', $me->id)->orderBy('id')->get();
            $levels = Level::where('status', 1)->get();

            return view('Applications.create', compact('myStudents', 'levels'));
        }
    }

    public function show($id)
    {
        $me = User::find(session('id'));
        $applicationReservation = ApplicationReservation::find($id);
        if (empty($applicationReservation)) {
            abort(403);
        }

        if ($me->hasRole('Parent(Father)') or $me->hasRole('Parent(Mother)')) {
            $myStudents = StudentInformation::where('guardian', $me->id)->pluck('student_id')->toArray();
            $applicationInfo = ApplicationReservation::with('levelInfo')->with('applicationInfo')->with('studentInfo')->with('reservatoreInfo')->with('applicationInvoiceInfo')->whereIn('student_id', $myStudents)->where('id', $id)->first();
        } elseif ($me->hasRole('Super Admin')) {
            $applicationInfo = ApplicationReservation::with('levelInfo')->with('applicationInfo')->with('studentInfo')->with('reservatoreInfo')->with('applicationInvoiceInfo')->where('id', $id)->first();
        }elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $principalAccess = explode('|', $myAllAccesses->principal);
            $admissionsOfficerAccess = explode('|', $myAllAccesses->admissions_officer);
            $filteredArray = array_filter(array_unique(array_merge($principalAccess, $admissionsOfficerAccess)));

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $applications = Applications::whereIn('application_timing_id', $applicationTimings)
                ->pluck('id')
                ->toArray();

            // Getting reservations of applications along with related information
            $applicationInfo = ApplicationReservation::with('applicationInfo')
                ->with('studentInfo')
                ->with('reservatoreInfo')
                ->whereIn('application_id', $applications)
                ->where('id',$id)->first();
            if (empty($applicationInfo)){
                abort(403);
            }
        }

        return view('Applications.show', compact('applicationInfo'));
    }

    public function destroy($id)
    {
        $me = User::find(session('id'));
        $checkAccessToApplication = [];
        if (! $me->hasRole('Super Admin')) {
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            if (isset($myAllAccesses->principal) or isset($myAllAccesses->admissions_officer)) {
                $principalAccess = explode('|', $myAllAccesses->principal);
                $admissionsOfficerAccess = explode('|', $myAllAccesses->admissions_officer);
                $filteredArray = array_filter(array_unique(array_merge($principalAccess, $admissionsOfficerAccess)));
                $checkAccessToApplication = ApplicationTiming::with('academicYearInfo')
                    ->with('applications')
                    ->join('academic_years', 'application_timings.academic_year', '=', 'academic_years.id')
                    ->join('applications', 'application_timings.id', '=', 'applications.application_timing_id')
                    ->whereIn('academic_years.school_id', $filteredArray)
                    ->where('applications.id', $id)
                    ->select('application_timings.*', 'academic_years.id as academic_year_id')
                    ->first();
                if (! $checkAccessToApplication) {
                    return redirect()->back()
                        ->withErrors(['errors' => 'Delete Failed!']);
                }
            }
        }

        $removeApplication = Applications::find($id)->delete();

        if (! $removeApplication) {
            return redirect()->back()
                ->withErrors(['errors' => 'Delete Failed!']);
        }

        return redirect()->back()
            ->with('success', 'Application deleted!');
    }

    public function removeFromReserve($id)
    {
        $me = User::find(session('id'));
        if (! $me->hasRole('Super Admin')) {
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            if (isset($myAllAccesses->principal) or isset($myAllAccesses->admissions_officer)) {
                $principalAccess = explode('|', $myAllAccesses->principal);
                $admissionsOfficerAccess = explode('|', $myAllAccesses->admissions_officer);
                $filteredArray = array_filter(array_unique(array_merge($principalAccess, $admissionsOfficerAccess)));
                $checkAccessToApplication = ApplicationTiming::with('academicYearInfo')
                    ->with('applications')
                    ->join('academic_years', 'application_timings.academic_year', '=', 'academic_years.id')
                    ->join('applications', 'application_timings.id', '=', 'applications.application_timing_id')
                    ->whereIn('academic_years.school_id', $filteredArray)
                    ->where('applications.id', $id)
                    ->select('application_timings.*', 'academic_years.id as academic_year_id')
                    ->first();
                if (! $checkAccessToApplication) {
                    return redirect()->back()
                        ->withErrors(['errors' => 'Delete Failed!']);
                }
            }
        }

        $removeApplicationReserve = Applications::find($id);
        $removeApplicationReserve->reserved = 0;

        $applicationReservations=ApplicationReservation::where('application_id',$removeApplicationReserve->id)->first();
        $applicationReservationInvoice=ApplicationReservationsInvoices::where('a_reservation_id',$applicationReservations->id)->delete();
        $applicationReservations->delete();
        if (! $removeApplicationReserve->save() or ! $applicationReservations or !$applicationReservationInvoice) {
            return redirect()->back()
                ->withErrors(['errors' => 'Remove Application Reservation Failed!']);
        }

        return redirect()->back()
            ->with('success', 'Application Reservation Changed!');
    }

    public function changeApplicationStatus($id)
    {
        $me = User::find(session('id'));
        if (! $me->hasRole('Super Admin')) {
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            if (isset($myAllAccesses->principal) or isset($myAllAccesses->admissions_officer)) {
                $principalAccess = explode('|', $myAllAccesses->principal);
                $admissionsOfficerAccess = explode('|', $myAllAccesses->admissions_officer);
                $filteredArray = array_filter(array_unique(array_merge($principalAccess, $admissionsOfficerAccess)));
                $checkAccessToApplication = ApplicationTiming::with('academicYearInfo')
                    ->with('applications')
                    ->join('academic_years', 'application_timings.academic_year', '=', 'academic_years.id')
                    ->join('applications', 'application_timings.id', '=', 'applications.application_timing_id')
                    ->whereIn('academic_years.school_id', $filteredArray)
                    ->where('applications.id', $id)
                    ->select('application_timings.*', 'academic_years.id as academic_year_id')
                    ->first();
                if (! $checkAccessToApplication) {
                    return redirect()->back()
                        ->withErrors(['errors' => 'Delete Failed!']);
                }
            }
        }

        $changeApplicationStatus = Applications::find($id);
        if ($changeApplicationStatus->status == 0) {
            $changeApplicationStatus->status = 1;
        } else {
            $changeApplicationStatus->status = 0;
        }

        if (! $changeApplicationStatus->save()) {
            return redirect()->back()
                ->withErrors(['errors' => 'Remove Interview Status Failed!']);
        }

        return redirect()->back()
            ->with('success', 'Interview Status Changed!');
    }

    public function getAcademicYearsByLevel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'level' => 'required|exists:levels,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Error on choosing level!'], 422);
        }
        $level = $request->level;
        $academicYears = AcademicYear::where('status', 1)->whereJsonContains('levels', $level)->select('id', 'name')->get()->toArray();

        return $academicYears;
    }

    public function getApplicationsByAcademicYear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year' => 'required|exists:academic_years,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Error on choosing academic year!'], 422);
        }

        $applicationTimings = ApplicationTiming::with('applications')
            ->join('applications', 'application_timings.id', '=', 'applications.application_timing_id')
            ->where('application_timings.academic_year', $request->academic_year)
            ->where('applications.status', 1)
            ->where('applications.reserved', 0)
            ->select('applications.*', 'application_timings.id as application_timings_id')
            ->orderBy('application_timings.start_date')
            ->get();

        return $applicationTimings;
    }

    public function checkDateAndTimeToBeFreeApplication(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'application' => 'required|exists:applications,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Error on choosing application!'], 422);
        }

        $application = $request->application;
        $applicationCheck = Applications::where('status', 1)->where('reserved', 0)->find($application);
        if (empty($applicationCheck)) {
            return response()->json(['error' => 'Unfortunately, the selected application was reserved a few minutes ago. Please choose another application'], 422);
        }

        return 0;
    }

    public function preparationForApplicationPayment(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'date_and_time' => 'required|exists:applications,id',
            'academic_year' => 'required|exists:academic_years,id',
            'level' => 'required|exists:levels,id',
            'student' => 'required|exists:student_informations,id',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $me = User::find(session('id'));
        $student= $request->student;
        $level = $request->level;
        $academic_year = $request->academic_year;
        $dateAndTime = $request->date_and_time;

        $studentInfo = StudentInformation::where('guardian', $me->id)->where('id', $student)->first();

        if (empty($studentInfo)) {
            abort(403);
        }

        $academicYearInfo = AcademicYear::whereJsonContains('levels', $level)->find($academic_year);
        if (empty($academicYearInfo)) {
            abort(403);
        }

        $applicationCheck = Applications::where('status', 1)->where('reserved', 0)->find($dateAndTime);
        if (empty($applicationCheck)) {
            return redirect()->back()->withErrors('Unfortunately, the selected application was reserved a few minutes ago. Please choose another application')->withInput();
        }

        $applicationReservation = new ApplicationReservation();
        $applicationReservation->application_id = $dateAndTime;
        $applicationReservation->student_id = $studentInfo->student_id;
        $applicationReservation->reservatore = $me->id;
        $applicationReservation->level = $level;

        if ($applicationReservation->save()) {
            $applications = Applications::find($dateAndTime);
            $applications->reserved = 1;
            $applications->save();
        }

        return redirect()->route('PrepareToPayApplication', $applicationReservation->id);
    }

    public function prepareToPay($application_id)
    {
        $me = User::find(session('id'));

        if ($me->hasRole('Parent(Father)') or $me->hasRole('Parent(Mother)')) {
            $checkApplication = ApplicationReservation::with('applicationInfo')->where('reservatore', $me->id)->find($application_id);
            if (empty($checkApplication)) {
                abort(403);
            }
        }
        $createdAt = $checkApplication->created_at;

        $deadline = Carbon::parse($createdAt)->addHour()->toDateTimeString();
        $paymentMethods = PaymentMethod::where('status', 1)->get();

        return view('Applications.application_payment', compact('checkApplication', 'deadline', 'paymentMethods'));
    }

    public function payApplicationFee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|exists:payment_methods,id',
            'id' => 'required|exists:application_reservations,id',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $applicationInformation = ApplicationReservation::find($request->id);

        switch ($request->payment_method) {
            case 1:
                $validator = Validator::make($request->all(), [
                    'document_file' => 'required|file|mimes:jpg,bmp,pdf,jpeg,png',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $path = $request->file('document_file')->store('public/uploads/Documents/'.session('id'));

                $document = new Document();
                $document->user_id = $applicationInformation->student_id;
                $document->document_type_id = 243;
                $document->src = $path;
                $document->save();

                if ($document) {
                    $applicationReservationInvoice = new ApplicationReservationsInvoices();
                    $applicationReservationInvoice->a_reservation_id = $request->id;
                    $applicationReservationInvoice->payment_information = json_encode([
                        'payment_method' => $request->payment_method,
                        'document_id' => $document->id,
                    ], true);
                    $applicationReservationInvoice->description = $request->description;
                    $applicationReservationInvoice->save();

                    if ($applicationReservationInvoice) {
                        $applicationInformation->payment_status = 2; //For Pending
                        $applicationInformation->save();

                        return redirect()->route('Applications.index')->with('success', 'Application reserved successfully!');
                    }
                }
                break;
            case 2:
                break;
            default:
                abort(403);
        }
    }
}
