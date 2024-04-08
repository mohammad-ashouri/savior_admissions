<?php

namespace App\Charts;

use App\Models\Branch\ApplicationReservation;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class AllRegisteredApplications
{
    protected LarapexChart $allRegisteredApplications;
    protected $academicYears;

    public function __construct(LarapexChart $allRegisteredApplications)
    {
        $this->allRegisteredApplications = $allRegisteredApplications;
        $this->academicYears = AcademicYear::where('status', 1)->get()->pluck('id')->toArray();
    }

    public function build(): \ArielMejiaDev\LarapexCharts\HorizontalBar
    {
        $applications=ApplicationReservation::join('applications', 'application_reservations.application_id', '=', 'applications.id')
            ->join('application_timings', 'applications.application_timing_id', '=', 'application_timings.id')
            ->where('applications.reserved',1)
            ->where('application_reservations.payment_status',1)
            ->select('application_timings.academic_year')
            ->get();
        dd($applications);


        $academicYearLabels = [];
        foreach ($this->academicYears as $yearId) {
            $academicYear = AcademicYear::find($yearId);
            $academicYearLabels[] = $academicYear->name ;
        }

        $data = [];
        foreach ($this->academicYears as $yearId) {
            $data[] = $studentCountsByYear[$yearId] ?? 0;
        }

        $chart = $this->allRegisteredApplications->horizontalBarChart()
            ->setTitle('Number of all registered applications by academic year')
            ->setWidth(600)
            ->setHeight(500)
            ->setGrid()
        ;

        foreach ($academicYearLabels as $index => $academicYearLabel) {
            $chart->addData($academicYearLabel, [$data[$index]]);
        }

        return $chart->setXAxis($academicYearLabels);
    }
}
