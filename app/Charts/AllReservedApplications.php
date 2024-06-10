<?php

namespace App\Charts;

use App\Models\Branch\ApplicationReservation;
use App\Models\Catalogs\AcademicYear;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class AllReservedApplications
{
    protected LarapexChart $allRegisteredApplications;

    protected $academicYears;

    public function __construct(LarapexChart $allRegisteredApplications)
    {
        $this->allRegisteredApplications = $allRegisteredApplications;
        $this->academicYears = AcademicYear::where('status', 1)->get()->pluck('id')->toArray();
    }

    public function build()
    {
        $applications = ApplicationReservation::join('applications', 'application_reservations.application_id', '=', 'applications.id')
            ->join('application_timings', 'applications.application_timing_id', '=', 'application_timings.id')
            ->where('applications.reserved', 1)
            ->where('application_reservations.payment_status', 1)
            ->whereIn('application_timings.academic_year', $this->academicYears)
            ->get();

        $applicationCountsByYear = [];
        foreach ($applications as $application) {
            $yearId = $application->academic_year;
            if (! isset($applicationCountsByYear[$yearId])) {
                $applicationCountsByYear[$yearId] = [];
            }
            $applicationCountsByYear[$yearId][] = 1;
        }

        $academicYearLabels = [];
        foreach ($this->academicYears as $yearId) {
            $academicYear = AcademicYear::find($yearId);
            $academicYearLabels[] = $academicYear->name;
        }

        $data = [];
        foreach ($this->academicYears as $yearId) {
            $data[] = count($applicationCountsByYear[$yearId] ?? []);
        }

        $colors = ['#FF5733', '#33FF57', '#3357FF', '#F333FF', '#FF33A1', '#33FFA7', '#FFA733'];

        $chart = $this->allRegisteredApplications->horizontalBarChart()
            ->setTitle('تعداد کل برنامه‌های ثبت‌نام شده بر اساس سال تحصیلی')
            ->setWidth(600)
            ->setHeight(250)
            ->setGrid()
            ->setXAxis($academicYearLabels)
            ->setDataset($data)
            ->setColors($colors);

        return [
            'chart' => $chart,
            'labels' => $academicYearLabels,
            'data' => $data,
            'colors' => $colors,
        ];
    }
}
