<?php

namespace App\Console\Commands;

use App\Models\Finance\TuitionInvoiceDetails;
use App\Models\Finance\TuitionInvoices;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RemoveExpiredInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remove-expired-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command removed expired invoices that not paid after 10 minutes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenMinutesAgo = Carbon::now()->subMinutes(12);

        $tuitionInvoiceDetails = TuitionInvoiceDetails::with(['tuitionInvoiceDetails' => function ($query) {
            $query->with(['applianceInformation' => function ($query) {
                $query->where('approval_status', '!=', 1);
            }]);
        }])
            ->where('created_at', '<=', $tenMinutesAgo)
            ->whereIsPaid(0)->where('payment_method', 2)
            ->get();
        foreach ($tuitionInvoiceDetails as $detail) {
            Invoice::whereJsonContains('description->invoice_details_id', $detail->id)->delete();
            TuitionInvoices::destroy($detail->tuition_invoice_id);
        }
        TuitionInvoiceDetails::where('created_at', '<=', $tenMinutesAgo)->whereIsPaid(0)->where('payment_method', 2)->delete();
    }
}
