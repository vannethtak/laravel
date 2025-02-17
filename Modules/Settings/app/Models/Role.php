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

// use Modules\Settings\Database\Factories\RoleFactory;

class Role extends Model
{
    use HasFactory, ImportScope, LogsActivity, RecordUser, Relationship, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'roles';

    protected $fillable = [
        'name_en', 'name_kh', 'slug', 'description', 'order', 'active', 'created_by', 'updated_by', 'deleted_by',
    ];

    public static function parentPermissions($field_id)
    {
        $pageId = PageAction::select(['page_id'])->find($field_id);
        $moduleId = Page::select(['module_id'])->find($pageId->page_id);
        $result = ['module_id' => $moduleId->module_id, 'page_id' => $pageId->page_id, 'action_id' => $field_id];

        return $result;
    }

    // Role Permissions
    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class, 'role_id');
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
        $activity->default_field = "{$this->name_en} - {$this->name_kh}";
        $activity->log_name = $this->table;
    }
}
