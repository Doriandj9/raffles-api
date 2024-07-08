<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Client\app\Models\Ticket;
use Modules\UserRaffle\app\Models\Raffle;

class Raffles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     * 
     */
    public function __construct(
        private string | int $idRaffle,
        private array $dataChange,
        private string $template,
        private string $subject
    ){
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
       $userInRaffle = Ticket::where('raffles_id', $this->idRaffle)
       ->select(['user_taxid'])
       ->whereNotNull('user_taxid')
       ->groupBy('user_taxid')
       ->get();  
       $raffle = Raffle::find($this->idRaffle);
       $userInRaffle = $userInRaffle->map(function (Ticket $ticket, int $index){
        return $ticket->user_taxid;
       });
       $users = User::whereIn('taxid', $userInRaffle->toArray())->get();
       foreach($users  as $user){
        if($user->send_email == true){
            sendEmail($user->email,$this->subject,$this->template,[
             'user' => $user,
             'raffle' => $raffle,
             'changes' => $this->dataChange
            ]);
        }
       }
    }


    
}
