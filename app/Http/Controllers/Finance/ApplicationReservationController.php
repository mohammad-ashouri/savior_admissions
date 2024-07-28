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
        $me = User::find(auth()->user()->id);
        $applications = $principalAccess = $financialManagerAccess = [];

        //        if ($me->hasRole('Super Admin')) {
        //            $applications = ApplicationReservation::with('applicationInfo')
        //                ->with('studentInfo')
        //                ->with('reservatoreInfo')
        //                ->with('applicationInvoiceInfo')
        //                ->join('applications', 'application_reservations.application_id', '=', 'applications.id')
        //                ->join('application_timings', 'applications.application_timing_id', '=', 'application_timings.id')
        //                ->select('application_reservations.*', 'application_reservations.id as application_reservations_id')
        //                ->orderBy('application_reservations.payment_status', 'desc')
        //                ->orderBy('application_timings.academic_year', 'desc')
        //                ->paginate(150);
        //        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
        //            // Convert accesses to arrays and remove duplicates
        //            $myAllAccesses = UserAccessInformation::whereUserId($me->id)->first();
        //            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);
        //
        //            // Finding academic years with status 1 in the specified schools
        //            $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();
        //
        //            // Finding application timings based on academic years
        //            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();
        //
        //            // Finding applications related to the application timings
        //            $applications = Applications::whereIn('application_timing_id', $applicationTimings)
        //                ->pluck('id')
        //                ->toArray();
        //
        //            // Getting reservations of applications along with related information
        //            $applications = ApplicationReservation::with('applicationInfo')
        //                ->with('studentInfo')
        //                ->with('reservatoreInfo')
        //                ->with('applicationInvoiceInfo')
        //                ->whereIn('application_id', $applications)
        //                ->join('applications', 'application_reservations.application_id', '=', 'applications.id')
        //                ->join('application_timings', 'applications.application_timing_id', '=', 'application_timings.id')
        //                ->select('application_reservations.*', 'application_reservations.id as application_reservations_id')
        //                ->orderBy('application_reservations.payment_status', 'asc')
        //                ->orderBy('application_timings.academic_year', 'desc')
        //                ->paginate(150);
        //        }
        //
        //        if ($applications->isEmpty()) {
        //            $applications = [];
        //        }
        //
        $paymentMethods = PaymentMethod::whereStatus(1)->get();
        if ($me->hasRole('Super Admin')) {
            $academicYears = AcademicYear::get();
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId($me->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->get();
        }

        return view('Finance.ApplicationReservationInvoices.index', compact('applications', 'paymentMethods', 'academicYears'));

    }

    public function create()
    {
        $me = User::find(auth()->user()->id);
        if ($me->hasRole('Parent')) {
            $myStudents = StudentInformation::with('generalInformations')->whereGuardian($me->id)->orderBy('id')->get();
            $levels = Level::whereStatus(1)->get();

            return view('Finance.ApplicationReservationInvoices.create', compact('myStudents', 'levels'));
        }
        abort(403);
    }

    public function show($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $applicationInfo = $principalAccess = $financialManagerAccess = [];

        $me = User::find(auth()->user()->id);
        $applicationReservation = ApplicationReservation::find($id);
        if (empty($applicationReservation)) {
            abort(403);
        }

        if ($me->hasRole('Parent')) {
            $myStudents = StudentInformation::whereGuardian($me->id)->pluck('student_id')->toArray();
            $applicationInfo = ApplicationReservation::with('levelInfo')->with('applicationInfo')->with('studentInfo')->with('reservatoreInfo')->with('applicationInvoiceInfo')->whereIn('student_id', $myStudents)->whereId($id)->first();
        } elseif ($me->hasRole('Super Admin')) {
            $applicationInfo = ApplicationReservation::with('levelInfo')->with('applicationInfo')->with('studentInfo')->with('reservatoreInfo')->with('applicationInvoiceInfo')->whereId($id)->first();
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId($me->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

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
                ->whereId($id)->first();
            if (empty($applicationInfo)) {
                abort(403);
            }
        }

        return view('Finance.ApplicationReservationInvoices.show', compact('applicationInfo'));
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        $me = User::find(auth()->user()->id);
        $checkAccessToApplication = [];
        if (! $me->hasRole('Super Admin')) {
            if (isset($myAllAccesses->principal) or isset($myAllAccesses->admissions_officer)) {
                $myAllAccesses = UserAccessInformation::whereUserId($me->id)->first();
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

    public function changeApplicationPaymentStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $me = User::find(auth()->user()->id);
        $applicationID = $request->application_id;
        $applicationStatus = $request->status;

        if ($me->hasRole('Super Admin')) {
            $applicationInfo = ApplicationReservation::with('levelInfo')->with('applicationInfo')->with('studentInfo')->with('reservatoreInfo')->with('applicationInvoiceInfo')->whereId($applicationID)->first();
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId($me->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

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
                ->whereId($applicationID)->first();

        }

        if (empty($applicationInfo)) {
            return response()->json(['message' => $applicationInfo], 422);
        }

        $applicationReservation = ApplicationReservation::with('applicationInfo')->with('reservatoreInfo')->find($applicationID);
        if (empty($applicationReservation)) {
            return response()->json(['message' => 'Application not found!'], 422);
        }

        $applicationReservation->payment_status = $applicationStatus;
        $applicationReservation->save();

        $applianceStatus = StudentApplianceStatus::where('student_id', $applicationReservation->student_id)->whereAcademicYear($applicationReservation->applicationInfo->applicationTimingInfo->academic_year)->first();
        if ($applicationStatus == 1) {
            if (empty($applianceStatus)) {
                $applianceStatus = new StudentApplianceStatus();
                $applianceStatus->student_id = $applicationReservation->student_id;
                $applianceStatus->academic_year = $applicationReservation->applicationInfo->applicationTimingInfo->academic_year;
                $applianceStatus->interview_status = 'Pending Interview';
            } else {
                $applianceStatus->interview_status = 'Pending Interview';
            }
            $applianceStatus->save();

            $application = Applications::find($applicationReservation->application_id);
            $application->reserved = 1;
            $application->save();

            $message = "Your payment has been successfully verified.\n
            Your application has been reserved for this date and time:\n
            ".$applicationReservation->applicationInfo->date.' '.$applicationReservation->applicationInfo->start_from." \nSavior Schools";

            $reservatoreMobile = $applicationReservation->reservatoreInfo->mobile;
            $this->sendSMS($reservatoreMobile, $message);
        } elseif ($applicationStatus == 3) {
            $applianceStatus = StudentApplianceStatus::where('student_id', $applicationReservation->student_id)->whereAcademicYear($applicationReservation->applicationInfo->applicationTimingInfo->academic_year)
                ->update(['interview_status' => null]);

            $application = Applications::find($applicationReservation->application_id);
            $application->reserved = 0;
            $application->save();

            $message = "Your application reservation payment has been rejected. \nSavior Schools";
            $reservatoreMobile = $applicationReservation->reservatoreInfo->mobile;
            $this->sendSMS($reservatoreMobile, $message);
        }

        return response()->json(['message' => 'Application payment status changed!'], 200);
    }

    public function searchReservationInvoices(Request $request)
    {
        $me = User::find(auth()->user()->id);
        $applications = $principalAccess = $financialManagerAccess = [];
        $paymentMethod = $request->payment_method;
        $dateOfPayment = $request->date_of_payment;
        if ($me->hasRole('Super Admin')) {
            $data = ApplicationReservation::with('applicationInfo')
                ->with('studentInfo')
                ->with('reservatoreInfo')
                ->with('applicationInvoiceInfo')
                ->join('applications', 'application_reservations.application_id', '=', 'applications.id')
                ->join('application_timings', 'applications.application_timing_id', '=', 'application_timings.id')
                ->where(function ($query) use ($request) {
                    if (! empty($request->academic_year)) {
                        $query->where('application_timings.academic_year', $request->academic_year);
                    }
                    if (! empty($request->status)) {
                        $query->where('application_reservations.payment_status', $request->status);
                    }
                });
            if (! empty($paymentMethod)) {
                $data->whereHas('applicationInvoiceInfo', function ($query) use ($paymentMethod) {
                    $query->whereJsonContains('payment_information->payment_method', (int) $paymentMethod);
                    $query->orWhereJsonContains('payment_information->payment_method', (string) $paymentMethod);
                });
            }
            if (! empty($dateOfPayment)) {
                $data->whereHas('applicationInvoiceInfo', function ($query) use ($dateOfPayment) {
                    $query->where('created_at','like', "%$dateOfPayment%");
                });
            }
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId($me->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $applications = Applications::whereIn('application_timing_id', $applicationTimings)
                ->pluck('id')
                ->toArray();

            // Getting reservations of applications along with related information
            $data = ApplicationReservation::with('applicationInfo')
                ->with('studentInfo')
                ->with('reservatoreInfo')
                ->with('applicationInvoiceInfo')
                ->whereIn('application_id', $applications)
                ->join('applications', 'application_reservations.application_id', '=', 'applications.id')
                ->join('application_timings', 'applications.application_timing_id', '=', 'application_timings.id');
            if (! empty($paymentMethod)) {
                $data->whereHas('applicationInvoiceInfo', function ($query) use ($paymentMethod) {
                    $query->whereJsonContains('payment_information->payment_method', (int) $paymentMethod);
                    $query->orWhereJsonContains('payment_information->payment_method', (string) $paymentMethod);
                });
            }
        }

        $applications = $data->select('application_reservations.*', 'application_reservations.id as application_reservations_id')
            ->orderBy('application_reservations.payment_status', 'asc')
            ->orderBy('application_timings.academic_year', 'desc')
            ->get();
        if ($applications->isEmpty()) {
            $applications = [];
        }

        $paymentMethods = PaymentMethod::whereStatus(1)->get();
        if ($me->hasRole('Super Admin')) {
            $academicYears = AcademicYear::get();
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId($me->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->get();
        }

        return view('Finance.ApplicationReservationInvoices.index', compact('applications', 'paymentMethods', 'academicYears'));
    }
}
