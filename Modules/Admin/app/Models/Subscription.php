<?php

namespace Modules\Admin\app\Models;

use App\Core\AuditBoot;
use App\Core\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Database\factories\SubscriptionFactory;

class Subscription extends BaseModel
{
    
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'subject',
        'description',
        'number_raffles',
        'price',
        'is_unlimited',
        'minimum_tickets',
        'maximum_tickets',
        'created_by',
        'updated_by',
    ];
    
    protected static function newFactory(): SubscriptionFactory
    {
        return SubscriptionFactory::new();
    }
}
