<?php

namespace Modules\Client\app\Models\Realationship;

use Modules\UserRaffle\app\Models\Raffle;

trait TicketsRealationship {
    public function raffle(){
        return $this->hasOne(Raffle::class,'id', 'raffles_id');
    }
}