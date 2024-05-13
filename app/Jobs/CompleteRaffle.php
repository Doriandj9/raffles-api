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

class CompleteRaffle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private string | int $idRaffle,
    )
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $raffle = Raffle::find($this->idRaffle);
        $drawDetails = json_decode($raffle->draw_details);
        $awardsWinners = $drawDetails->tickets_winner;
        $templateWinners = 'emails.winners-raffles';
        foreach($awardsWinners as $award){
            $title = $award->description->title;
            sendEmail($award->winner->user->email,"Acabas de ganar el $title.",$templateWinners,[
                'award' => $award,
                'user' => $award->winner->user,
                'raffle' => $raffle
            ]);
        }

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
       $template = 'emails.complete-raffles';
       
       foreach($users  as $user){
        $name = $raffle->name;
        sendEmail($user->email, "Detalles del sorteo de la rifa $name",$template,[
         'user' => $user,
         'raffle' => $raffle,
         'winners' => $awardsWinners
        ]);
    }
    }
}
