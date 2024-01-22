<?php

namespace Modules\Client\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Client\Database\factories\TicketFactory;

class Ticket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'qr_image',
        'raffles_id',
        'user_taxid',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
    
    protected static function newFactory(): TicketFactory
    {
        return TicketFactory::new();
    }
}
