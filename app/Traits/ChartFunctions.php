<?php

namespace App\Traits;

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
            'chart_label' => 'Total Number of Enrolled Students by Academic Year',
            'unit' => 'students',
        ];

        return $data;
    }

    /**
     * Return chart of accepted students in last academic year
     */
    public function acceptedStudentNumberStatusByAcademicYear($activeAcademicYears)
    {
        // Replace this with your actual data retrieval logic
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
            'chart_label' => 'Total Number of Accepted Students by Academic Year',
            'unit' => 'students',
        ];

        return $data;
    }
}
