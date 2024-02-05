<?php

namespace Modules\UserRaffle\app\Models\Realationship;

use App\Models\User;

trait RaffleRealationship {
    public function user(){
        return $this->belongsTo(User::class,'user_taxid', 'taxid');
    }
}