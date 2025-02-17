<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait RecordUser
{
    protected static function boot()
    {
        parent::boot();
        if (Auth::user()) {
            $userId = Auth::user()->id;
        } else {
            $userId = 0;
        }
        static::creating(function ($model) use ($userId) {
            $model->created_by = $userId;
        });
        static::updating(function ($model) use ($userId) {
            $model->updated_by = $userId;
        });
        static::deleting(function ($model) use ($userId) {
            $model->deleted_by = $userId;
        });
        // How to boot a model via trait? Event
    }
}
