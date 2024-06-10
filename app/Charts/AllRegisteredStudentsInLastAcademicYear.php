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

    public function build()
    {
        $students = StudentApplianceStatus::with('academicYearInfo')
            ->whereIn('academic_year', $this->academicYears)
            ->get();

        $studentCountsByYear = [];
        foreach ($students as $student) {
            $yearId = $student->academicYearInfo->id;
            if (!isset($studentCountsByYear[$yearId])) {
                $studentCountsByYear[$yearId] = 0;
            }
            $studentCountsByYear[$yearId]++;
        }

        $academicYearLabels = [];
        foreach ($this->academicYears as $yearId) {
            $academicYear = AcademicYear::find($yearId);
            $academicYearLabels[] = $academicYear->name;
        }

        $data = [];
        foreach ($this->academicYears as $yearId) {
            $data[] = $studentCountsByYear[$yearId] ?? 0;
        }

        $colors = ['#FF5733', '#33FF57', '#3357FF', '#F333FF', '#FF33A1', '#33FFA7', '#FFA733'];

        $chart = $this->allRegisteredStudentsInLastAcademicYear->horizontalBarChart()
            ->setTitle('Number of all approved students by academic year')
            ->setWidth(600)
            ->setHeight(250)
            ->setGrid()
            ->addData('Approved Students', $data)
            ->setXAxis($academicYearLabels);

        return [
            'chart' => $chart,
            'labels' => $academicYearLabels,
            'data' => $data,
            'colors' => $colors,
        ];
    }
}
