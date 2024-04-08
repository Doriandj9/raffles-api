<?php

namespace Modules\Seller\app\Models\Realationship;

use Modules\Client\app\Models\Ticket;

trait SalesRealationship {
    public function ticket(){
        return $this->hasOne(Ticket::class,'id', 'tickets_id');
    }
}