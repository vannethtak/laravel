<?php

namespace Modules\Settings\App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    use HasFactory;

    protected $table = 'activity_log';

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format(config('setting.datetime_format_24h'));
    }
}
