<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Branch\ApplicationReservation;
use App\Models\Branch\Applications;
use App\Models\Branch\ApplicationTiming;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\Level;
use App\Models\Catalogs\PaymentMethod;
use App\Models\StudentInformation;
use App\Models\User;
use App\Models\UserAccessInformation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApplicationReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:reservation-invoice-list', ['only' => ['index']]);
        $this->middleware('permission:reservation-invoice-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:reservation-invoice-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:reservation-invoice-delete', ['only' => ['destroy']]);
        $this->middleware('permission:reservation-invoice-show', ['only' => ['show']]);
        $this->middleware('permission:reservation-invoice-search', ['only' => ['searchApplicationTiming']]);
        $this->middleware('permission:reservation-payment-status-change', ['only' => ['changeApplicationPaymentStatus']]);
    }

    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::find(session('id'));
        $applications = $principalAccess = $financialManagerAccess = [];

        if ($me->hasRole('Super Admin')) {
            $applications = ApplicationReservation::with('applicationInfo')
                ->with('studentInfo')
                ->with('reservatoreInfo')
                ->with('applicationInvoiceInfo')
                ->join('applications', 'application_reservations.application_id', '=', 'applications.id')
                ->join('application_timings', 'applications.application_timing_id', '=', 'application_timings.id')
                ->select('application_reservations.*', 'application_reservations.id as application_reservations_id')
                ->orderBy('application_timings.academic_year', 'desc')
                ->paginate(30);
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

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
                ->with('applicationInvoiceInfo')
                ->whereIn('application_id', $applications)
                ->join('applications', 'application_reservations.application_id', '=', 'applications.id')
                ->join('application_timings', 'applications.application_timing_id', '=', 'application_timings.id')
                ->select('application_reservations.*', 'application_reservations.id as application_reservations_id')
                ->orderBy('application_timings.academic_year', 'desc')
                ->paginate(30);
        }

        if ($applications->isEmpty()) {
            $applications = [];
        }

        $paymentMethods = PaymentMethod::where('status', 1)->get();
        if ($me->hasRole('Super Admin')) {
            $academicYears = AcademicYear::get();
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->get();
        }
        $this->logActivity(json_encode(['activity' => 'Getting Application Reservation Invoices']), request()->ip(), request()->userAgent());

        return view('Finance.ApplicationReservationInvoices.index', compact('applications', 'paymentMethods', 'academicYears'));

    }

    public function create()
    {
        $me = User::find(session('id'));
        if ($me->hasRole('Parent(Father)') or $me->hasRole('Parent(Mother)')) {
            $myStudents = StudentInformation::with('generalInformations')->where('guardian', $me->id)->orderBy('id')->get();
            $levels = Level::where('status', 1)->get();

            return view('Finance.ApplicationReservationInvoices.create', compact('myStudents', 'levels'));
        }
        abort(403);
    }

    public function show($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $applicationInfo = $principalAccess = $financialManagerAccess = [];

        $me = User::find(session('id'));
        $applicationReservation = ApplicationReservation::find($id);
        if (empty($applicationReservation)) {
            $this->logActivity(json_encode(['activity' => 'Access Denied To Show Application Reservation Invoice', 'application_reservation_id' => $id, 'status' => 'Not Found']), request()->ip(), request()->userAgent());

            abort(403);
        }

        if ($me->hasRole('Parent(Father)') or $me->hasRole('Parent(Mother)')) {
            $myStudents = StudentInformation::where('guardian', $me->id)->pluck('student_id')->toArray();
            $applicationInfo = ApplicationReservation::with('levelInfo')->with('applicationInfo')->with('studentInfo')->with('reservatoreInfo')->with('applicationInvoiceInfo')->whereIn('student_id', $myStudents)->where('id', $id)->first();
        } elseif ($me->hasRole('Super Admin')) {
            $applicationInfo = ApplicationReservation::with('levelInfo')->with('applicationInfo')->with('studentInfo')->with('reservatoreInfo')->with('applicationInvoiceInfo')->where('id', $id)->first();
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

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
                abort(403);
            }
        }
        $this->logActivity(json_encode(['activity' => 'Getting Application Reservation Informations', 'application_reservation_id' => $id]), request()->ip(), request()->userAgent());

        return view('Finance.ApplicationReservationInvoices.show', compact('applicationInfo'));
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        $me = User::find(session('id'));
        $checkAccessToApplication = [];
        if (! $me->hasRole('Super Admin')) {
            if (isset($myAllAccesses->principal) or isset($myAllAccesses->admissions_officer)) {
                $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
                $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);
                $checkAccessToApplication = ApplicationTiming::with('academicYearInfo')
                    ->with('applications')
                    ->join('academic_years', 'application_timings.academic_year', '=', 'academic_years.id')
                    ->join('applications', 'application_timings.id', '=', 'applications.application_timing_id')
                    ->whereIn('academic_years.school_id', $filteredArray)
                    ->where('applications.id', $id)
                    ->select('application_timings.*', 'academic_years.id as academic_year_id')
                    ->first();
                if (! $checkAccessToApplication) {
                    $this->logActivity(json_encode(['activity' => 'Destroying Application Reservation Failed', 'application_reservation_id' => $id]), request()->ip(), request()->userAgent());

                    return redirect()->back()
                        ->withErrors(['errors' => 'Delete Failed!']);
                }
            }
        }

        $removeApplication = Applications::find($id)->delete();

        if (! $removeApplication) {
            $this->logActivity(json_encode(['activity' => 'Destroying Application Reservation Failed', 'application_reservation_id' => $id]), request()->ip(), request()->userAgent());

            return redirect()->back()
                ->withErrors(['errors' => 'Delete Failed!']);
        }
        $this->logActivity(json_encode(['activity' => 'Application Reservation Successfully Destroyed', 'application_reservation_id' => $id]), request()->ip(), request()->userAgent());

        return redirect()->back()
            ->with('success', 'Application deleted!');
    }

    public function changeApplicationPaymentStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $me = User::find(session('id'));
        $applicationID = $request->application_id;
        $applicationStatus = $request->status;

        if ($me->hasRole('Super Admin')) {
            $applicationInfo = ApplicationReservation::with('levelInfo')->with('applicationInfo')->with('studentInfo')->with('reservatoreInfo')->with('applicationInvoiceInfo')->where('id', $applicationID)->first();
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

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
                ->where('id', $applicationID)->first();

        }

        if (empty($applicationInfo)) {
            $this->logActivity(json_encode(['activity' => 'Changing Application Payment Status Failed', 'application_id' => $applicationID, 'application_status' => $applicationStatus, 'message' => $applicationInfo]), request()->ip(), request()->userAgent());

            return response()->json(['message' => $applicationInfo], 422);
        }

        $applicationReservation = ApplicationReservation::with('applicationInfo')->find($applicationID);
        if (empty($applicationReservation)) {
            $this->logActivity(json_encode(['activity' => 'Changing Application Payment Status Failed', 'application_id' => $applicationID, 'application_status' => $applicationStatus, 'message' => $applicationInfo]), request()->ip(), request()->userAgent());

            return response()->json(['message' => 'Application not found!'], 422);
        }

        $applicationReservation->payment_status = $applicationStatus;
        $applicationReservation->save();
        $this->logActivity(json_encode(['activity' => 'Application Payment Status Changed', 'application_id' => $applicationID, 'application_status' => $applicationStatus]), request()->ip(), request()->userAgent());

        $applianceStatus = StudentApplianceStatus::where('student_id', $applicationReservation->student_id)->where('academic_year', $applicationReservation->applicationInfo->applicationTimingInfo->academic_year)->first();
        if ($applicationStatus == 1) {
            if (empty($applianceStatus)) {
                $applianceStatus = new StudentApplianceStatus();
                $applianceStatus->student_id = $applicationReservation->student_id;
                $applianceStatus->academic_year = $applicationReservation->applicationInfo->applicationTimingInfo->academic_year;
                $applianceStatus->interview_status = 'Pending First Interview';
            } elseif ($applianceStatus->interview_status == 'Rejected') {
                $applianceStatus->interview_status = 'Pending First Interview';
            }
            $applianceStatus->save();

        }

        return response()->json(['message' => 'Application payment status changed!'], 200);
    }

    public function searchReservationInvoices(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::find(session('id'));
        $applications = $academicYears = [];

        if ($me->hasRole('Super Admin')) {
            $applications = ApplicationReservation::with('applicationInfo')
                ->with('studentInfo')
                ->with('reservatoreInfo')
                ->with('applicationInvoiceInfo')
                ->join('applications', 'application_reservations.application_id', '=', 'applications.id')
                ->join('application_timings', 'applications.application_timing_id', '=', 'application_timings.id');
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

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
                ->with('applicationInvoiceInfo')
                ->whereIn('application_id', $applications)
                ->join('applications', 'application_reservations.application_id', '=', 'applications.id')
                ->join('application_timings', 'applications.application_timing_id', '=', 'application_timings.id');
        }

        if (! empty($request->academic_year)) {
            $applications->where('application_timings.academic_year', $request->academic_year);
        }
        if (! empty($request->date_of_payment) or ! empty($request->payment_method) or ! empty($request->status)) {
            $applications = $applications->join('application_reservations_invoices', 'application_reservations.id', '=', 'application_reservations_invoices.a_reservation_id');
            if (! empty($request->date_of_payment)) {
                $applications->where('application_reservations.payment_status', 1);
                $date = Carbon::parse($request->date_of_payment)->toDateString();
                $applications->whereDate('application_reservations_invoices.created_at', $date);
            }
            if (! empty($request->payment_method)) {
                $applications->whereJsonContains('application_reservations_invoices.payment_information->payment_method', $request->payment_method);
            }
            if (! empty($request->status)) {
                $applications->where('application_reservations.payment_status', '=', $request->status);
            } else {
                $applications->where('application_reservations.payment_status', '=', 0);
            }
        }

        $applications = $applications->orderBy('application_timings.academic_year', 'desc')
            ->paginate(30);
        $applications->appends(request()->query())->links();

        if (! isset($applications) and $applications->isEmpty()) {
            $applications = [];
        }

        $paymentMethods = PaymentMethod::where('status', 1)->get();

        if ($me->hasRole('Super Admin')) {
            $academicYears = AcademicYear::get();
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->get();
        }
        $this->logActivity(json_encode(['activity' => 'Searching In Application Reservation Invoice ', 'search_parameters' => json_encode($request->all())]), request()->ip(), request()->userAgent());

        return view('Finance.ApplicationReservationInvoices.index', compact('applications', 'paymentMethods', 'academicYears'));
    }
}
