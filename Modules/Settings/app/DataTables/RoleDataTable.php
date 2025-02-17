<?php

namespace Modules\Settings\App\DataTables;

use App\DataTable\BaseDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Settings\App\Models\Role;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;

class RoleDataTable extends BaseDataTable
{
    protected string $module = 'settings';

    protected string $tableName = 'roles';

    protected string $pageName = 'role';

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return parent::dataTable($query)
            ->editColumn('icon', fn ($table) => '<i class="'.$table->icon.'"></i>')
            ->rawColumns(['active', 'icon']);
    }

    public function query(QueryBuilder $query): QueryBuilder
    {
        $query = Role::query()
            ->softDelete(request('soft_delete'))
            ->active(request('active'));
        $query->where('slug', '!=', 'owner');
        if ($name = request('name')) {
            $query->where(fn ($query) => $query->orWhere('name_en', 'like', "%$name%")
                ->orWhere('name_kh', 'like', "%$name%"));
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
            // Column::computed('icon', trans('icon'))->width(10)->addClass('text-center'),
            Column::make('name_en', 'name_en')->title(trans('role_name_en')),
            Column::make('name_kh', 'name_kh')->title(trans('role_name_kh')),
            Column::make('slug', 'slug')->title(trans('slug')),
        ]);
    }

    protected function filename(): string
    {
        return $this->tableName.'_'.date('YmdHis');
    }
}
