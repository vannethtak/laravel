<?php

namespace Modules\Settings\App\DataTables;

use App\DataTable\BaseDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Settings\App\Models\Configuration;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;

class ConfigurationDataTable extends BaseDataTable
{
    protected string $module = 'settings';

    protected string $tableName = 'configurations';

    protected string $pageName = 'configuration';

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return parent::dataTable($query)
            ->editColumn('icon', fn ($table) => '<i class="'.$table->icon.'"></i>')
            ->rawColumns(['active', 'icon']);
    }

    public function query(QueryBuilder $query): QueryBuilder
    {
        $query = Configuration::query()
            ->softDelete(request('soft_delete'))
            ->active(request('active'));
        if ($name = request('name')) {
            $query->where(fn ($query) => $query->orWhere('key', 'like', "%$name%")
                ->orWhere('value', 'like', "%$name%"));
        }
        $query->orderBy('created_at', 'asc');

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
            Column::make('key', 'key')->title(trans('key')),
            Column::make('value', 'value')->title(trans('value')),
            Column::make('datatype', 'datatype')->title(trans('datatype')),
        ]);
    }

    protected function filename(): string
    {
        return $this->tableName.'_'.date('YmdHis');
    }
}
