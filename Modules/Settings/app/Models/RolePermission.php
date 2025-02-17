<?php

namespace Modules\Settings\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Settings\App\Models\Traits\ImportScope;
use Modules\Settings\App\Models\Traits\Relationship;

class RolePermission extends Model
{
    use HasFactory, ImportScope, Relationship;

    protected $table = 'role_permissions';

    protected $fillable = [
        'role_id', 'action_id', 'created_at', 'updated_at',
    ];
}
