<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Subscription implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
    )
    {
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->substractDays();
    }


    private function substractDays(){
        $suscriptors = User::whereNotNull('subscription_id')
        ->where('remaining_days_suscription','>',0)
        ->get();

        foreach($suscriptors as $suscriptor){
            $daysActive = $suscriptor->remaining_days_suscription;
            $currentDays = ($daysActive - 1);
            $suscriptor->remaining_days_suscription = $currentDays;
            if($suscriptor->remaining_days_suscription <= 0 ){
                $suscriptor->raffles = 0;
            }

            $suscriptor->save();
        }
    }
}
