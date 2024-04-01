<?php

namespace App\Charts;

use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\School;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class SchoolsStudentsNumber
{
    protected LarapexChart $studentNumberStatusByAcademicYear;

    public function __construct(LarapexChart $studentNumberStatusByAcademicYear)
    {
        $this->studentNumberStatusByAcademicYear = $studentNumberStatusByAcademicYear;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\PieChart
    {
        $academicYears = AcademicYear::where('status', 1)->get()->pluck('id')->toArray();
        $students = StudentApplianceStatus::with('academicYearInfo')
            ->whereIn('academic_year', $academicYears)
            ->where('approval_status', 1)
            ->get();

        $studentCountsByYear = [];
        foreach ($students as $student) {
            $yearId = $student->academicYearInfo->id;
            if (! isset($studentCountsByYear[$yearId])) {
                $studentCountsByYear[$yearId] = 0;
            }
            $studentCountsByYear[$yearId]++;
        }

        $data = [];
        foreach ($academicYears as $yearId) {
            $data[] = $studentCountsByYear[$yearId] ?? 0;
        }

        $schoolLabels = collect($academicYears)->map(function ($academicYearId) {
            $academicYears = AcademicYear::find($academicYearId);

            return School::find($academicYears->school_id)->name;
        })->toArray();

        return $this->studentNumberStatusByAcademicYear->pieChart()
            ->setTitle('Number of students by school')
            ->addData($data)
            ->setLabels($schoolLabels)
            ->setWidth(400);

    }
}
