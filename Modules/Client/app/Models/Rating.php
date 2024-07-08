<?php

namespace Modules\Client\app\Models;

use App\Core\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Client\app\Models\Realationship\RatingRelationship;

// use Modules\Client\Database\factories\RatingFactory;

class Rating extends BaseModel
{
    use HasFactory, RatingRelationship;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'calification',
        'comment',
        'is_active',
        'status',
        'likes',
        'not_likes',
        'created_by',
        'updated_by'
    ];

    protected $with = ['user'];
    
    // protected static function newFactory(): RatingFactory
    // {
    //     return RatingFactory::new();
    // }
}
