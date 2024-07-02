<?php

namespace Modules\UserRaffle\app\Models;

use App\Core\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Client\app\Models\Ticket;
use Modules\UserRaffle\app\Models\Realationship\RaffleRealationship;
use Modules\UserRaffle\Database\factories\RaffleFactory;

class Raffle extends BaseModel
{
    use HasFactory, RaffleRealationship;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'draw_date',
        'logo_raffles',
        'is_complete',
        'user_taxid',
        'description',
        'number_tickets',
        'subscriptions_id',
        'summary',
        'income',
        'price',
        'awards',
        'commission_sellers',
        'created_by',
        'updated_by',
        'draw_parameters',
        'draw_details',
        'in_sorter'
    ];

    protected $appends = ['purchased_tickets','pending_tickets','unsold_tickets'];
    protected $with = ['user'];
    
    public function getPurchasedTicketsAttribute(){
        $tickets = Ticket::where('raffles_id',$this->id)
        ->whereNotNull('user_taxid')
        ->where('is_buy',true)
        ->get();
        
        return $tickets->count();
    }

    public function getPendingTicketsAttribute(){
        $tickets = Ticket::where('raffles_id',$this->id)
        ->where('is_buy',false)
        ->whereNotNull('user_taxid')
        ->get();
        
        return $tickets->count();
    }
    
    public function getUnsoldTicketsAttribute(){
        $tickets = Ticket::where('raffles_id',$this->id)
        ->where('is_buy',false)
        ->whereNull('user_taxid')
        ->get();
        
        return $tickets->count();
    }

    protected static function newFactory(): RaffleFactory
    {
        return RaffleFactory::new();
    }

}
