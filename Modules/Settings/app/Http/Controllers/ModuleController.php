<?php

namespace Modules\Settings\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Settings\App\DataTables\ModulesDataTable;
use Modules\Settings\App\Models\Module;

class ModuleController extends Controller
{
    private $module = 'settings';

    private $page_name = 'module.';

    private $table_name = 'modules';

    private $load_page;

    private $prefix_route;

    private $data;

    public function __construct()
    {
        $this->load_page = $this->module.'::'.$this->page_name.'.';
        $this->prefix_route = $this->module.'.'.$this->page_name;
        $this->data['prefix_route'] = $this->prefix_route;
        $this->data['module'] = $this->module;
        $this->data['page_name'] = $this->page_name;
        $this->data['table_name'] = $this->table_name;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ModulesDataTable $dataTable)
    {
        $data = $this->data;

        return $dataTable->render($this->load_page.'index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $this->data;

        return view($this->load_page.'create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_en' => 'required|string|max:255',
            'name_kh' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'order' => 'nullable|numeric',
            'active' => 'required|string|in:Y,N',
        ]);
        if ($validator->fails()) {
            notify_error(trans('create_error'), $validator->errors());

            return redirect()->route($this->prefix_route.'create')->withErrors($validator);
        }
        $active = $request->input('active') ?? 'Y';

        DB::beginTransaction();
        try {
            Module::create([
                'name_en' => $request->input('name_en'),
                'name_kh' => $request->input('name_kh'),
                'slug' => slug($request->input('name_en')),
                'icon' => $request->input('icon') ?? 'fas fa-folder',
                'active' => $active,
                'order' => $request->input('order') ?? 0,
            ]);
            DB::commit();
            notify_success(trans('create_success'), trans('create_success_message'));

            return redirect()->route($this->prefix_route.($request->input('submit') == 'save' ? 'index' : 'create'));
        } catch (\Exception $e) {
            DB::rollBack();
            notify_error(trans('create_error'), trans('create_error_message'));

            return redirect()->route($this->prefix_route.'index')->withErrors($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($field_data)
    {
        $data = $this->data;
        $data['field_data'] = Module::find($field_data);

        return view($this->load_page.'edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $field_data)
    {
        $validator = Validator::make($request->all(), [
            'name_en' => 'required|string|max:255',
            'name_kh' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'order' => 'nullable|numeric',
            'active' => 'required|string|in:Y,N',
        ]);
        if ($validator->fails()) {
            notify_error(trans('update_error'), $validator->errors());

            return redirect()->route($this->prefix_route.'edit', $field_data)->withErrors($validator);
        }
        $field_data = Module::find($field_data);
        if (! $field_data) {
            notify_error(trans('update_error'), trans('field_data_not_found'));

            return redirect()->route($this->prefix_route.'index');
        }
        $active = $request->input('active') ?? 'Y';

        DB::beginTransaction();
        try {
            $field_data->update([
                'name_en' => $request->input('name_en') ?? $field_data->name_en,
                'name_kh' => $request->input('name_kh') ?? $field_data->name_kh,
                'slug' => slug($request->input('name_en')) ? slug($request->input('name_en')) : $field_data->slug,
                'icon' => $request->input('icon') ?? $field_data->icon,
                'order' => $request->input('order') ?? $field_data->order,
                'active' => $active,
            ]);
            DB::commit();
            notify_success(trans('update_success'), trans('update_success_message'));

            return redirect()->route($this->prefix_route.'index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify_error(trans('update_error'), trans('update_error_message'));

            return redirect()->route($this->prefix_route.'index')->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($field_data)
    {
        $field_data = Module::find($field_data);
        if (! $field_data) {
            notify_error(trans('delete_error'), trans('delete_error_message'));

            return redirect()->route($this->prefix_route.'index');
        }
        if ($field_data->slug === 'dashboard' || $field_data->slug === 'settings') {
            notify_error(trans('delete_error'), trans('delete_error_dashboard_message'));

            return redirect()->route($this->prefix_route.'index');
        }
        $field_data->delete();
        notify_success(trans('delete_success'), trans('delete_success_message'));

        return redirect()->route($this->prefix_route.'index');
    }

    public function restore($field_data)
    {
        $field_data = Module::where('id', $field_data);
        $field_data->restore();
        notify_success(trans('restore_success'), trans('restore_success_message'));

        return redirect()->route($this->prefix_route.'index');
    }
}
