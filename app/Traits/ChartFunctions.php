<?php

namespace App\Traits;

use App\Models\Branch\ApplicationReservation;
use App\Models\Branch\Applications;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\Level;
use App\Models\Finance\TuitionInvoices;

trait ChartFunctions
{
    /**
     * Return chart of registered students in last academic year
     */
    public function registeredStudentsInLastAcademicYear($activeAcademicYears)
    {
        // Replace this with your actual data retrieval logic
        $data = StudentApplianceStatus::with('academicYearInfo')
            ->whereHas('academicYearInfo', function ($query) use ($activeAcademicYears) {
                $query->whereIn('id', $activeAcademicYears);
            })
            ->get();

        /**
         * Getting academic year names
         */
        $academicYearNames = [];
        foreach ($data as $studentStatus) {
            $academicYearNames[] = $studentStatus->academicYearInfo->name;
        }

        /**
         * Getting appliance count by academic year
         */
        $applianceCount = array_count_values(array_filter($academicYearNames));

        $data = [
            'labels' => array_keys($applianceCount),
            'data' => array_values($applianceCount),
            'chart_label' => 'Enrolled Students',
            'unit' => 'students',
        ];

        return $data;
    }

    /**
     * Return chart of accepted students in last academic year
     */
    public function acceptedStudentNumberStatusByAcademicYear($activeAcademicYears)
    {
        $data = StudentApplianceStatus::with('academicYearInfo')
            ->whereHas('academicYearInfo', function ($query) use ($activeAcademicYears) {
                $query->whereIn('id', $activeAcademicYears);
            })
            ->whereApprovalStatus(1)
            ->get();

        /**
         * Getting academic year names
         */
        $academicYearNames = [];
        foreach ($data as $studentStatus) {
            $academicYearNames[] = $studentStatus->academicYearInfo->name;
        }

        /**
         * Getting appliance count by academic year
         */
        $applianceCount = array_count_values(array_filter($academicYearNames));

        $data = [
            'labels' => array_keys($applianceCount),
            'data' => array_values($applianceCount),
            'chart_label' => 'Accepted Students ',
            'unit' => 'students',
        ];

        return $data;
    }

    /**
     * Return chart of reserved applications in last academic year
     */
    public function reservedApplicationsByAcademicYear($activeAcademicYears)
    {
        // Replace this with your actual data retrieval logic
        $applicationReservedByAcademicYear = Applications::with('applicationTimingInfo')
            ->whereHas('applicationTimingInfo', function ($query) use ($activeAcademicYears) {
                $query->whereIn('academic_year', $activeAcademicYears);
            })
            ->whereReserved('1')
            ->get();

        /**
         * Getting academic year names
         */
        $academicYearNames = [];
        foreach ($applicationReservedByAcademicYear as $applicationReserved) {
            $academicYearNames[] = $applicationReserved->applicationTimingInfo->academicYearInfo->name;
        }

        /**
         * Getting appliance count by academic year
         */
        $applianceCount = array_count_values(array_filter($academicYearNames));

        $data = [
            'labels' => array_keys($applianceCount),
            'data' => array_values($applianceCount),
            'chart_label' => 'Total Number of Applications reserved ',
            'unit' => 'Application',
        ];

        return $data;
    }

    /**
     * Return chart of students admitted in interview
     */
    public function admittedInterviews($activeAcademicYears)
    {
        $data = StudentApplianceStatus::with('academicYearInfo')
            ->whereHas('academicYearInfo', function ($query) use ($activeAcademicYears) {
                $query->whereIn('id', $activeAcademicYears);
            })
            ->whereInterviewStatus('Admitted')
            ->get();

        /**
         * Getting academic year names
         */
        $academicYearNames = [];
        foreach ($data as $studentStatus) {
            $academicYearNames[] = $studentStatus->academicYearInfo->name;
        }

        /**
         * Getting appliance count by academic year
         */
        $applianceCount = array_count_values(array_filter($academicYearNames));

        $data = [
            'labels' => array_keys($applianceCount),
            'data' => array_values($applianceCount),
            'chart_label' => 'Admitted Interviews',
            'unit' => 'students',
        ];

        return $data;
    }

