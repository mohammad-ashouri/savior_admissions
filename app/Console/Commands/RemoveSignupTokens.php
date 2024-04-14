<?php

namespace App\Console\Commands;

use App\Models\Auth\RegisterToken;
use App\Models\Branch\ApplicationReservation;
use App\Models\Branch\Applications;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RemoveSignupTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remove-signup-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove unused signup tokens';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $registerTokens=RegisterToken::where('created_at', '<=', now()->subHour(1))->where('status',0)->get();
        foreach ($registerTokens as $registerToken){
            RegisterToken::find($registerToken->id)->delete();
        }
    }
}
