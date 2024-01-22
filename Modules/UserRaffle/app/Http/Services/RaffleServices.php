<?php

namespace Modules\UserRaffle\app\Http\Services;

use App\Core\BaseService;
use Modules\Client\app\Models\Ticket;
use Modules\UserRaffle\app\Models\Raffle;

class RaffleServices extends BaseService {

    public function __construct(Raffle $raffle)
    {
        $this->model = $raffle;
    }

    public function customSaveTickets(Raffle $raffle)
    {
        $data = [];
        $date = now();
        $info = [
            'code'  => '',
            'qr_image' => 'pending',
            'raffles_id' => intval($raffle->id),
            'created_by' => $raffle->created_by,
            'updated_by' => $raffle->updated_by,
            'created_at' => $date,
            'updated_at' => $date
        ];

        for($i = 0; $i < intval($raffle->number_tickets); $i++){
            $code = $date->year . '-' . $date->timestamp . '-' . $raffle->id . '-' . ($i + 1);
            $info['code'] = $code;
            array_push($data, $info);
        }

        $chucks = array_chunk($data,1000);
        foreach($chucks as $chuck){
            Ticket::insert($chuck);
        }

        return true;
    }

   
}