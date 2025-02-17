<?php

namespace Modules\Settings\App\DataTables;

use App\DataTable\BaseDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Settings\App\Models\User;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;

class UserDataTable extends BaseDataTable
{
    protected string $module = 'settings';

    protected string $tableName = 'users';

    protected string $pageName = 'user';

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return parent::dataTable($query)
            ->editColumn('icon', function ($table) {
                $icon_viewer = '';
                $img = assetFile(config('setting.disk_name'), $table->avatar) ?? custom_asset('image/me.jpg');
                $icon_viewer = '<img src="'.$img.'" class="img-fluid" alt="avatar">';

                return $icon_viewer;
            })
            ->editColumn('role', function ($table) {
                $role = $table->role->name_en ?? '';

                return $role;
            })
            ->editColumn('last_activity', function ($table) {
                $last_activity = $table->last_activity ?? '';

                return $last_activity;
            })
            ->rawColumns(['active', 'icon']);
    }

    public function query(QueryBuilder $query): QueryBuilder
    {
        $query = User::query()
            ->with('role')
            ->softDelete(request('soft_delete'))
            ->active(request('active'));
        $query->noneOwner();
        if ($name = request('name')) {
            $query->where(fn ($query) => $query->orWhere('name', 'like', "%$name%")
                ->orWhere('name', 'like', "%$name%"))
                ->orWhere('username', 'like', "%$name%");
        }
        $query->orderBy('order', 'asc');

        return parent::query($query);
    }

    public function html(): HtmlBuilder
    {
        return parent::html()->ajax([
            'data' => 'function(d) {
                d.name = $("#name").val();
                d.soft_delete = $("#soft_delete").val();
                d.active = $("#active").val();
            }',
        ]);
    }

    public function additionalColumns(): array
    {
        return array_merge(parent::additionalColumns(), [
            Column::computed('icon', trans('icon'))->width(10)->addClass('text-center'),
            Column::make('name', 'name')->title(trans('full_name')),
            Column::make('username', 'username')->title(trans('username')),
            Column::make('role', 'role')->title(trans('role')),
            Column::make('email', 'email')->title(trans('email')),
            Column::make('phone', 'phone')->title(trans('phone')),
            Column::make('last_activity', 'last_activity')->width(1)->title(trans('last_activity')),
        ]);
    }

    protected function filename(): string
    {
        return $this->tableName.'_'.date('YmdHis');
    }
}
