<?php

namespace Modules\Client\app\Models\Realationship;

use App\Models\File;
use App\Models\User;

trait ReceiptRealationship {
    public function user(){
        return $this->hasOne(User::class,'id', 'user_id');
    }
}