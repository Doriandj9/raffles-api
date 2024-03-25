<?php

namespace Modules\Seller\app\Models\Realationship;

use App\Models\User;
use Modules\UserRaffle\app\Models\Raffle;

trait CommissionsRealationship {
    public function raffle(){
        return $this->belongsTo(Raffle::class,'raffles_id', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_taxid','taxid');
    }
}