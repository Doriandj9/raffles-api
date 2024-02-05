<?php

namespace Modules\Admin\app\Models\Realationship;

use App\Models\File;
use App\Models\User;

trait SubscriptionRealationship {
    public function user(){
        return $this->hasOne(User::class,'id', 'created_by');
    }

}