    /**
     * Return chart of students rejected in interview
     */
    public function rejectedInterviews($activeAcademicYears)
    {
        $data = StudentApplianceStatus::with('academicYearInfo')
            ->whereHas('academicYearInfo', function ($query) use ($activeAcademicYears) {
                $query->whereIn('id', $activeAcademicYears);
            })
            ->whereInterviewStatus('Rejected')
            ->get();

        /**
         * Getting academic year names
         */
        $academicYearNames = [];
        foreach ($data as $studentStatus) {
            $academicYearNames[] = $studentStatus->academicYearInfo->name;
        }

        /**
         * Getting appliance count by academic year
         */
        $applianceCount = array_count_values(array_filter($academicYearNames));

        $data = [
            'labels' => array_keys($applianceCount),
            'data' => array_values($applianceCount),
            'chart_label' => 'Rejected Interviews',
            'unit' => 'students',
        ];

        return $data;
    }

    /**
     * Return chart of students absence in interview
     */
    public function absenceInInterview($activeAcademicYears)
    {
        $data = StudentApplianceStatus::with('academicYearInfo')
            ->whereHas('academicYearInfo', function ($query) use ($activeAcademicYears) {
                $query->whereIn('id', $activeAcademicYears);
            })
            ->whereInterviewStatus('Absence')
            ->get();

        /**
         * Getting academic year names
         */
        $academicYearNames = [];
        foreach ($data as $studentStatus) {
            $academicYearNames[] = $studentStatus->academicYearInfo->name;
        }

        /**
         * Getting appliance count by academic year
         */
        $applianceCount = array_count_values(array_filter($academicYearNames));

        $data = [
            'labels' => array_keys($applianceCount),
            'data' => array_values($applianceCount),
            'chart_label' => 'Absence In Interview',
            'unit' => 'students',
        ];

        return $data;
    }

    /**
     * Return chart of interview types
     */
    public function interviewTypes($activeAcademicYears)
    {
        $onCampus = ApplicationReservation::with('applicationInfo')
            ->whereInterviewType('On-Campus')
            ->whereHas('applicationInfo', function ($query) use ($activeAcademicYears) {
                $query->whereHas('applicationTimingInfo', function ($query) use ($activeAcademicYears) {
                    $query->whereIn('academic_year', $activeAcademicYears);
                });
            })
            ->count();
        $onSight = ApplicationReservation::with('applicationInfo')
            ->whereInterviewType('On-Sight')
            ->whereHas('applicationInfo', function ($query) use ($activeAcademicYears) {
                $query->whereHas('applicationTimingInfo', function ($query) use ($activeAcademicYears) {
                    $query->whereIn('academic_year', $activeAcademicYears);
                });
            })
            ->count();
        /**
         * Interview Types
         */
        $types = [
            'On-Campus' => $onCampus,
            'On-Sight' => $onSight,
        ];

        $data = [
            'labels' => array_keys($types),
            'data' => array_values($types),
            'chart_label' => 'Interview Types',
            'unit' => 'interview',
        ];

        return $data;
    }

    /**
     * Return chart of tuition payment types
     */
    public function paymentTypes($activeAcademicYears)
    {
        $data = TuitionInvoices::with('applianceInformation')
            ->whereHas('applianceInformation', function ($query) use ($activeAcademicYears) {
                $query->whereHas('academicYearInfo', function ($query) use ($activeAcademicYears) {
                    $query->whereIn('id', $activeAcademicYears);
                });
            })
            ->get();

        /**
         * Getting payment types
         */
        $fullPayment = 0;
        $fullPaymentWithAdvance = 0;
        $twoInstallments = 0;
        $fourInstallments = 0;
        foreach ($data as $tuitionInvoices) {
            switch ($tuitionInvoices->payment_type) {
                case 1:
                    $fullPayment++;
                    break;
                case 4:
                    $fullPaymentWithAdvance++;
                    break;
                case 2:
                    $twoInstallments++;
                    break;
                case 3:
                    $fourInstallments++;
                    break;
            }
        }

        $tuitionInfo = [
            'Full Payment' => $fullPayment,
            'Full Payment With Advance' => $fullPaymentWithAdvance,
            'Two Installments' => $twoInstallments,
            'Four Installments' => $fourInstallments,
        ];
        $data = [
            'labels' => array_keys($tuitionInfo),
            'data' => array_values($tuitionInfo),
            'chart_label' => 'Tuition Payment Types',
            'unit' => '',
        ];

        return $data;
    }

