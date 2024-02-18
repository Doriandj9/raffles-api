<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Relationship\UserRealationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,UserRealationship;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'taxid',
        'first_name',
        'last_name',
        'username',
        'permission',
        'rol',
        'phone',
        'is_new',
        'is_active',
        'is_pending',
        'is_client',
        'is_raffles',
        'is_seller',
        'is_admin',
        'start_date_supcription',
        'end_date_suscription',
        'token',
        'nationality',
        'address',
        'send_email',
        'email_verified_at',
        'subscription_id',
        'organize_riffs'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $with = ['subscription','bankAccounts']; 
}
