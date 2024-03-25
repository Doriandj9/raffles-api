<?php

namespace Modules\Seller\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Seller\app\Models\Realationship\CommissionsRealationship;
use Modules\Seller\Database\factories\CommissionsFactory;

class Commissions extends Model
{
    use HasFactory,CommissionsRealationship;

    public const STATUS_CANCEL = 'CL'; //Cancelado por el propietario de lrita
    public const STATUS_ACTIVE = 'AC'; //Activo acepta afiliacion
    public const STATUS_DRAFT = 'DR'; //Borrado por defecto
    public const STATUS_DROP_OUT = 'DO'; //dado de baja por su decision
    public const STATUS_COMPLETE = 'CO'; // Cuando ya sido pagado su commision
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'raffles_id',
        'user_taxid',
        'seller_pos',
        'url',
        'code',
        'status',
        'is_paid',
        'path_qr',
        'created_by',
        'updated_by',
    ];

    protected $appends = ['total_commissions','tickets_sales'];
    
    protected static function newFactory(): CommissionsFactory
    {
        return CommissionsFactory::new();
    }


    public function getTotalCommissionsAttribute(){

        $commissions = Sales::where('commissions_id',$this->id)->get();
        $total = $commissions->sum(function($commission){
            return $commission->value;
        });

        return  round($total,2);
    }

    public function getTicketsSalesAttribute(){

        $commissions = Sales::where('commissions_id',$this->id)->get();

        return $commissions->count();
    }

}
