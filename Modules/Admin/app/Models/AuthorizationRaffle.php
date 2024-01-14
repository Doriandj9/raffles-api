<?php

namespace Modules\Admin\app\Models;

use App\Core\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\app\Models\Realationship\AuthRaffleRealationship;
use Modules\Admin\Database\factories\AuthorizationRaffleFactory;

class AuthorizationRaffle extends BaseModel
{
    use HasFactory, AuthRaffleRealationship;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'file_id',
        'created_by',
        'updated_by'
    ];

    protected $with = ['user','file'];
    
    protected static function newFactory(): AuthorizationRaffleFactory
    {
        return AuthorizationRaffleFactory::new();
    }
}
