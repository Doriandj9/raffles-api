<?php

namespace App\Core;

trait AuditBoot {
    public static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()){
            static::creating(function($model){
                return $model->fill([
                    'user_id' => auth()->id() ? auth()->id() : request()->user_id,
                    'created_by' => auth()->id() ? auth()->id() : request()->user_id,
                    'updated_by' => auth()->id() ? auth()->id() : request()->user_id,
                ]);
            });
        }

        static::updated(function($model){
            return $model->fill([
                'updated_by' => auth()->id(),
            ]);
        });                
    }
}