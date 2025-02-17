<?php

namespace Modules\Settings\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use Modules\Settings\App\DataTables\LocaleDataTable;
use Modules\Settings\App\Models\Locale;

class LocaleController extends Controller
{
    private $module = 'settings';

    private $page_name = 'locale.';

    private $table_name = 'locales';

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

    public function setLocale($lang)
    {
        $locale = $lang;
        if (! in_array($locale, ['en', 'kh'])) {
            abort(400);
        }
        session()->put('locale', $locale);
        $user = Auth::user();
        if ($user instanceof \App\Models\User) {
            $user->locale = $locale;
            $user->save();
        }
        notify_success(__('Success'), __('Language has been changed successfully.'));

        return redirect()->back();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(LocaleDataTable $dataTable)
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
            'locale' => 'nullable|string|max:255',
            'translations' => 'nullable|string|max:255',
            'attachment' => [
                File::types(['png', 'jpg', 'jpeg'])
                    ->max(config('setting.upload_max_size')),
            ],
        ]);
        if ($validator->fails()) {
            notify_error(trans('create_error'), $validator->errors());

            return redirect()->route($this->prefix_route.'create')->withErrors($validator);
        }
        $active = $request->input('active') ?? 'Y';
        $locale = $request->input('locale');
        DB::beginTransaction();
        try {
            $attachment = $request->file('attachment');
            $storePath = 'lang';
            $fileName = $locale;
            $fileExtension = 'jpg';
            $savedAttachment = resizeAndUploadImage($attachment, $storePath, $fileName, $fileExtension, resizeLogo());
            Locale::create([
                'locale' => $request->input('locale'),
                'translations' => $request->input('translations'),
                'active' => $active,
                'logo' => $savedAttachment['pathFile'] ?? config('setting.image_default_value'),
            ]);
            DB::commit();
            notify_success(trans('create_success'), trans('create_success_message'));

            return redirect()->route($this->prefix_route.($request->input('submit') == 'save' ? 'index' : 'create'));
        } catch (\Exception $e) {
            if (isset($tempPath) && file_exists($tempPath)) {
                unlink($tempPath);
            }
            DB::rollBack();
            notify_error(trans('create_error'), trans('create_error_message'));

            return redirect()->route($this->prefix_route.'index')->withErrors($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Locale $field_data)
    {
        $data = $this->data;
        $data['field_data'] = $field_data;

        return view($this->load_page.'.edit', compact('data'));
    }

    public function update(Request $request, Locale $field_data)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'locale' => 'nullable|string|max:255',
            'translations' => 'nullable|string|max:255',
            'attachment' => [
                File::types(['png', 'jpg', 'jpeg'])
                    ->max(config('setting.upload_max_size')),
            ],
        ]);
        if ($validator->fails()) {
            notify_error(trans('update_error'), $validator->errors());

            return redirect()->route($this->prefix_route.'edit', $field_data)->withErrors($validator);
        }
        $active = $request->input('active') ?? 'Y';
        $locale = $request->input('locale');
        if ($active == 'N') {
            if ($locale == app()->getLocale()) {
                $user = Auth::user();
                if ($user instanceof \App\Models\User) {
                    $user->update(['locale' => 'en']);
                }
            }
            if ($locale == 'en') {
                notify_error(trans('update_error'), trans('update_error_message'));

                return redirect()->route($this->prefix_route.'index')->withErrors('Cannot deactivate English language');
            }
        }

        if ($request->get('attachment_') == 'deleted') {
            deleteFile(config('setting.disk_name'), $field_data->logo);
        }

        $attachment = $request->file('attachment');

        if ($attachment) {
            deleteFile(config('setting.disk_name'), $field_data->logo);
            $storePath = 'lang';
            $fileName = $locale;
            $fileExtension = 'jpg';
            $savedAttachment = resizeAndUploadImage($attachment, $storePath, $fileName, $fileExtension, resizeLogo());
        }
        DB::beginTransaction();
        try {
            $field_data->update([
                'locale' => $request->input('locale'),
                'translations' => $request->input('translations'),
                'active' => $active,
                'logo' => $savedAttachment['pathFile'] ?? $field_data->logo,
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
    public function destroy(Locale $field_data)
    {
        if (! $field_data || in_array($field_data->locale, ['en', 'kh'])) {
            notify_error(trans('delete_error'), trans('delete_error_message'));

            return redirect()->route($this->prefix_route.'index');
        }
        $field_data->delete();
        notify_success(trans('delete_success'), trans('delete_success_message'));

        return redirect()->route($this->prefix_route.'index');
    }

    public function restore($field_data)
    {
        $field_data = Locale::where('id', $field_data);
        $field_data->restore();
        notify_success(trans('restore_success'), trans('restore_success_message'));

        return redirect()->route($this->prefix_route.'index');
    }
}
