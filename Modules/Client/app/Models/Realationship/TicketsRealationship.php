<?php

namespace Modules\Client\app\Models\Realationship;

use App\Models\User;
use Modules\UserRaffle\app\Models\Raffle;

trait TicketsRealationship {
    public function raffle(){
        return $this->hasOne(Raffle::class,'id', 'raffles_id');
    }

    public function user(){
        return $this->hasOne(User::class,'taxid', 'user_taxid');
    }
}