<?php

namespace App\Charts;

use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class AbsenceInInterview
{
    protected LarapexChart $absenceInInterview;
    protected $academicYears;

    public function __construct(LarapexChart $absenceInInterview)
    {
        $this->absenceInInterview = $absenceInInterview;
        $this->academicYears = AcademicYear::where('status', 1)->get()->pluck('id')->toArray();
    }

    public function build()
    {
        $students = StudentApplianceStatus::with('academicYearInfo')
            ->whereIn('academic_year', $this->academicYears)
            ->where('interview_status','=', 'Absence')
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

        $chart = $this->absenceInInterview->horizontalBarChart()
            ->setWidth(600)
            ->setHeight(250)
            ->setGrid()
            ->addData('Absence In Interview', $data)
            ->setXAxis($academicYearLabels);

        return [
            'chart' => $chart,
            'labels' => $academicYearLabels,
            'data' => $data,
            'colors' => $colors,
        ];
    }
}
