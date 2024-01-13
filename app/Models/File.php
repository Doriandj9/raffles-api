<?php

namespace App\Models;

use App\Core\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'path',
        'fileable_id',
        'type',
        'created_by',
        'updated_by',
    ];
}
