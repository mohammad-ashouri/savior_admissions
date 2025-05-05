<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Branch\ApplicationReservation;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\Finance\DiscountDetail;
use App\Models\Finance\GrantedFamilyDiscount;
use App\Models\StudentInformation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Jenssegers\Agent\Agent;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;
use Kavenegar\Laravel\Facade as Kavenegar;

use function PHPUnit\Framework\isEmpty;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    const FORMAT = '%s = %s <br/>';

    public function logActivity($activity, $ip_address, $user_agent, $user_id = null): void
    {
        //        if (session('id')) {
        //            $user_id = session('id');
        //        }
        //        // Detect device type based on user agent header
        //        $deviceType = null;
        //        if (strpos($user_agent, 'Mobile') !== false) {
        //            $deviceType = 'Mobile';
        //        } elseif (strpos($user_agent, 'Tablet') !== false) {
        //            $deviceType = 'Tablet';
        //        } else {
        //            $deviceType = 'Desktop';
        //        }
        //
        //        // Detect browser type and version
        //        $browser = '';
        //        $browser_version = '';
        //        if (preg_match('/(MSIE|Edge|Firefox|Chrome|Safari)\/([^\s]+)/i', $user_agent, $matches)) {
        //            $browser = $matches[1];
        //            $browser_version = $matches[2];
        //        }
        //
        //        // Detect platform type and version
        //        $platform = '';
        //        $platform_version = '';
        //        if (preg_match('/(Windows NT|Windows|Macintosh|Android|iOS) ([^\s]+)/i', $user_agent, $matches)) {
        //            $platform = $matches[1];
        //            $platform_version = $matches[2];
        //        }
        //
        //        // Create activity log record
        //        ActivityLog::create([
        //            'user_id' => $user_id,
        //            'activity' => $activity,
        //            'ip_address' => $ip_address,
        //            'device' => $user_agent,
        //            'platform' => $platform,
        //            'platform_version' => $platform_version,
        //            'browser' => $browser,
        //            'browser_version' => $browser_version,
        //            'device_type' => $deviceType,
        //        ]);
    }

    public function alerts($state, $errorVariable, $errorText): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => $state,
            'errors' => [
                $errorVariable => [$errorText],
            ],
        ]);
    }

    public function success($state, $messageVariable, $messageText): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => $state,
            'message' => [
                $messageVariable => [$messageText],
            ],
        ]);
    }

    //Getting principal and financial manager accesses
    public function getFilteredAccessesPF($userAccessInfo): array
    {
        $principalAccess = [];
        $financialManagerAccess = [];

        if (!empty($userAccessInfo->principal)) {
            $principalAccess = explode('|', $userAccessInfo->principal);
        }

        if (!empty($userAccessInfo->financial_manager)) {
            $financialManagerAccess = explode('|', $userAccessInfo->financial_manager);
        }

        return array_filter(array_unique(array_merge($principalAccess, $financialManagerAccess)));
    }

    //Getting principal and financial manager accesses
    public function getFilteredAccessesI($userAccessInfo): array
    {
        $interviewerAccess = [];

        if (!empty($userAccessInfo->interviewer)) {
            $interviewerAccess = explode('|', $userAccessInfo->interviewer);
        }

        return array_filter(array_unique(array_merge($interviewerAccess, $interviewerAccess)));
    }

    //Getting principal and admissions officer accesses
    public function getFilteredAccessesPA($userAccessInfo): array
    {
        $principalAccess = [];
        $admissionsOfficerAccess = [];

        if (!empty($userAccessInfo->principal)) {
            $principalAccess = explode('|', $userAccessInfo->principal);
        }

        if (!empty($userAccessInfo->admissions_officer)) {
            $admissionsOfficerAccess = explode('|', $userAccessInfo->admissions_officer);
        }

        return array_filter(array_unique(array_merge($principalAccess, $admissionsOfficerAccess)));
    }

    //Getting principal and admissions officer and financial manager accesses
    public function getFilteredAccessesPAF($userAccessInfo): array
    {
        $principalAccess = [];
        $admissionsOfficerAccess = [];

        if (!empty($userAccessInfo->principal)) {
            $principalAccess = explode('|', $userAccessInfo->principal);
        }

        if (!empty($userAccessInfo->admissions_officer)) {
            $admissionsOfficerAccess = explode('|', $userAccessInfo->admissions_officer);
        }

        $financialManagerAccess = [];

        if (!empty($userAccessInfo->financial_manager)) {
            $financialManagerAccess = explode('|', $userAccessInfo->financial_manager);
        }

        return array_filter(array_unique(array_merge($principalAccess, $admissionsOfficerAccess, $financialManagerAccess)));
    }

    //Getting financial manager accesses
    public function getFilteredAccessesF($userAccessInfo): array
    {
        $financialManagerAccess = [];

        if (!empty($userAccessInfo->financial_manager)) {
            $financialManagerAccess = explode('|', $userAccessInfo->financial_manager);
        }

        return array_filter(array_unique(array_merge($financialManagerAccess)));
    }

    //Getting principal accesses
    public function getFilteredAccessesP($userAccessInfo): array
    {
        $principalAccess = [];

        if (!empty($userAccessInfo->principal)) {
            $principalAccess = explode('|', $userAccessInfo->principal);
        }

        return array_filter(array_unique(array_merge($principalAccess)));
    }

    public function sendSMS($mobile, $messageText): void
    {
        try {
            $sender = '+9890005085';
            $message = $messageText;
            $receptor = [$mobile];
            $result = Kavenegar::Send($sender, $receptor, $message . "\nلغو11");
            $this->format($result);
        } catch (ApiException $e) {
            echo $e->errorMessage();
        } catch (HttpException $e) {
            echo $e->errorMessage();
        }
    }

    private function format($result)
    {
        //        $statuses = [];
        //        if ($result) {
        //            foreach ($result as $r) {
        //                $statuses[] = ['status' => $r->status];
        //            }
        //        }
        //        return json_encode($statuses);
    }

    public function getActiveAcademicYears()
    {
        $academicYears = AcademicYear::whereStatus(1)->get()->pluck('id')->toArray();
        if (empty($academicYears)) {
            return false;
        }

        return $academicYears;
    }

    //For return all discounts
    public function getAllDiscounts($student_id, $academic_year)
    {
        $applicationInfo = ApplicationReservation::join('applications', 'application_reservations.application_id', '=', 'applications.id')
            ->join('application_timings', 'applications.application_timing_id', '=', 'application_timings.id')
            ->join('interviews', 'applications.id', '=', 'interviews.application_id')
            ->where('application_reservations.student_id', $student_id)
            ->where('applications.reserved', 1)
            ->where('application_reservations.payment_status', 1)
//            ->where('applications.interviewed', 1)
            ->where('interviews.interview_type', 3)
            ->whereNull('interviews.deleted_at')
            ->where('application_timings.academic_year', $academic_year)
            ->orderByDesc('application_reservations.id')
            ->first();

        //Discount Percentages
        $discountPercentages = 0;
        if (isset(json_decode($applicationInfo->interview_form, true)['discount'])) {
            $interviewFormDiscounts = json_decode($applicationInfo->interview_form, true)['discount'];
            $discountPercentages = DiscountDetail::whereIn('id', $interviewFormDiscounts)->pluck('percentage')->sum();
        }

        return $discountPercentages;
    }

    public function getAllFamilyDiscounts()
    {
        $me = auth()->user()->id;

        $allStudentsWithMyGuardian = StudentInformation::whereGuardian($me)->pluck('student_id')->toArray();
        $allStudentsWithPaidStatusInActiveAcademicYear = StudentApplianceStatus::with('studentInfo')
            ->with('academicYearInfo')
            ->whereIn('student_id', $allStudentsWithMyGuardian)
            ->whereTuitionPaymentStatus('Paid')
            ->whereIn('academic_year', $this->getActiveAcademicYears())
            ->count();

        $familyDiscountAmount = 0;
        if ($allStudentsWithPaidStatusInActiveAcademicYear == 2) {
            $familyDiscountAmount = 25;
        }
        if ($allStudentsWithPaidStatusInActiveAcademicYear == 3) {
            $familyDiscountAmount = 30;
        }
        if ($allStudentsWithPaidStatusInActiveAcademicYear > 4) {
            $familyDiscountAmount = 40;
        }

        return ['percentage' => $familyDiscountAmount, 'students_count' => $allStudentsWithPaidStatusInActiveAcademicYear];
    }

    public function getMinimumApplianceLevelInfo($guardian_id)
    {
        $allStudentsWithGuardian = StudentInformation::whereGuardian($guardian_id)->pluck('student_id')->toArray();
        $allApplianceStudents = StudentApplianceStatus::whereIn('student_id', $allStudentsWithGuardian)->whereIn('academic_year', $this->getActiveAcademicYears())->whereTuitionPaymentStatus('Paid')->pluck('id')->toArray();
        $level = GrantedFamilyDiscount::whereIn('appliance_id', $allApplianceStudents)
            ->orderBy('level', 'asc')
            ->first();
        $academicYear = [];
        if (!empty($level)) {
            $academicYear = StudentApplianceStatus::whereId($level->appliance_id)->value('academic_year');
        }

        return ['level' => $level, 'academic_year' => $academicYear];
    }

    public function getMinimumSignedChildNumber($guardian_id)
    {
        $allStudentsWithGuardian = StudentInformation::whereGuardian($guardian_id)->pluck('student_id')->toArray();
        $allApplianceStudents = StudentApplianceStatus::whereIn('student_id', $allStudentsWithGuardian)->whereIn('academic_year', $this->getActiveAcademicYears())->whereTuitionPaymentStatus('Paid')->pluck('id')->toArray();

        $minimumSignedStudentNumber = GrantedFamilyDiscount::whereIn('appliance_id', $allApplianceStudents)
            ->orderByDesc('signed_child_number')
            ->value('signed_child_number');
        if (empty($minimumSignedStudentNumber) or $minimumSignedStudentNumber == null) {
            return 1;
        }

        return $minimumSignedStudentNumber;
    }

    public function getAllFamilyDiscountPrice($guardian_id)
    {
        //Found previous discounts
        $allStudentsWithGuardian = StudentInformation::whereGuardian($guardian_id)->pluck('student_id')->toArray();
        $allApplianceStudents = StudentApplianceStatus::whereIn('student_id', $allStudentsWithGuardian)->whereIn('academic_year', $this->getActiveAcademicYears())->whereTuitionPaymentStatus('Paid')->pluck('id')->toArray();

        return GrantedFamilyDiscount::whereIn('appliance_id', $allApplianceStudents)->sum('discount_price');
    }

    public function getGrantedDiscountInfo($appliance_id)
    {
        $grantedDiscountInfo = GrantedFamilyDiscount::whereApplianceId($appliance_id)->first();

        if (empty($grantedDiscountInfo)) {
            return 0;
        }

        return $grantedDiscountInfo;
    }

    public function getMyStudentsAcademicYears()
    {
        $allStudents = StudentInformation::whereGuardian(auth()->user()->id)->pluck('student_id')->toArray();
        $allStudentsStatus = StudentApplianceStatus::whereIn('student_id', $allStudents)->whereInterviewStatus('Admitted')->pluck('academic_year')->toArray();
        if (empty($allStudentsStatus)) {
            return null;
        }

        return array_unique($allStudentsStatus);
    }

    function convertPersianToEnglish($string)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($persian, $english, $string);
    }
}
