<?php

namespace App\Charts;

use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\School;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class SchoolsStudentsNumber
{
    protected LarapexChart $studentNumberStatusByAcademicYear;
    protected $academicYears;

    public function __construct(LarapexChart $studentNumberStatusByAcademicYear)
    {
        $this->studentNumberStatusByAcademicYear = $studentNumberStatusByAcademicYear;
        $this->academicYears = AcademicYear::where('status', 1)->get()->pluck('id')->toArray();
    }

    public function build(): \ArielMejiaDev\LarapexCharts\PieChart
    {
        $students = StudentApplianceStatus::with('academicYearInfo')
            ->whereIn('academic_year', $this->academicYears)
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
        foreach ($this->academicYears as $yearId) {
            $data[] = $studentCountsByYear[$yearId] ?? 0;
        }

        $schoolLabels = collect($this->academicYears)->map(function ($academicYearId) {
            $academicYears = AcademicYear::find($academicYearId);

            return School::find($academicYears->school_id)->name;
        })->toArray();

        return $this->studentNumberStatusByAcademicYear->pieChart()
            ->setTitle('Number of students by school')
            ->addData($data)
            ->setLabels($schoolLabels)
            ->setWidth(400)
            ->setXAxis($schoolLabels);

    }
}
