<?php

namespace Modules\Admin\app\Models\Realationship;

use App\Models\File;
use App\Models\User;
use Modules\UserRaffle\app\Models\Raffle;

trait WinnersRealationship {
    public function raffle(){
        return $this->hasOne(Raffle::class,'id', 'raffles_id');
    }
}