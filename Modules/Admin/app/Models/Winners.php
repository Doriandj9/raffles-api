<?php

namespace Modules\Admin\app\Models;

use App\Core\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\app\Models\Realationship\WinnersRealationship;
use Modules\Admin\Database\factories\WinnersFactory;

class Winners extends BaseModel
{
    use HasFactory, WinnersRealationship;
    public const STATUS_ACTIVE = 'AC'; //Activo acepta afiliacion
    public const STATUS_DRAFT = 'DR'; //Borrado por defecto
    public const STATUS_INACTIVE = 'IE'; //Borrado por defecto

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
       'is_active',
       'state',
       'payload',
       'raffles_id',
       'created_by',
       'updated_by',
    ];

    protected $with = ['raffle'];
    
    protected static function newFactory(): WinnersFactory
    {
        return WinnersFactory::new();
    }
}
