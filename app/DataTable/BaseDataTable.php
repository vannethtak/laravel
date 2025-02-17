<?php

namespace App\DataTable;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

abstract class BaseDataTable extends DataTable
{
    protected string $module;

    protected string $tableName;

    protected string $pageName;

    /**
     * Build the DataTable class.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->setRowClass(fn ($table) => 'row_reload_'.$table->id)
            ->addColumn('action', fn ($table) => view($this->module.'::'.$this->pageName.'.action', ['table' => $table]))
            ->editColumn('active', fn ($table) => $table->active == 'Y'
                ? '<span class="font-weight-bold text-success">'.trans('active_yes').'</span>'
                : '<span class="text-danger">'.trans('active_no').'</span>')
            ->rawColumns(['active', 'action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(QueryBuilder $query): QueryBuilder
    {
        if ($softDelete = request('soft_delete')) {
            $query->withTrashed();
            if ($softDelete == 'deleted') {
                $query->where($this->tableName.'.deleted_at', '!=', null);
            }
        }

        if ($active = request('active')) {
            $query->active($active);
        }

        return $query;
    }

    /**
     * Method to get the html builder for DataTables.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId($this->tableName.'-table')
            ->columns($this->getColumns())
            ->ajax([
                'data' => 'function(d) {
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
                'buttons' => [
                    'copy', 'csv', 'excel', 'pdf', 'print',
                ],
            ])

            ->orderBy([2, 'ASC']);
    }

    /**
     * Get the DataTable columns definition.
     */
    public function getColumns(): array
    {
        return array_merge([
            Column::computed('action', trans('action'))->exportable(false)->printable(false)->width(50)->addClass('text-center'),
            Column::computed('DT_RowIndex', trans('no.'))->width(50)->addClass('text-center'),
        ], $this->additionalColumns(), [
            Column::make('order')->title(trans('order'))->width(10)->addClass('text-center'),
            Column::make('active')->title(trans('active'))->width(10)->addClass('text-center'),
            Column::make('created_at')->title(trans('created_at'))->width(10)->addClass('text-center'),
        ]);
    }

    /**
     * Method to allow child classes to define additional columns.
     * Override this method in child classes to add custom columns.
     */
    protected function additionalColumns(): array
    {
        return [];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return $this->tableName.'_'.date('YmdHis');
    }
}
