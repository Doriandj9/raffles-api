<?php

namespace Modules\Client\app\Models;

use App\Core\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Client\app\Models\Realationship\ReceiptRealationship;
use Modules\Client\Database\factories\ReceiptFactory;

class Receipt extends BaseModel
{
    use HasFactory, ReceiptRealationship;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'description',
        'organizer_raffles_taxid',
        'total',
        'subtotal',
        'amount',
        'single_price',
        'voucher',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $with = ['user'];
    
    protected static function newFactory(): ReceiptFactory
    {
        return ReceiptFactory::new();
    }
}
