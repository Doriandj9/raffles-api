<?php

namespace Modules\Seller\app\Models;

use App\Core\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Seller\app\Models\Realationship\CommissionsRealationship;
use Modules\Seller\Database\factories\CommissionsFactory;
use Modules\UserRaffle\app\Models\Raffle;

class Commissions extends BaseModel
{
    use HasFactory,CommissionsRealationship;

    public const STATUS_CANCEL = 'CL'; //Cancelado por el propietario de lrita
    public const STATUS_ACTIVE = 'AC'; //Activo acepta afiliacion
    public const STATUS_DRAFT = 'DR'; //Borrado por defecto
    public const STATUS_DROP_OUT = 'DO'; //dado de baja por su decision
    public const STATUS_COMPLETE = 'CO'; // Cuando ya sido pagado su commision
    private  $raffle;
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

    protected $appends = ['total_commissions','tickets_pos','tickets_sales','tickets_pending','amount_paid','sales_physicals'];
    
    protected static function newFactory(): CommissionsFactory
    {
        return CommissionsFactory::new();
    }


    public function getTotalCommissionsAttribute(){

        $commissions = Sales::where('commissions_id',$this->id)
        ->where('is_complete',true)
        ->get();
        $total = $commissions->sum(function($commission){
            return $commission->value;
        });

        return  round($total,2);
    }

    public function getTicketsSalesAttribute(){

        $commissions = Sales::where('commissions_id',$this->id)
        ->where('is_complete',true)
        ->get();

        return $commissions->count();
    }

    public function getTicketsPendingAttribute(){

        $commissions = Sales::where('commissions_id',$this->id)
        ->where('is_complete',false)
        ->get();

        return $commissions->count();
    }

    public function getAmountPaidAttribute(){

        $commissions = Sales::where('commissions_id',$this->id)
        ->where('is_sales_code',false)
        ->get();

        return $commissions->count();
    }

    public function getSalesPhysicalsAttribute(){
        if(!$this->raffle){
            $this->raffle = Raffle::find($this->raffles_id);
        }

        $commissions = Sales::where('commissions_id',$this->id)
        ->where('is_sales_code',false)
        ->get();

        return round($commissions->count() * $this->raffle->price,2);
    }

    public function getTicketsPosAttribute(){
        $commissions = Sales::where('commissions_id',$this->id)
        ->where('is_sales_code',true)
        ->get();

        return $commissions->count();
    }

}
