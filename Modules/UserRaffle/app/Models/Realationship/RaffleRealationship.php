<?php

namespace Modules\UserRaffle\app\Models\Realationship;

use App\Models\User;
use Modules\Seller\app\Models\Commissions;
use Modules\UserRaffle\app\Models\RequestIncome;

trait RaffleRealationship {
    public function user(){
        return $this->belongsTo(User::class,'user_taxid', 'taxid');
    }

    public function commissions(){
        return $this->hasMany(Commissions::class,'raffles_id','id');
    }

    public function requestIncome(){
        return $this->hasMany(RequestIncome::class,'raffle_id','id');
    }
}