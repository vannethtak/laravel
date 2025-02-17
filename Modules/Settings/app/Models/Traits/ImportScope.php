<?php

namespace Modules\Settings\App\Models\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

trait ImportScope
{
    public function scopeActive($query, $field_value)
    {
        if ($field_value) {
            return $query->where('active', $field_value);
        }
    }

    public function scopeActiveWithTable($query, $table_name, $field_value)
    {
        return $query->where($table_name.'.active', $field_value);
    }

    // User Administration
    public function scopeNoneOwner($query)
    {
        return $query->where('users.name', '<>', 'owner');
    }

    public function scopeSoftDelete($query, $softDelete)
    {
        if ($softDelete) {
            $query->withTrashed();
            if ($softDelete === 'deleted') {
                $query->whereNotNull('deleted_at');
            }
        }

        return $query;
    }

    // Set Format of Attribute
    public function getCreatedAtAttribute($value)
    {
        $date = Carbon::parse($value)->format(config('setting.date_format'));

        return App::getLocale() === 'kh' ? convert_date_to_khmer($date) : $date;

    }

    public function getUpdatedAtAttribute($value)
    {
        $date = Carbon::parse($value)->format(config('setting.date_format'));

        return App::getLocale() === 'kh' ? convert_date_to_khmer($date) : $date;
    }
}
