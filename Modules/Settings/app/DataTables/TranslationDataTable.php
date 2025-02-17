<?php

namespace Modules\Settings\App\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Modules\Settings\App\Models\Translation;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TranslationDataTable extends DataTable
{
    private $module = 'settings';

    private $tableName = 'translations';

    private $pageName = 'translation';

    private $locales;

    public function __construct()
    {
        $this->locales = DB::table('locales')->whereNull('deleted_at')->pluck('locale', 'id');
    }

    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->setRowClass(function ($row) {
                return 'row_reload_'.$row->key;
            })
            ->addColumn('action', function ($row) {
                return view($this->module.'::'.$this->pageName.'.action', ['table' => $row]); // Change $row to $table
            })
            ->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Translation $model): QueryBuilder
    {
        $selectColumns = [
            'translations.key',
            'translations.active',
            'translations.created_at',
            'translations.deleted_at',
        ];

        foreach ($this->locales as $localeId => $localeCode) {
            $selectColumns[] = DB::raw("MAX(CASE WHEN translations.locale_id = {$localeId} THEN translations.value ELSE NULL END) as value_{$localeCode}");
        }

        $query = $model->newQuery()
            ->select($selectColumns)
            ->groupBy('translations.key', 'translations.active', 'translations.created_at', 'translations.deleted_at');

        if (request('name')) {
            $query->where(function ($subQuery) {
                $subQuery->where('translations.key', 'like', '%'.request('name').'%')
                    ->orWhere('translations.value', 'like', '%'.request('name').'%');
            });
        }

        if (request('soft_delete')) {
            if (request('soft_delete') == 'deleted') {
                $query->withTrashed()->whereNotNull('translations.deleted_at');
            } elseif (request('soft_delete') == 'all_records') {
                $query->withTrashed();
            }
        }

        if (request('active')) {
            $query->where('translations.active', request('active'));
        }

        return $query;
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
        $columns = [
            Column::computed('action', trans('action'))->exportable(false)->printable(false)->width(50)->addClass('text-center'),
            Column::computed('DT_RowIndex', trans('no.'))->width(50)->addClass('text-center'),
            Column::make('key')->title(trans('key')),
        ];

        foreach ($this->locales as $locale) {
            $columns[] = Column::make("value_{$locale}")->title(trans("value_{$locale}"));
        }

        $columns[] = Column::make('active')->title(trans('active'))->width(10)->addClass('text-center');
        $columns[] = Column::make('created_at')->title(trans('created_at'))->width(10)->addClass('text-center');

        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return $this->tableName.'_'.date('YmdHis');
    }
}
