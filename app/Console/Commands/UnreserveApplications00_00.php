<?php

namespace App\Console\Commands;

use App\Models\Branch\Applications;
use Illuminate\Console\Command;

class UnreserveApplications00_00 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:unreserve-applications00_00';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command deactive applications when time is 00:00';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->toDateString();

        Applications::whereDate('date', $today)
            ->where('reserved', 0)
            ->whereStatus(1)
            ->update(['status' => 0]);
    }
}
