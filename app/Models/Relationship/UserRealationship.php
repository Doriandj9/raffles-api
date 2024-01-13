<?php

namespace App\Models\Relationship;

use App\Models\File;
use App\Models\User;

trait UserRealationship {

    public function raffles(){
        return $this->belongsToMany(User::class,'raffles_sellers','seller_id','raffles_id');
    }

    public function seller(){
        return $this->belongsToMany(User::class,'raffles_sellers','raffles_id','seller_id');
    }

    public function filesPaymentPlan(){
        return $this->hasMany(File::class,'fileable_id','id')
        ->where('type','LIKE','%raffles_payment_plan%')
        ->orderBy('created_at', 'ASC');
    }
}