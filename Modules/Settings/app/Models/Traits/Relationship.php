<?php

namespace Modules\Settings\App\Models\Traits;

use Illuminate\Support\Str;
use Modules\Settings\App\Models\Module;
use Modules\Settings\App\Models\Page;
use Modules\Settings\App\Models\PageAction;
use Modules\Settings\App\Models\Role;
use Modules\Settings\App\Models\RolePermission;
use Modules\Settings\App\Models\User;
use Modules\Settings\App\Models\UserPermission;

trait Relationship
{
    // User Relationship
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Module Relationships
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    // Page Relationships
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function pageActions()
    {
        return $this->hasMany(PageAction::class);
    }

    public function actionDefault()
    {
        return $this->hasOne(PageAction::class)->where('type', $this->default_action);
    }

    // Role Permissions
    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class);
    }

    // User Permissions
    public function userPermissions()
    {
        return $this->hasMany(UserPermission::class, 'user_id', 'id');
    }

    // SystemLog Action Model
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'causer_id');
    }

    // // Accessor for avatar URL
    // public function getAvatarUrlAttribute()
    // {
    //     $name = Str::of($this->name)
    //         ->trim()
    //         ->explode(' ')
    //         ->map(fn (string $segment): string => filled($segment) ? mb_substr($segment, 0, 1) : '')
    //         ->join(' ');

    //     $avatarUrl = assetFile(config('setting.disk_name'), $this->image)
    //         ? assetFile(config('setting.disk_name'), $this->image)
    //         : 'https://ui-avatars.com/api/?name='.urlencode($name);

    //     return $avatarUrl;
    // }
}
