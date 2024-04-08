<?php

namespace Modules\Seller\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Seller\app\Models\Realationship\SalesRealationship;
use Modules\Seller\Database\factories\SalesFactory;

class Sales extends Model
{
    use HasFactory, SalesRealationship;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'commissions_id',
        'tickets_id',
        'is_sales_code',
        'value',
        'is_complete',
        'created_by',
        'updated_by'
    ];
    
    protected static function newFactory(): SalesFactory
    {
        return SalesFactory::new();
    }
}
