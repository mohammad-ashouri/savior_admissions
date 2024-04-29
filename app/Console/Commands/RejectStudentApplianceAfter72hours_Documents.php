<?php

namespace App\Console\Commands;

use App\Models\Branch\StudentApplianceStatus;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RejectStudentApplianceAfter72hours_Documents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reject-student-appliance-documents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command rejects appliances whose documents have not been uploaded after 72 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $now = Carbon::now();
        $before72Hours = $now->subHours(72);

        StudentApplianceStatus::where('updated_at', '<=', $before72Hours)->where('documents_uploaded', 0)->update([
            'documents_uploaded' => 3,
            'documents_uploaded_approval' => 3,
            'description' => 'Automatic Rejected (Documents could not be uploaded after 72 hours)',
        ]);

    }
}
