<?php

namespace Modules\Settings\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Settings\App\DataTables\ConfigurationDataTable;
use Modules\Settings\App\Models\Configuration;

class ConfigurationController extends Controller
{
    private $module = 'settings';

    private $page_name = 'configuration.';

    private $table_name = 'configurations';

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
    public function index(ConfigurationDataTable $dataTable)
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
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|max:255|unique:configurations,key',
            'value' => 'required|string|max:255',
            'order' => 'nullable|numeric',
            'active' => 'required|string|in:Y,N',
            'datatype' => 'required|string|in:string,number',
        ]);
        if ($validator->fails()) {
            notify_error(trans('create_error'), $validator->errors());

            return redirect()->route($this->prefix_route.'create')->withErrors($validator);
        }
        $active = $request->input('active') ?? 'Y';

        DB::beginTransaction();
        try {
            $configuration = Configuration::create([
                'key' => $request->input('key'),
                'value' => $request->input('value'),
                'active' => $active,
                'order' => $request->input('order') ?? 0,
                'datatype' => $request->input('datatype'),
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
        $data['field_data'] = Configuration::find($field_data);

        return view($this->load_page.'edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $field_data)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|max:255|unique:configurations,key,'.$field_data,
            'value' => 'required|string|max:255',
            'order' => 'nullable|numeric',
            'active' => 'required|string|in:Y,N',
            'datatype' => 'required|string|in:string,number',
        ]);
        if ($validator->fails()) {
            notify_error(trans('update_error'), $validator->errors());

            return redirect()->route($this->prefix_route.'edit', $field_data)->withErrors($validator);
        }
        $field_data = Configuration::find($field_data);
        if (! $field_data) {
            notify_error(trans('update_error'), trans('field_data_not_found'));

            return redirect()->route($this->prefix_route.'index');
        }
        $active = $request->input('active') ?? 'Y';

        DB::beginTransaction();
        try {
            $field_data->update([
                'key' => $request->input('key') ?? $field_data->key,
                'value' => $request->input('value') ?? $field_data->value,
                'order' => $request->input('order') ?? $field_data->order,
                'active' => $active,
                'datatype' => $request->input('datatype') ?? $field_data->datatype,
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
        $field_data = Configuration::find($field_data);
        $field_data->delete();
        notify_success(trans('delete_success'), trans('delete_success_message'));

        return redirect()->route($this->prefix_route.'index');
    }

    public function restore($field_data)
    {
        $field_data = Configuration::where('id', $field_data);
        $field_data->restore();
        notify_success(trans('restore_success'), trans('restore_success_message'));

        return redirect()->route($this->prefix_route.'index');
    }

    public function sync()
    {
        generateConfig();
        activity('synced')
            ->causedBy(Auth::user())
            ->withProperties(['module' => $this->module, 'action' => 'sync'])
            ->log('Config Synced');

        notify_success(trans('sync_success'), trans('sync_success_message'));

        return redirect()->route($this->prefix_route.'index');
    }
}
