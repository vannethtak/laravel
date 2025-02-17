<?php

namespace Modules\Settings\App\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Settings\App\Models\SystemLog;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ActivityLogDataTable extends DataTable
{
    private $module = 'settings';

    private $tableName = 'activity_log';

    private $pageName = 'activity-log';

    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->setRowClass(function ($table) {
                return 'row_reload_'.$table->id;
            })
            ->editColumn('description', function ($table) {
                $bg_color = config('setting.badge_success');
                if ($table->description == 'updated') {
                    $bg_color = config('setting.badge_warning');
                } elseif ($table->description == 'deleted') {
                    $bg_color = config('setting.badge_danger');
                }

                return '<span class="'.$bg_color.'">'.ucfirst($table->description).'</span>';
            })
            ->editColumn('showDetail', function ($table) {
                $class = 'show_properties';
                $text_color = '';
                if ($table->description == 'deleted') {
                    $class = '';
                    $text_color = 'text-default';
                }

                return '<a href="javascript:void(0)" log_id="'.$table->id.'" class="'.$class.' '.$text_color.'">'.ucfirst($table->default_field).'</a>';
            })
            ->editColumn('created_at', function ($table) {
                return \Carbon\Carbon::parse($table->created_at)->format('Y-m-d H:i:s');
            })

            ->rawColumns(['showDetail', 'description', 'created_at']); // allowed for using html code here
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(SystemLog $model): QueryBuilder
    {
        $model = $model->newQuery();
        $model->join('users', 'activity_log.causer_id', '=', 'users.id');
        $model->select($this->tableName.'.*', 'users.name as action_user'); // Only select valid columns

        if (request('name')) {
            $model->where(function ($query) {
                $query->orWhere('log_name', 'like', '%'.request('name').'%')
                    ->orWhere('description', 'like', '%'.request('name').'%')
                    ->orWhere('users.name', 'like', '%'.request('name').'%')
                    ->orWhere('default_field', 'like', '%'.request('name').'%');
            });
        }

        $model->orderBy('created_at', 'desc');

        return $model;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId($this->tableName.'-table')
            ->columns($this->getColumns())
            ->ajax([
                'data' => 'function(d) {
                            d.name = $("#name").val();
                        }',
            ])
            ->parameters([
                'initComplete' => 'function() {
                            $("#filter").submit(function(event) {
                                event.preventDefault();
                                $("#'.$this->tableName.'-table").DataTable().ajax.reload();
                            });
                        }',
            ]);
        // ->orderBy([8, "DESC"])
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex', trans('global.no'))->width(30)->addClass('text-center'),
            Column::make('action_user', 'action_user')->title(__('Action By'))->width(60),
            Column::make('log_name')->title(trans('Log On'))->width(30),
            Column::computed('showDetail')->title(trans('Log Detail'))->width(120),
            Column::make('description')->title(trans('Log Event'))->width(120),
            Column::make('ip_address')->title(trans('IP Address'))->width(80),
            Column::make('browser')->title(trans('Browser'))->width(80),
            Column::make('browser_version')->title(trans('Browser Version'))->width(80),
            Column::make('device')->title(trans('Device'))->width(80),
            Column::make('platform')->title(trans('Platform'))->width(60),
            Column::make('created_at')->title(trans('Log Date'))->width(60),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return $this->tableName.'_'.date('YmdHis');
    }
}
