<?php

namespace Modules\Client\app\Models\Realationship;

use App\Models\File;
use App\Models\User;

trait RatingRelationship {
    public function user(){
        return $this->belongsTo(User::class,'user_id', 'id');
    }
}