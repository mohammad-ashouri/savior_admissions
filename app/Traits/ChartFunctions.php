<?php

namespace App\Traits;

use App\Models\Branch\Applications;
use App\Models\Branch\StudentApplianceStatus;

trait ChartFunctions
{
    /**
     * Return chart of registered students in last academic year
     */
    public function registeredStudentsInLastAcademicYear($activeAcademicYears)
    {
        // Replace this with your actual data retrieval logic
        $studentStatusesByAcademicYear = StudentApplianceStatus::with('academicYearInfo')
            ->whereHas('academicYearInfo', function ($query) use ($activeAcademicYears) {
                $query->whereIn('id', $activeAcademicYears);
            })
            ->get();

        /**
         * Getting academic year names
         */
        $academicYearNames = [];
        foreach ($studentStatusesByAcademicYear as $studentStatus) {
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
        $studentStatusesByAcademicYear = StudentApplianceStatus::with('academicYearInfo')
            ->whereHas('academicYearInfo', function ($query) use ($activeAcademicYears) {
                $query->whereIn('id', $activeAcademicYears);
            })
            ->whereApprovalStatus(1)
            ->get();

        /**
         * Getting academic year names
         */
        $academicYearNames = [];
        foreach ($studentStatusesByAcademicYear as $studentStatus) {
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
        $studentStatusesByAcademicYear = StudentApplianceStatus::with('academicYearInfo')
            ->whereHas('academicYearInfo', function ($query) use ($activeAcademicYears) {
                $query->whereIn('id', $activeAcademicYears);
            })
            ->whereInterviewStatus('Admitted')
            ->get();

        /**
         * Getting academic year names
         */
        $academicYearNames = [];
        foreach ($studentStatusesByAcademicYear as $studentStatus) {
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
        $studentStatusesByAcademicYear = StudentApplianceStatus::with('academicYearInfo')
            ->whereHas('academicYearInfo', function ($query) use ($activeAcademicYears) {
                $query->whereIn('id', $activeAcademicYears);
            })
            ->whereInterviewStatus('Rejected')
            ->get();

        /**
         * Getting academic year names
         */
        $academicYearNames = [];
        foreach ($studentStatusesByAcademicYear as $studentStatus) {
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
        $studentStatusesByAcademicYear = StudentApplianceStatus::with('academicYearInfo')
            ->whereHas('academicYearInfo', function ($query) use ($activeAcademicYears) {
                $query->whereIn('id', $activeAcademicYears);
            })
            ->whereInterviewStatus('Absence')
            ->get();

        /**
         * Getting academic year names
         */
        $academicYearNames = [];
        foreach ($studentStatusesByAcademicYear as $studentStatus) {
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
}
