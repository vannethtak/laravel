<?php

namespace Modules\Settings\App\Models;

use App\Traits\RecordUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Settings\App\Models\Traits\ImportScope;
use Modules\Settings\App\Models\Traits\Relationship;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Page extends Model
{
    use HasFactory, ImportScope, LogsActivity, RecordUser, Relationship, SoftDeletes;

    protected $table = 'pages';

    protected $fillable = [
        'module_id', 'name_en', 'name_kh', 'slug', 'order', 'icon', 'active', 'created_by', 'updated_by',
    ];

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
        $activity->default_field = "{$this->name_en} - {$this->name_kh}";
        $activity->log_name = $this->table;
    }
}
