<?php

namespace Modules\Settings\App\DataTables;

use App\DataTable\BaseDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\App;
use Modules\Settings\App\Models\Page;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;

class PageDataTable extends BaseDataTable
{
    protected string $module = 'settings';

    protected string $tableName = 'pages';

    protected string $pageName = 'page';

    private $locale;

    public function __construct()
    {
        parent::__construct();
        $this->locale = 'name_'.App::getLocale();
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return parent::dataTable($query)
            ->editColumn('module', fn ($table) => $table->{$this->locale})
            ->rawColumns(['active', 'icon']);
    }

    public function query(QueryBuilder $query): QueryBuilder
    {
        $query = Page::query()
            ->softDelete(request('soft_delete'))
            ->active(request('active'));

        if ($name = request('name')) {
            $query->where(fn ($query) => $query->orWhere('name_en', 'like', "%$name%")
                ->orWhere('name_kh', 'like', "%$name%"));
        }
        if ($module_id = request('module_id')) {
            $query->where('module_id', $module_id);
        }
        $query->orderBy('order', 'asc')->orderBy('module_id', 'asc');

        return parent::query($query);
    }

    public function html(): HtmlBuilder
    {
        return parent::html()->ajax([
            'data' => 'function(d) {
                d.name = $("#name").val();
                d.module_id = $("#module_id").val();
                d.soft_delete = $("#soft_delete").val();
                d.active = $("#active").val();
            }',
        ]);
    }

    public function additionalColumns(): array
    {
        return array_merge(parent::additionalColumns(), [
            Column::computed('icon', trans('icon'))->width(10)->addClass('text-center'),
            Column::make('name_en', 'name_en')->title(trans('module_name_en')),
            Column::make('name_kh', 'name_kh')->title(trans('module_name_kh')),
            Column::computed('module', 'module')->title(trans('module')),
            Column::make('slug', 'slug')->title(trans('slug')),

        ]);
    }

    protected function filename(): string
    {
        return $this->tableName.'_'.date('YmdHis');
    }
}
