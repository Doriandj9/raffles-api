<?php

namespace App\Models\Relationship;

use App\Models\File;
use App\Models\User;
use Modules\Admin\app\Models\AuthorizationRaffle;
use Modules\Admin\app\Models\Subscription;
use Modules\Seller\app\Models\Commissions;
use Modules\UserRaffle\app\Models\BankAccount;
use Modules\UserRaffle\app\Models\Raffle;

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

    public function authorizationRaffles(){
        return $this->hasMany(AuthorizationRaffle::class,'user_id','id');
    }

    public function subscription(){
        return $this->hasOne(Subscription::class,'id','subscription_id');
    }

    public function bankAccounts() {
        return $this->hasMany(BankAccount::class,'user_id','id');
    }

    public function rafflesCreated() {
        return $this->hasMany(Raffle::class,'user_taxid','taxid');
    }

    public function commissions() {
        return $this->hasMany(Commissions::class,'user_taxid','taxid');
    }

}