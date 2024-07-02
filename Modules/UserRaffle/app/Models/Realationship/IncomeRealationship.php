<?php

namespace Modules\UserRaffle\app\Models\Realationship;

use App\Models\User;
use Modules\UserRaffle\app\Models\Raffle;

trait IncomeRealationship {
    public function user(){
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    public function raffle(){
        return $this->belongsTo(Raffle::class,'raffle_id', 'id');
    }
}