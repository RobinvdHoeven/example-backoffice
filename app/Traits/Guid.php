<?php

namespace App\Traits;

trait Guid
{
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->guid = md5(uniqid());
        });
    }
}
