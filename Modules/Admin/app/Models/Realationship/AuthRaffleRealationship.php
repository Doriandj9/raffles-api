<?php

namespace Modules\Admin\app\Models\Realationship;

use App\Models\File;
use App\Models\User;

trait AuthRaffleRealationship {
    public function user(){
        return $this->hasOne(User::class,'id', 'user_id');
    }

    public function file(){
        return $this->hasOne(File::class,'id', 'file_id');
    }
}