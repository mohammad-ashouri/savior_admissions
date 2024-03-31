<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class MonthlyUsersChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\PieChart
    {
        return $this->chart->pieChart()
            ->setTitle('Sales during 2021.')
            ->setSubtitle('Physical sales vs Digital sales.')
            ->addData([40, 93, 35, 42, 18, 82,62])
            ->setXAxis(['January', 'February', 'March', 'April', 'May', 'June']);
    }
}
