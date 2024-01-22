<?php

namespace Modules\UserRaffle\app\Models;

use App\Core\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\UserRaffle\Database\factories\RaffleFactory;

class Raffle extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'draw_date',
        'logo_raffles',
        'user_taxid',
        'description',
        'subscriptions_id',
        'summary',
        'price',
        'awards',
        'commission_sellers',
        'created_by',
        'updated_by',
    ];
    
    protected static function newFactory(): RaffleFactory
    {
        return RaffleFactory::new();
    }
}
