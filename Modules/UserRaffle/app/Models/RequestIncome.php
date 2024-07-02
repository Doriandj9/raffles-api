<?php

namespace Modules\UserRaffle\app\Models;

use App\Core\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\UserRaffle\app\Models\Realationship\IncomeRealationship;

class RequestIncome extends BaseModel
{
    use HasFactory, IncomeRealationship;
    public const STATUS_DRAFT = 'DR';
    public const STATUS_ACCEPT = 'AC';
    public const STATUS_CANCEL = 'CL';
    public const STATUS_INPROGRESS = 'DO';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'raffle_id',
        'user_id',
        'amount',
        'observation',
        'status',
        'is_active',
        'voucher',
        'created_by',
        'updated_by',
    ];
 
    protected $with = ['user','raffle'];
}
