<?php

namespace Database\Seeders;

use App\Models\Branch\ApplicationReservation;
use App\Models\Branch\ApplicationTiming;
use App\Models\Branch\StudentApplianceStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddLevelsToStudentApplianceStatuses extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $applianceStatuses = StudentApplianceStatus::whereNotNull('interview_status')->get();

        foreach ($applianceStatuses as $appliance) {
            $applicationReservation = ApplicationReservation::where('payment_status', 1)
                ->where('student_id', $appliance->student_id)
                ->whereHas('applicationInfo', function ($query) use ($appliance) {
                    $query->whereHas('applicationTimingInfo', function ($query) use ($appliance) {
                        $query->where('academic_year',$appliance->academic_year);
                    });
                })
                ->latest()->first();
            if (!empty($applicationReservation->level)){
                $applianceStatuses = StudentApplianceStatus::find($appliance->id)->update([
                    'level'=>$applicationReservation->level
                ]);
            }

        }
    }
}
