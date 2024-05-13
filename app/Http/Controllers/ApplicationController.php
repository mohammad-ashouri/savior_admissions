<?php

namespace App\Http\Controllers;

use App\Models\Branch\ApplicationReservation;
use App\Models\Branch\Applications;
use App\Models\Branch\ApplicationTiming;
use App\Models\Branch\Interview;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\DocumentType;
use App\Models\Catalogs\Level;
use App\Models\Catalogs\PaymentMethod;
use App\Models\Catalogs\School;
use App\Models\Document;
use App\Models\Finance\ApplicationReservationsInvoices;
use App\Models\Finance\Discount;
use App\Models\GeneralInformation;
use App\Models\StudentInformation;
use App\Models\User;
use App\Models\UserAccessInformation;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;

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
        $this->middleware('permission:application-confirmation-menu-access', ['only' => ['confirmApplication']]);
    }

    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::find(auth()->user()->id);
        $applications = [];
        if ($me->hasRole('Parent')) {
            $myStudents = StudentInformation::where('guardian', $me->id)->pluck('student_id')->toArray();
            $applications = ApplicationReservation::with('applicationInfo')->with('studentInfo')->with('reservatoreInfo')->whereIn('student_id', $myStudents)->paginate(150);
        } elseif ($me->hasRole('Super Admin')) {
            $applications = ApplicationReservation::with('applicationInfo')->with('studentInfo')->with('reservatoreInfo')->paginate(150);
        } elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $applications = Applications::join('application_reservations', 'applications.id', '=', 'application_reservations.application_id')
                ->whereIn('applications.application_timing_id', $applicationTimings)
                ->where('applications.reserved', 1)
                ->where('application_reservations.payment_status', 1)
                ->pluck('applications.id')
                ->toArray();

            // Getting reservations of applications along with related information
            $applications = ApplicationReservation::with('applicationInfo')
                ->with('studentInfo')
                ->with('reservatoreInfo')
                ->whereIn('application_id', $applications)
                ->paginate(150);
        }

        if (empty($applications)) {
            $applications = [];
        }
        $this->logActivity(json_encode(['activity' => 'Getting Applications']), request()->ip(), request()->userAgent());

        return view('Applications.index', compact('applications', 'me'));

    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::find(auth()->user()->id);
        $activeAcademicYears = $this->getActiveAcademicYears();
        if ($me->hasRole('Parent')) {
            $myStudents = StudentInformation::where('guardian', $me->id)->orderBy('student_id')->get();
            $levels = Level::where('status', 1)->get();

        } elseif ($me->hasRole('Super Admin')) {
            $levels = Level::where('status', 1)->get();
            $myStudents = StudentInformation::
                join('general_informations','student_informations.student_id','=','general_informations.user_id')
            ->with('generalInformations')->orderBy('general_informations.last_name_en')->orderBy('general_informations.first_name_en')->get();
        } elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding levels by accessing academic year levels
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->get();
            $levels = [];
            foreach ($academicYears as $academicYear) {
                $levels[] = json_decode($academicYear['levels'], true);
            }
            $flattenedLevels = array_unique(array_merge(...$levels));
            $myStudents = StudentInformation::with('generalInformations')->orderBy('id')->get();

            $levels = Level::whereIn('id', $flattenedLevels)->get();
        }

        return view('Applications.create', compact('myStudents', 'levels', 'activeAcademicYears'));

    }

    public function show($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::find(auth()->user()->id);
        $applicationInfo = null;
        $applicationReservation = ApplicationReservation::find($id);
        if (empty($applicationReservation)) {
            $this->logActivity(json_encode(['activity' => 'Unauthorized Permission To Access Applications', 'entered_id' => $id]), request()->ip(), request()->userAgent());

            abort(403);
        }

        if ($me->hasRole('Parent')) {
            $myStudents = StudentInformation::where('guardian', $me->id)->pluck('student_id')->toArray();
            $applicationInfo = ApplicationReservation::with('levelInfo')->with('applicationInfo')->with('studentInfo')->with('reservatoreInfo')->with('applicationInvoiceInfo')->whereIn('student_id', $myStudents)->where('id', $id)->first();
        } elseif ($me->hasRole('Super Admin')) {
            $applicationInfo = ApplicationReservation::with('levelInfo')->with('applicationInfo')->with('studentInfo')->with('reservatoreInfo')->with('applicationInvoiceInfo')->where('id', $id)->first();
        } elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

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
                ->where('id', $id)->first();
            if (empty($applicationInfo)) {
                $this->logActivity(json_encode(['activity' => 'Application Not Found', 'entered_id' => $id]), request()->ip(), request()->userAgent());

                abort(403);
            }
        }
        $this->logActivity(json_encode(['activity' => 'Getting Application Informations', 'entered_id' => $id]), request()->ip(), request()->userAgent());

        return view('Applications.show', compact('applicationInfo'));
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        $me = User::find(auth()->user()->id);
        if (! $me->hasRole('Super Admin')) {
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);
            $checkAccessToApplication = ApplicationTiming::with('academicYearInfo')
                ->with('applications')
                ->join('academic_years', 'application_timings.academic_year', '=', 'academic_years.id')
                ->join('applications', 'application_timings.id', '=', 'applications.application_timing_id')
                ->whereIn('academic_years.school_id', $filteredArray)
                ->where('applications.id', $id)
                ->select('application_timings.*', 'academic_years.id as academic_year_id')
                ->first();
            if (! $checkAccessToApplication) {
                $this->logActivity(json_encode(['activity' => 'Failed To Delete Application', 'entered_id' => $id]), request()->ip(), request()->userAgent());

                return redirect()->back()
                    ->withErrors(['errors' => 'Delete Failed!']);
            }
        }

        $removeApplication = Applications::find($id)->delete();

        if (! $removeApplication) {
            $this->logActivity(json_encode(['activity' => 'Failed To Delete Application', 'entered_id' => $id]), request()->ip(), request()->userAgent());

            return redirect()->back()
                ->withErrors(['errors' => 'Delete Failed!']);
        }
        $this->logActivity(json_encode(['activity' => 'Application Deleted', 'entered_id' => $id]), request()->ip(), request()->userAgent());

        return redirect()->back()
            ->with('success', 'Application deleted!');
    }

    public function removeFromReserve($id): \Illuminate\Http\RedirectResponse
    {
        $me = User::find(auth()->user()->id);
        if (! $me->hasRole('Super Admin')) {
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);
            $checkAccessToApplication = ApplicationTiming::with('academicYearInfo')
                ->with('applications')
                ->join('academic_years', 'application_timings.academic_year', '=', 'academic_years.id')
                ->join('applications', 'application_timings.id', '=', 'applications.application_timing_id')
                ->whereIn('academic_years.school_id', $filteredArray)
                ->where('applications.id', $id)
                ->select('application_timings.*', 'academic_years.id as academic_year_id')
                ->first();
            if (! $checkAccessToApplication) {
                $this->logActivity(json_encode(['activity' => 'Failed To Remove Application From Reservation', 'entered_id' => $id]), request()->ip(), request()->userAgent());

                return redirect()->back()
                    ->withErrors(['errors' => 'Delete Failed!']);
            }
        }

        $removeApplicationReserve = Applications::find($id);
        $removeApplicationReserve->reserved = 0;

        $applicationReservations = ApplicationReservation::where('application_id', $removeApplicationReserve->id)->first();
        $applicationReservationInvoice = ApplicationReservationsInvoices::where('a_reservation_id', $applicationReservations->id)->delete();
        $applicationReservations->delete();
        if (! $removeApplicationReserve->save() or ! $applicationReservations or ! $applicationReservationInvoice) {
            $this->logActivity(json_encode(['activity' => 'Failed To Remove Application From Reservation', 'entered_id' => $id]), request()->ip(), request()->userAgent());

            return redirect()->back()
                ->withErrors(['errors' => 'Remove Application Reservation Failed!']);
        }
        $this->logActivity(json_encode(['activity' => 'Application Reservation Changed', 'entered_id' => $id]), request()->ip(), request()->userAgent());

        return redirect()->back()
            ->with('success', 'Application Reservation Changed!');
    }

    public function changeApplicationStatus($id): \Illuminate\Http\RedirectResponse
    {
        $me = User::find(auth()->user()->id);
        if (! $me->hasRole('Super Admin')) {
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);
            $checkAccessToApplication = ApplicationTiming::with('academicYearInfo')
                ->with('applications')
                ->join('academic_years', 'application_timings.academic_year', '=', 'academic_years.id')
                ->join('applications', 'application_timings.id', '=', 'applications.application_timing_id')
                ->whereIn('academic_years.school_id', $filteredArray)
                ->where('applications.id', $id)
                ->select('application_timings.*', 'academic_years.id as academic_year_id')
                ->first();
            if (! $checkAccessToApplication) {
                $this->logActivity(json_encode(['activity' => 'Failed To Change Application Status', 'entered_id' => $id]), request()->ip(), request()->userAgent());

                return redirect()->back()
                    ->withErrors(['errors' => 'Delete Failed!']);
            }
        }

        $changeApplicationStatus = Applications::find($id);
        $changeApplicationStatus->status = ($changeApplicationStatus->status == 0) ? 1 : 0;

        if (! $changeApplicationStatus->save()) {
            $this->logActivity(json_encode(['activity' => 'Failed To Change Application Status', 'entered_id' => $id]), request()->ip(), request()->userAgent());

            return redirect()->back()
                ->withErrors(['errors' => 'Change Interview Status Failed!']);
        }
        $this->logActivity(json_encode(['activity' => 'Interview Status Changed Successfully', 'entered_id' => $id]), request()->ip(), request()->userAgent());

        return redirect()->back()
            ->with('success', 'Interview Status Changed!');
    }

    public function getAcademicYearsByLevel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'level' => 'required|exists:levels,id',
            'student' => 'required|exists:general_informations,user_id',
        ]);
        if (! $request->student) {
            $this->logActivity(json_encode(['activity' => 'Getting Academic Years By Level Failed', 'errors' => 'Student Not Chosen']), request()->ip(), request()->userAgent());

            return response()->json(['error' => 'Select student first!'], 422);
        }
        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Getting Academic Years By Level Failed', 'errors' => json_encode($validator)]), request()->ip(), request()->userAgent());

            return response()->json(['error' => 'Error on choosing level!'], 422);
        }
        $level = $request->level;

        $studentGender = GeneralInformation::where('user_id', $request->student)->value('gender');

        switch ($studentGender) {
            case 'Male':
                $gender = 1;
                break;
            case 'Female':
                $gender = 2;
                break;
        }
        $schoolWithGender = School::where('gender', $gender)->get()->pluck('id')->toArray();
        $query = AcademicYear::where('status', 1)->whereJsonContains('levels', $level);
        if ($level != 1 and $level != 2) {
            $query->whereIn('school_id', $schoolWithGender);
        }
        $academicYears = $query->select('id', 'name')->get()->toArray();
        $this->logActivity(json_encode(['activity' => 'Getting Academic Years By Level', 'entered_level' => $level]), request()->ip(), request()->userAgent());

        return $academicYears;
    }

    public function getApplicationsByAcademicYear(Request $request): \Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse|array
    {
        $validator = Validator::make($request->all(), [
            'academic_year' => 'required|exists:academic_years,id',
        ]);
        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Getting Applications By Academic Year Failed', 'errors' => json_encode($validator)]), request()->ip(), request()->userAgent());

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
        $this->logActivity(json_encode(['activity' => 'Getting Applications By Academic Year', 'entered_academic_year' => $request->academic_year]), request()->ip(), request()->userAgent());

        if (!empty($applicationTimings) and $applicationTimings->isNotEmpty()) {
            return $applicationTimings;
        }
        return response()->json(['error'=>'No capacity was found for this academic year!'],404);
    }

    public function checkDateAndTimeToBeFreeApplication(Request $request): \Illuminate\Http\JsonResponse|int
    {
        $validator = Validator::make($request->all(), [
            'application' => 'required|exists:applications,id',
        ]);
        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Getting Date And Time To Be Free Application Failed', 'errors' => json_encode($validator)]), request()->ip(), request()->userAgent());

            return response()->json(['error' => 'Error on choosing application!'], 422);
        }

        $application = $request->application;
        $applicationCheck = Applications::where('status', 1)->where('reserved', 0)->find($application);
        if (empty($applicationCheck)) {
            $this->logActivity(json_encode(['activity' => 'Application Reserved A Few Moments Ago', 'application_id' => $application]), request()->ip(), request()->userAgent());

            return response()->json(['error' => 'Unfortunately, the selected application was reserved a few minutes ago. Please choose another application'], 422);
        }
        $this->logActivity(json_encode(['activity' => 'Getting Date And Time To Be Free Application', 'application_id' => $application]), request()->ip(), request()->userAgent());

        return 0;
    }

    public function preparationForApplicationPayment(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'date_and_time' => 'required|exists:applications,id',
            'academic_year' => 'required|exists:academic_years,id',
            'level' => 'required|exists:levels,id',
            'student' => 'required|exists:student_informations,student_id',
            'interview_type' => 'required',
        ]);
        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Preparation For Application Payment Failed', 'errors' => json_encode($validator)]), request()->ip(), request()->userAgent());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $me = User::find(auth()->user()->id);
        $student = $request->student;
        $level = $request->level;
        $academic_year = $request->academic_year;
        $dateAndTime = $request->date_and_time;
        $interviewType = $request->interview_type;

        $studentInfo = StudentInformation::where('guardian', $me->id)->where('student_id', $student)->first();

        if (empty($studentInfo)) {
            $this->logActivity(json_encode(['activity' => 'Preparation For Application Payment Failed', 'errors' => 'Access To Student Denied', 'parameters' => json_encode($request->all())]), request()->ip(), request()->userAgent());

            abort(403);
        }

        $academicYearInfo = AcademicYear::whereJsonContains('levels', $level)->find($academic_year);
        if (empty($academicYearInfo)) {
            $this->logActivity(json_encode(['activity' => 'Preparation For Application Payment Failed', 'errors' => 'Access To Academic Year Info Denied', 'parameters' => json_encode($request->all())]), request()->ip(), request()->userAgent());

            abort(403);
        }

        $applicationCheck = Applications::where('status', 1)->where('reserved', 0)->find($dateAndTime);
        if (empty($applicationCheck)) {
            $this->logActivity(json_encode(['activity' => 'Application Reserved A Few Moments Ago', 'parameters' => json_encode($request->all())]), request()->ip(), request()->userAgent());

            return redirect()->back()->withErrors('Unfortunately, the selected application was reserved a few minutes ago. Please choose another application')->withInput();
        }

        $applicationReservation = new ApplicationReservation();
        $applicationReservation->application_id = $dateAndTime;
        $applicationReservation->student_id = $studentInfo->student_id;
        $applicationReservation->reservatore = $me->id;
        $applicationReservation->level = $level;
        $applicationReservation->interview_type = $interviewType;

        if ($applicationReservation->save()) {
            $applications = Applications::find($dateAndTime);
            $applications->reserved = 1;
            $applications->save();
        }
        $this->logActivity(json_encode(['activity' => 'Application Payment Prepared To Pay Successfully', 'parameters' => json_encode($request->all())]), request()->ip(), request()->userAgent());

        return redirect()->route('PrepareToPayApplication', $applicationReservation->id);
    }

    public function prepareToPay($application_id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::find(auth()->user()->id);
        $checkApplication = null;
        if ($me->hasRole('Parent')) {
            $checkApplication = ApplicationReservation::with('applicationInfo')->where('reservatore', $me->id)->find($application_id);
            if (empty($checkApplication)) {
                $this->logActivity(json_encode(['activity' => 'Prepare To Pay Application Failed', 'application_id' => $application_id, 'errors' => 'Access Denied']), request()->ip(), request()->userAgent());

                abort(403);
            }
        }
        $createdAt = $checkApplication->created_at;

        $deadline = Carbon::parse($createdAt)->addHour()->toDateTimeString();
        $paymentMethods = PaymentMethod::where('status', 1)->get();
        $this->logActivity(json_encode(['activity' => 'Application Prepared To Pay', 'application_id' => $application_id]), request()->ip(), request()->userAgent());

        return view('Applications.application_payment', compact('checkApplication', 'deadline', 'paymentMethods'));
    }

    /**
     * @throws Exception
     */
    public function payApplicationFee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|exists:payment_methods,id',
            'id' => 'required|exists:application_reservations,id',
        ]);
        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Application Payment Failed', 'errors' => json_encode($validator)]), request()->ip(), request()->userAgent());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $applicationInformation = ApplicationReservation::with('applicationInfo')->find($request->id);

        switch ($request->payment_method) {
            case 1:
                $validator = Validator::make($request->all(), [
                    'document_file' => 'required|file|mimes:jpg,bmp,pdf,jpeg,png',
                ]);
                if ($validator->fails()) {
                    $this->logActivity(json_encode(['activity' => 'Application Payment Failed', 'errors' => json_encode($validator)]), request()->ip(), request()->userAgent());

                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $path = $request->file('document_file')->store('public/uploads/Documents/'.auth()->user()->id);

                $document = new Document();
                $document->user_id = $applicationInformation->student_id;
                $document->document_type_id = DocumentType::where('name', 'Deposit slip')->first()->id;
                $document->src = $path;
                $document->save();

                $document = new Document();
                $document->user_id = auth()->user()->id;
                $document->document_type_id = DocumentType::where('name', 'Deposit slip')->first()->id;
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
                        $this->logActivity(json_encode(['activity' => 'Application Reserved Successfully', 'reservation_invoice_id' => $applicationInformation->id]), request()->ip(), request()->userAgent());

                        return redirect()->route('Applications.index')->with('success', 'Application reserved successfully!');
                    }
                }

                $application = Applications::find($applicationReservationInvoice->reservationInfo->application_id);
                $application->reserved = 1;
                $application->save();
                break;
            case 2:
                $amount = $applicationInformation->applicationInfo->applicationTimingInfo->fee;
                // Create new invoice.
                $invoice = (new Invoice)->amount($amount);

                return Payment::via('behpardakht')->callbackUrl(env('APP_URL').'/VerifyApplicationPayment')->purchase(
                    $invoice,
                    function ($driver, $transactionID) use ($amount, $applicationInformation) {
                        $dataInvoice = new \App\Models\Invoice();
                        $dataInvoice->user_id = auth()->user()->id;
                        $dataInvoice->type = 'Application Reservation';
                        $dataInvoice->amount = $amount;
                        $dataInvoice->description = json_encode(['amount' => $amount, 'reservation_id' => $applicationInformation->id], true);
                        $dataInvoice->transaction_id = $transactionID;
                        $dataInvoice->save();
                    }
                )->pay()->render();

                break;
            default:
                $this->logActivity(json_encode(['activity' => 'Application Payment Failed', 'errors' => json_encode($validator)]), request()->ip(), request()->userAgent());

                abort(403);
        }
    }

    public function confirmApplication()
    {
        $me = User::find(auth()->user()->id);
        if ($me->hasRole('Super Admin')) {
            $academicYears = AcademicYear::pluck('id')->toArray();
        } elseif ($me->hasRole('Principal')) {
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesP($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
        }
        $studentAppliances = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->whereIn('academic_year', $academicYears)->where('interview_status', 'Pending For Principal Confirmation')->paginate(150);
        $this->logActivity(json_encode(['activity' => 'Getting Appliance List']), request()->ip(), request()->userAgent());

        return view('BranchInfo.ConfirmAppliance.index', compact('studentAppliances'));
    }

    public function showApplicationConfirmation($application_id, $appliance_id)
    {
        $me = User::find(auth()->user()->id);
        if ($me->hasRole('Super Admin')) {
            $academicYears = AcademicYear::pluck('id')->toArray();
        } elseif ($me->hasRole('Principal')) {
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesP($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
        }
        $studentAppliance = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->whereIn('academic_year', $academicYears)->where('id', $appliance_id)->where('interview_status', 'Pending For Principal Confirmation')->first();

        if (empty($studentAppliance)) {
            $this->logActivity(json_encode(['activity' => 'Getting Appliance Interview Form Failed']), request()->ip(), request()->userAgent());

            abort(403);
        }
        $interviewsForms = Interview::where('application_id', $application_id)->pluck('interview_form')->toArray();
        $interviewFields = [];
        foreach ($interviewsForms as $interviewFormArray) {
            $interviewFormData = json_decode($interviewFormArray, true);

            $interviewFields = array_merge($interviewFields, $interviewFormData);
        }
        $applicationReservation = ApplicationReservation::with('levelInfo')->with('studentInfo')->where('student_id', $studentAppliance->studentInfo->id)->where('payment_status', 1)->latest()->first();
        $discounts = Discount::with('allDiscounts')
            ->where('academic_year', $studentAppliance->academicYearInfo->id)
            ->join('discount_details', 'discounts.id', '=', 'discount_details.discount_id')
            ->where('discount_details.status', 1)
            ->where('discount_details.interviewer_permission', 1)
            ->get();
        $this->logActivity(json_encode(['activity' => 'Getting Appliance Interview Form']), request()->ip(), request()->userAgent());

        return view('BranchInfo.Interviews.Forms.1.ApplianceConfirmation.Show', compact('studentAppliance', 'interviewFields', 'applicationReservation', 'discounts'));
    }

    public function confirmStudentAppliance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appliance_id' => 'required|exists:student_appliance_statuses,id',
            'application_id' => 'required|exists:applications,id',
            'type' => 'required|in:Accept,Reject',
        ]);

        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Confirm Student Appliance Failed', 'values' => $request->all(), 'errors' => json_encode($validator)]), request()->ip(), request()->userAgent());

            return redirect()->back()->withErrors($validator)->withInput();
        }
        $applicationInfo = Applications::find($request->application_id);
        $reservatoreMobile = $applicationInfo->reservationInfo->reservatoreInfo->mobile;

        switch ($request->type) {
            case 'Accept':
                StudentApplianceStatus::find($request->appliance_id)->update(['interview_status' => 'Admitted', 'documents_uploaded' => 0]);
                $messageText = "Your interview has been successfully accepted. You have up to 72 hours to upload documents. Please upload documents on the dashboard page.\nSavior Schools";
                $this->sendSMS($reservatoreMobile, $messageText);
                break;
            case 'Reject':
                $reservationID = $applicationInfo->reservationInfo->id;
                $messageText = "Your application with reservation id ($reservationID) has been rejected.\nSavior Schools";
                $this->sendSMS($reservatoreMobile, $messageText);
                StudentApplianceStatus::find($request->appliance_id)->update(['interview_status' => 'Rejected']);
                break;
        }
        Applications::find($request->application_id)->update(['Interviewed' => 1]);

        $applianceStatus = StudentApplianceStatus::with('studentInfo')->find($request->appliance_id);

        return redirect()->route('Application.ConfirmApplicationList')
            ->with('success', 'Appliance Status Confirmed For This Student: '.$applianceStatus->studentInfo->generalInformationInfo->first_name_en.' '.$applianceStatus->studentInfo->generalInformationInfo->last_name_en.' - Chosen status: '.$request->type.'ed');
    }
}
