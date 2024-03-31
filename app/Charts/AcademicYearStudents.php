<?php

namespace App\Charts;

use App\Models\Catalogs\AcademicYear;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class AcademicYearStudents
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\PieChart
    {
        $academicYears=AcademicYear::where('status',1)->get()->pluck('id')->toArray();
        return $this->chart->pieChart()
            ->setTitle('Number of students by school')
            ->addData([40, 93, 35, 42, 18, 82])
            ->setXAxis(['January', 'February', 'March', 'April', 'May', 'June'])
            ;
    }
}