    /**
     * Return chart of tuition paid (Payment Type)
     */
    public function tuitionPaidPaymentType($activeAcademicYears)
    {
        $data = TuitionInvoices::with(['applianceInformation', 'invoiceDetails'])
            ->whereHas('applianceInformation', function ($query) use ($activeAcademicYears) {
                $query->whereHas('academicYearInfo', function ($query) use ($activeAcademicYears) {
                    $query->whereIn('id', $activeAcademicYears);
                });
            })
            ->whereHas('invoiceDetails', function ($query) {
                $query->whereIsPaid(1);
            })
            ->get();

        /**
         * Getting tuition invoice details
         */
        $fullPayment = 0;
        $fullPaymentWithAdvance = 0;
        $twoInstallments = 0;
        $fourInstallments = 0;
        foreach ($data as $tuitionInvoices) {
            $amount = 0;
            foreach ($tuitionInvoices->invoiceDetails as $invoiceDetail) {
                $amount += $invoiceDetail->amount;
            }
            switch ($tuitionInvoices->payment_type) {
                case 1:
                    $fullPayment += $amount;
                    break;
                case 4:
                    $fullPaymentWithAdvance += $amount;
                    break;
                case 2:
                    $twoInstallments += $amount;
                    break;
                case 3:
                    $fourInstallments += $amount;
                    break;
            }
        }

        $tuitionInfo = [
            'Full Payment' => $fullPayment,
            'Full Payment With Advance' => $fullPaymentWithAdvance,
            'Two Installments' => $twoInstallments,
            'Four Installments' => $fourInstallments,
        ];
        $data = [
            'labels' => array_keys($tuitionInfo),
            'data' => array_values($tuitionInfo),
            'chart_label' => 'Tuition Paid (Payment Type)',
            'unit' => 'IRR',
        ];

        return $data;
    }

    /**
     * Return chart of tuition paid (Academic Year)
     */
    public function tuitionPaidAcademicYear($activeAcademicYears)
    {
        $academicYearTuition=[];
        foreach ($activeAcademicYears as $activeAcademicYear) {
            $data = TuitionInvoices::with(['applianceInformation', 'invoiceDetails'])
                ->whereHas('applianceInformation', function ($query) use ($activeAcademicYear) {
                    $query->whereHas('academicYearInfo', function ($query) use ($activeAcademicYear) {
                        $query->where('id', $activeAcademicYear);
                    });
                })
                ->whereHas('invoiceDetails', function ($query) {
                    $query->whereIsPaid(1);
                })
                ->get()
                ->sum(function ($invoice) {
                    return $invoice->invoiceDetails->sum('amount');
                });
            $academicYearInfo=AcademicYear::find($activeAcademicYear)->first();
            $academicYearTuition[$academicYearInfo->name] = $data;
        }
        $data = [
            'labels' => array_keys($academicYearTuition),
            'data' => array_values($academicYearTuition),
            'chart_label' => 'Tuition Paid (Payment Type)',
            'unit' => 'IRR',
        ];

        return $data;
    }

    /**
     * Return chart of levels
     */
    public function levels($activeAcademicYears)
    {
        $levels = Level::all();
        $data = [];
        foreach ($levels as $level) {
            $reservations = ApplicationReservation::with('applicationInfo')
                ->whereHas('applicationInfo', function ($query) use ($activeAcademicYears) {
                    $query->whereHas('applicationTimingInfo', function ($query) use ($activeAcademicYears) {
                        $query->whereIn('academic_year', $activeAcademicYears);
                    });
                })
                ->whereLevel($level->id)
                ->wherePaymentStatus(1)
                ->count();
            $data[$level->name] = $reservations;
        }
        $data = [
            'labels' => array_keys($data),
            'data' => array_values($data),
            'chart_label' => 'Levels',
            'unit' => 'student(s)',
        ];

        return $data;
    }
}
