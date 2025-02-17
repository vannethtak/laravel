<?php

namespace Modules\Settings\App\Models;

use App\Traits\RecordUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Settings\App\Models\Traits\ImportScope;
use Modules\Settings\App\Models\Traits\Relationship;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasFactory, ImportScope, LogsActivity, Notifiable, RecordUser, Relationship, SoftDeletes;

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'username', 'role_id', 'order',
        'phone', 'password', 'avatar', 'locale', 'active'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    protected static $logFillable = true;

    protected static $logOnlyDirty = true;

    protected static $dontSubmitEmptyLogs = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName($this->table)
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function tapActivity(Activity $activity)
    {
        $activity->default_field = "{$this->name}";
        $activity->log_name = $this->table;
    }
}
