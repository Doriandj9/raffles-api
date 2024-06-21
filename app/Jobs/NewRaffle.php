<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\UserRaffle\app\Models\Raffle;

class NewRaffle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private string | int $raffleId)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       $raffle = Raffle::find($this->raffleId);
       $awards = json_decode($raffle->awards);

       //envio de correo electronico
       User::chunk(1000, function(Collection $users) use ($raffle, $awards){
        $template = 'emails.new-raffle';
        foreach($users as $user){
            $id = $raffle->id;
            $data = [
                'user' => $user,
                'raffle' => $raffle,
                'awards' => $awards,
                'url' => "payment/raffles/$id"
            ];
            sendEmail($user->email,'Un nuevo sueño una nueva rifa - HAYU24',$template,$data);
        }
       }
    );
    }
}
