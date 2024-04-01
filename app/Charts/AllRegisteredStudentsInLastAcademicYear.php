<?php

namespace App\Charts;

use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\School;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class AllRegisteredStudentsInLastAcademicYear
{
    protected LarapexChart $allRegisteredStudentsInLastAcademicYear;
    protected $academicYears;

    public function __construct(LarapexChart $allRegisteredStudentsInLastAcademicYear)
    {
        $this->allRegisteredStudentsInLastAcademicYear = $allRegisteredStudentsInLastAcademicYear;
        $this->academicYears = AcademicYear::where('status', 1)->get()->pluck('id')->toArray();
    }

    public function build(): \ArielMejiaDev\LarapexCharts\HorizontalBar
    {
        $students = StudentApplianceStatus::with('academicYearInfo')
            ->whereIn('academic_year', $this->academicYears)
            ->get();

        $studentCountsByYear = [];
        foreach ($students as $student) {
            $yearId = $student->academicYearInfo->id;
            if (! isset($studentCountsByYear[$yearId])) {
                $studentCountsByYear[$yearId] = 0;
            }
            $studentCountsByYear[$yearId]++;
        }

        $academicYearLabels = [];
        foreach ($this->academicYears as $yearId) {
            $academicYear = AcademicYear::find($yearId);
            $academicYearLabels[] = $academicYear->name ;
        }

        $data = [];
        foreach ($this->academicYears as $yearId) {
            $data[] = $studentCountsByYear[$yearId] ?? 0;
        }

        $chart = $this->allRegisteredStudentsInLastAcademicYear->horizontalBarChart()
            ->setTitle('Number of all registered students by academic year')
            ->setWidth(500)
            ->setHeight(500)
        ;

        foreach ($academicYearLabels as $index => $academicYearLabel) {
            $chart->addData($academicYearLabel, [$data[$index]]);
        }

        return $chart->setXAxis($academicYearLabels);

    }
}
