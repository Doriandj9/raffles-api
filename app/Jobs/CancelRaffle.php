<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Client\app\Models\Ticket;
use Modules\UserRaffle\app\Models\Raffle;

class CancelRaffle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
        
        public function __construct(private string | int $idRaffle, private string $description, private string $phone)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $raffle = Raffle::find($this->idRaffle);

        $tickets = Ticket::where('raffles_id',$raffle->id)
        ->whereIn('is_buy',[true, false])
        ->whereNotNull('user_taxid')
        ->groupBy(['user_taxid','tickets.id'])
        ->get();

        foreach($tickets as $ticket){
            $user = $ticket->user;
            $template = 'emails.raffle-cancel';
            $data = [
                'user' => $user,
                'raffle' => $raffle,
                'phone' => $this->phone,
                'description' => $this->description
            ];
            sendEmail($user->email,'Rifa Cancelada',$template,$data);
        }
    }
}
