<?php

namespace Modules\Settings\App\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Settings\App\Models\Module;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ModulesDataTable extends DataTable
{
    private $module = 'settings';

    private $tableName = 'modules';

    private $pageName = 'module';

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
            ->addColumn('action', function ($table) {
                return view($this->module.'::'.$this->pageName.'.action', ['table' => $table]);
            })
            ->editColumn('active', function ($table) {
                // $active_status = ($table->active == 'Y') ? '<span class="'.config('setting.badge_success').'">'.trans('active_yes').'</span>' : '<span class="'.config('setting.badge_danger').'">'.trans('active_no').'</span>';
                $active_status = ($table->active == 'Y') ? '<span class="font-weight-bold text-success">'.trans('active_yes').'</span>' : '<span class="text-danger">'.trans('active_no').'</span>';

                return $active_status;
            })
            ->editColumn('icon', function ($table) {
                $icon_viewer = '<i class="'.$table->icon.'"></i>';

                return $icon_viewer;
            })
            ->rawColumns(['active', 'icon']); // allowed for using html code here
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Module $model): QueryBuilder
    {
        $model = $model->newQuery();
        $model->select(['id', 'name_en', 'name_kh', 'icon', 'order', 'active', 'created_at', 'deleted_at']);
        if (request('name')) {
            $model->where(function ($query) {
                $query->orWhere('name_en', 'like', '%'.request('name').'%')->orWhere('name_kh', 'like', '%'.request('name').'%');
            });
        }
        if (request('soft_delete')) {
            // dd(request('soft_delete'));
            if (request('soft_delete') == 'deleted') {
                $model->withTrashed();
                $model->where($this->tableName.'.deleted_at', '!=', null);
            } elseif (request('soft_delete') == 'all_records') {
                $model->withTrashed();
            }
        }
        if (request('active')) {
            $model->active(request('active'));
        }

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
                            d.active = $("#active").val();
                            d.soft_delete = $("#soft_delete").val();
                        }',
            ])
            ->parameters([
                'initComplete' => 'function() {
                            $("#filter").submit(function(event) {
                                event.preventDefault();
                                $("#'.$this->tableName.'-table").DataTable().ajax.reload();
                            });
                        }',
            ])
            ->orderBy([2, 'ASC']);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('action', trans('action'))->exportable(false)->printable(false)->width(50)->addClass('text-center'),
            Column::computed('DT_RowIndex', trans('no.'))->width(50)->addClass('text-center'),
            Column::computed('icon', trans('icon'))->width(10)->addClass('text-center'),
            Column::make('name_en', 'name_en')->title(trans('module_name_en')),
            Column::make('name_kh', 'name_kh')->title(trans('module_name_kh')),
            Column::make('order')->title(trans('order'))->width(10)->addClass('text-center'),
            Column::make('active')->title(trans('active'))->width(10)->addClass('text-center'),
            Column::make('created_at')->title(trans('created_at'))->width(10)->addClass('text-center'),
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
