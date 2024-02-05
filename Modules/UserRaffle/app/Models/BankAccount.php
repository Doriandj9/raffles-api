<?php

namespace Modules\UserRaffle\app\Models;

use App\Core\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\UserRaffle\Database\factories\BankAccountsFactory;

class BankAccount extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name_account',
        'account_number',
        'bank_name',
        'qr_image',
        'type',
        'is_account_local',
        'taxid',
        'user_id',
        'created_by',
        'updated_by'
    ];
    
    protected static function newFactory(): BankAccountsFactory
    {
        return BankAccountsFactory::new();
    }
}
