<?php

namespace Modules\Settings\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Settings\App\DataTables\PageDataTable;
use Modules\Settings\App\Models\Module;
use Modules\Settings\App\Models\Page;
use Modules\Settings\App\Models\PageAction;

class PageController extends Controller
{
    private $module = 'settings';

    private $page_name = 'page.';

    private $table_name = 'pages';

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
    public function index(PageDataTable $dataTable)
    {
        $data = $this->data;
        $data['module'] = Module::select('id', 'name_'.app()->getLocale().' as name')->where('active', 'Y')->orderBy('order', 'asc')->get();

        return $dataTable->render($this->load_page.'index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $this->data;
        $data['module'] = Module::where('active', 'Y')->get();

        return view($this->load_page.'create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'module_id' => 'required|int',
            'name_en' => 'required|string|max:255',
            'name_kh' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'order' => 'nullable|numeric',
            'active' => 'required|string|in:Y,N',

            'action_name_en' => 'required|array',
            'action_name_kh' => 'required|array',
            'action_route' => 'required|array',
            'action_type' => 'required|array',
            'action_position' => 'nullable|array',
            'action_icon' => 'nullable|array',
            'action_active' => 'nullable|array',
            // action validation
            'action_name_en.*' => 'required|string|max:255',
            'action_name_kh.*' => 'required|string|max:255',
            'action_route.*' => 'required|string|max:255',
            'action_type.*' => 'required|string|max:255',
            'action_position.*' => 'nullable|string|max:255',
            'action_active.*' => 'nullable|string|in:Y,N',
            'action_icon.*' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            notify_error(trans('create_error'), $validator->errors());

            return redirect()->route($this->prefix_route.'create')->withErrors($validator);
        }
        $active = $request->input('active', 'Y');

        DB::beginTransaction();
        try {
            $page = Page::create([
                'module_id' => $request->input('module_id'),
                'name_en' => $request->input('name_en'),
                'name_kh' => $request->input('name_kh'),
                'slug' => slug($request->input('name_en')),
                'icon' => $request->input('icon') ?? 'fas fa-folder',
                'active' => $active,
                'order' => $request->input('order') ?? 0,
            ]);
            $action_route = Module::find($request->input('module_id'))->slug.'.'.$page->slug;
            if ($request->get('action_name_en')) {
                foreach ($request->input('action_name_en') as $index => $action_name_en) {
                    PageAction::create([
                        'page_id' => $page->id,
                        'name_en' => $action_name_en,
                        'name_kh' => $request->input('action_name_kh')[$index] ?? null,
                        'route_name' => $action_route.'.'.($request->input('action_route')[$index] ?? null),
                        'type' => $request->input('action_type')[$index] ?? null,
                        'position' => $request->input('action_position')[$index] ?? null,
                        'icon' => $request->input('action_icon')[$index] ?? 'fas fa-folder',
                        'active' => $request->input('action_active')[$index] ?? 'Y',
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
        $data['field_data'] = Page::findOrFail($field_data);
        $data['module'] = Module::where('active', 'Y')->get();
        $data['pageAction'] = PageAction::where('page_id', $field_data)->orderBy('position', 'desc')->get()->toArray();

        // dd($data);
        return view($this->load_page.'edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $field_data)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'module_id' => 'required|int',
            'name_en' => 'required|string|max:255',
            'name_kh' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'order' => 'nullable|numeric',
            'active' => 'required|string|in:Y,N',

            'action_name_en' => 'required|array',
            'action_name_kh' => 'required|array',
            'action_route' => 'required|array',
            'action_type' => 'required|array',
            'action_position' => 'nullable|array',
            'action_icon' => 'nullable|array',
            'action_active' => 'nullable|array',
            // action validation
            'action_name_en.*' => 'required|string|max:255',
            'action_name_kh.*' => 'required|string|max:255',
            'action_route.*' => 'required|string|max:255',
            'action_type.*' => 'required|string|max:255',
            'action_position.*' => 'nullable|string|max:255',
            'action_active.*' => 'nullable|string|in:Y,N',
            'action_icon.*' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            notify_error(trans('update_error'), $validator->errors());

            return redirect()->route($this->prefix_route.'edit', $field_data)->withErrors($validator);
        }
        $field_data = Page::findOrFail($field_data);
        if (! $field_data) {
            notify_error(trans('update_error'), trans('field_data_not_found'));

            return redirect()->route($this->prefix_route.'index');
        }
        $active = $request->input('active') ?? 'Y';

        DB::beginTransaction();
        try {
            $updateFieldData = [
                'module_id' => $request->input('module_id', $field_data->module_id),
                'name_en' => $request->input('name_en', $field_data->name_en),
                'name_kh' => $request->input('name_kh', $field_data->name_kh),
                'slug' => $field_data->slug == 'pages' ? $field_data->slug : slug($request->input('name_en', $field_data->name_en)),
                'icon' => $request->input('icon', $field_data->icon),
                'order' => $request->input('order', $field_data->order ?? 0),
                'active' => $field_data->slug == 'pages' ? 'Y' : $active,
            ];
            $field_data->update($updateFieldData);
            if ($request->get('action_name_en')) {
                PageAction::where('page_id', $field_data->id)->delete();
                foreach ($request->input('action_name_en') as $index => $action_name_en) {
                    PageAction::create([
                        'page_id' => $field_data->id,
                        'name_en' => $action_name_en,
                        'name_kh' => $request->input('action_name_kh')[$index] ?? null,
                        'route_name' => $request->input('action_route')[$index] ?? null,
                        'type' => $request->input('action_type')[$index] ?? null,
                        'position' => $request->input('action_position')[$index] ?? null,
                        'icon' => $request->input('action_icon')[$index] ?? 'fas fa-folder',
                        'active' => $request->input('action_active')[$index] ?? 'Y',
                    ]);
                }
            }

            DB::commit();
            notify_success(trans('update_success'), trans('update_success_message'));

            return redirect()->route($this->prefix_route.'index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify_error(trans('update_error'), ($e->getMessage() !== null ? $e->getMessage() : trans('update_error_message')));

            return redirect()->route($this->prefix_route.'index')->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($field_data)
    {
        $field_data = Page::where('id', $field_data);
        $field_data->delete();
        notify_success(trans('delete_success'), trans('delete_success_message'));

        return redirect()->route($this->prefix_route.'index');
    }

    public function restore($field_data)
    {
        $field_data = Page::where('id', $field_data);
        $field_data->restore();
        notify_success(trans('restore_success'), trans('restore_success_message'));

        return redirect()->route($this->prefix_route.'index');
    }
}
