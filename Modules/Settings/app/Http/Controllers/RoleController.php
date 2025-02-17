<?php

namespace Modules\Settings\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Settings\App\DataTables\RoleDataTable;
use Modules\Settings\App\Models\Module;
use Modules\Settings\App\Models\Page;
use Modules\Settings\App\Models\PageAction;
use Modules\Settings\App\Models\Role;
use Modules\Settings\App\Models\RolePermission;

class RoleController extends Controller
{
    private $module = 'settings';

    private $page_name = 'role.';

    private $table_name = 'roles';

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
        $this->data['load_page'] = $this->load_page;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(RoleDataTable $dataTable)
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
        $data['modules'] = Module::select('id', 'name_'.app()->getLocale().' as name', 'icon')->where('active', 'Y')->orderBy('order', 'asc')->get()->toArray();
        $data['page'] = Page::select('id', 'name_'.app()->getLocale().' as name', 'module_id', 'icon')->where('active', 'Y')->orderBy('order', 'asc')->get()->toArray();
        $data['pageAction'] = PageAction::select('id', 'name_'.app()->getLocale().' as name', 'page_id', 'icon')->where('active', 'Y')->orderBy('order', 'asc')->get()->toArray();

        return view($this->load_page.'create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name_en' => 'required|string|max:255|unique:modules,name_en',
            'name_kh' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:modules,slug',
            'order' => 'nullable|numeric',
            'active' => 'required|string|in:Y,N',

            'action_id' => 'nullable|array',
            'action_id.*' => 'integer|distinct',
        ]);
        if ($validator->fails()) {
            notify_error(trans('create_error'), $validator->errors());

            return redirect()->route($this->prefix_route.'create')->withErrors($validator);
        }
        $active = $request->input('active') ?? 'Y';

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name_en' => $request->input('name_en'),
                'name_kh' => $request->input('name_kh'),
                // 'slug' => slug($request->input('name_en')),
                'slug' => slug($request->input('slug')),
                'active' => $active,
                'order' => $request->input('order') ?? 0,
            ]);
            $action_id = $request->input('action_id');
            if ($action_id) {
                foreach ($action_id as $action) {
                    RolePermission::create([
                        'role_id' => $role->id,
                        'action_id' => $action,
                    ]);
                }
            }
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
        $data['field_data'] = Role::find($field_data);
        $data['modules'] = Module::select('id', 'name_'.app()->getLocale().' as name', 'icon')
            ->where('active', 'Y')
            ->orderBy('order', 'asc')
            ->get()
            ->toArray();
        $data['page'] = Page::select('id', 'name_'.app()->getLocale().' as name', 'module_id', 'icon')
            ->where('active', 'Y')
            ->orderBy('order', 'asc')
            ->get()
            ->toArray();
        $data['pageAction'] = PageAction::select('id', 'name_'.app()->getLocale().' as name', 'page_id', 'icon')
            ->where('active', 'Y')
            ->orderBy('order', 'asc')
            ->get()
            ->toArray();
        $data['rolePermission'] = RolePermission::where('role_id', $field_data)
            ->pluck('action_id')
            ->toArray();

        return view($this->load_page.'edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $field_data)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name_en' => 'required|string|max:255|unique:modules,name_en,'.$field_data,
            'name_kh' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:modules,slug,'.$field_data,
            'order' => 'nullable|numeric',
            'active' => 'required|string|in:Y,N',

            'action_id' => 'nullable|array',
            'action_id.*' => 'integer|distinct',
        ]);
        if ($validator->fails()) {
            notify_error(trans('update_error'), $validator->errors());

            return redirect()->route($this->prefix_route.'edit', $field_data)->withErrors($validator);
        }
        $field_data = Role::find($field_data);
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
                'slug' => slug($request->input('slug')) ? slug($request->input('slug')) : $field_data->slug,
                'order' => $request->input('order') ?? $field_data->order,
                'active' => $active,
            ]);
            $action_id = $request->input('action_id');
            RolePermission::where('role_id', $field_data->id)->delete();
            if ($action_id) {
                foreach ($action_id as $action) {
                    RolePermission::create([
                        'role_id' => $field_data->id,
                        'action_id' => $action,
                    ]);
                }
            }
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
        $field_data = Role::find($field_data);
        $field_data->delete();
        notify_success(trans('delete_success'), trans('delete_success_message'));

        return redirect()->route($this->prefix_route.'index');
    }

    public function restore($field_data)
    {
        $field_data = Role::where('id', $field_data);
        $field_data->restore();
        notify_success(trans('restore_success'), trans('restore_success_message'));

        return redirect()->route($this->prefix_route.'index');
    }
}
