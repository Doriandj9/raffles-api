<?php

namespace App\Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWToken {

    private const ALG = 'HS256';
    private const KEY = 'Digital_Educas_2023_Raffles';
    public static function create($data): string
    {
        $token = JWT::encode($data,self::KEY,self::ALG);
        return $token;
    }

    public static function verify($token): bool
    {
        $key = new Key(self::KEY,self::ALG);
        try {
            JWT::decode($token,$key);
        } catch (\Throwable $e) {
            return false;        
        }
    }
}