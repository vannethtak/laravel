<?php

namespace Modules\Settings\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Settings\App\DataTables\TranslationDataTable;
use Modules\Settings\App\Models\Translation;

class TranslationController extends Controller
{
    private $module = 'settings';

    private $page_name = 'translation.';

    private $table_name = 'translations';

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
    // public function index()
    // {
    //     $data = $this->data;
    //     return view($this->load_page.'create', compact('data'));
    // }
    public function index(TranslationDataTable $dataTable)
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
        $data['locales'] = DB::table('locales')->whereNull('deleted_at')->pluck('locale', 'id');

        return view($this->load_page.'create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            notify_error(trans('create_error'), $validator->errors());

            return redirect()->route($this->prefix_route.'create')->withErrors($validator);
        }
        $locales = DB::table('locales')->pluck('locale', 'id');
        $key = $request->input('key');
        $active = $request->input('active') ?? 'Y';

        DB::beginTransaction();
        try {
            foreach ($locales as $localeId => $locale) {
                $data = [
                    'locale_id' => $localeId,
                    'key' => $key,
                    'value' => $request->input('value_'.$locale),
                    'active' => $active,
                ];
                Translation::create($data);
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
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('settings::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($field_data)
    {
        $data = $this->data;
        $field_data = Translation::where('key', $field_data)->get()->keyBy('locale_id')->toArray();
        $data['locales'] = DB::table('locales')->whereNull('deleted_at')->pluck('locale', 'id');
        $data['field_data'] = $field_data;

        return view($this->load_page.'.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $field_data)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            notify_error(trans('update_error'), $validator->errors());

            return redirect()->route($this->prefix_route.'edit', $field_data)->withErrors($validator);
        }
        $data = $this->data;
        $locales = DB::table('locales')->pluck('locale', 'id');
        $oldKey = $field_data;
        $newKey = $request->input('key');
        $active = $request->input('active') ?? 'Y';

        DB::beginTransaction();
        try {
            foreach ($locales as $localeId => $locale) {
                $translation = Translation::where('locale_id', $localeId)->where('key', $oldKey)->first();

                if ($translation) {
                    $translation->update([
                        'key' => $newKey,
                        'value' => $request->input('value_'.$locale),
                        'active' => $active,
                    ]);
                } else {
                    Translation::create([
                        'locale_id' => $localeId,
                        'key' => $newKey,
                        'value' => $request->input('value_'.$locale),
                        'active' => $active,
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
        $field_data = Translation::where('key', $field_data);
        if (! $field_data) {
            notify_error(trans('delete_error'), trans('delete_error_message'));

            return redirect()->route($this->prefix_route.'index');
        }
        $field_data->delete();
        notify_success(trans('delete_success'), trans('delete_success_message'));

        return redirect()->route($this->prefix_route.'index');
    }

    public function restore($field_data)
    {
        $field_data = Translation::where('key', $field_data);
        $field_data->restore();
        notify_success(trans('restore_success'), trans('restore_success_message'));

        return redirect()->route($this->prefix_route.'index');
    }

    public function sync()
    {
        $locales = DB::table('locales')->select('locale', 'deleted_at')->get()->groupBy(function ($item) {
            return $item->deleted_at ? 'deleted' : 'active';
        });

        generateLocales($locales->get('active', collect())->pluck('locale')->toArray());
        deleteLocales($locales->get('deleted', collect())->pluck('locale')->toArray());

        activity('synced')
            ->causedBy(Auth::user())
            ->withProperties(['module' => $this->module, 'action' => 'sync'])
            ->log('Locale Synced');

        notify_success(trans('sync_success'), trans('sync_success_message'));

        return redirect()->route($this->prefix_route.'index');
    }
}
