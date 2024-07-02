<?php

namespace App\Models;

use App\Core\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CardTransaction extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'status_code',
        'transaction_id',
        'payload',
        'user_id',
        'created_by',
        'updated_by'
    ];
    
}
