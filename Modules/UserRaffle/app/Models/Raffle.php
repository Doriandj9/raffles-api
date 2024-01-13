<?php

namespace Modules\UserRaffle\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\UserRaffle\Database\factories\RaffleFactory;

class Raffle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): RaffleFactory
    {
        //return RaffleFactory::new();
    }
}
