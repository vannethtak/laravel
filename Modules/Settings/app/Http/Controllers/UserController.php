<?php

namespace Modules\Settings\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use Modules\Settings\App\DataTables\UserDataTable;
use Modules\Settings\App\Models\Module;
use Modules\Settings\App\Models\Page;
use Modules\Settings\App\Models\PageAction;
use Modules\Settings\App\Models\Role;
use Modules\Settings\App\Models\RolePermission;
use Modules\Settings\App\Models\User;
use Modules\Settings\App\Models\UserPermission;

class UserController extends Controller
{
    private $module = 'settings';

    private $page_name = 'user.';

    private $table_name = 'users';

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
    public function index(UserDataTable $dataTable)
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
        $data['roles'] = Role::select('id', 'name_'.app()->getLocale().' as name')
            ->where('active', 'Y')
            ->where('slug', '!=', 'owner')
            ->orderBy('order', 'asc')
            ->get()->toArray();
        $data['modules'] = Module::select('id', 'name_'.app()->getLocale().' as name', 'icon')->where('active', 'Y')->orderBy('order', 'asc')->get()->toArray();
        $data['page'] = Page::select('id', 'name_'.app()->getLocale().' as name', 'module_id', 'icon')->where('active', 'Y')->orderBy('order', 'asc')->get()->toArray();
        $data['pageAction'] = PageAction::select('id', 'name_'.app()->getLocale().' as name', 'page_id', 'icon')->where('active', 'Y')->orderBy('order', 'asc')->get()->toArray();

        return view($this->load_page.'create', compact('data'));
    }

    public function getRolePermissions(Request $request)
    {
        $roleId = $request->role_id;
        $permissions = RolePermission::where('role_id', $roleId)->pluck('action_id')->toArray();

        return response()->json([
            'success' => true,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|max:255|unique:users,email',
            'phone' => 'required|string|min:7|max:255|unique:users,phone',
            'password' => 'required|string|min:6|max:255|confirmed',
            'role_id' => 'required|integer|exists:roles,id',
            'order' => 'nullable|numeric',
            'active' => 'required|string|in:Y,N',
            'action_id' => 'nullable|array',
            'action_id.*' => 'nullable|distinct',
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

        DB::beginTransaction();
        try {
            $attachment = $request->file('attachment');
            $storePath = 'user';
            $fileName = null;
            $fileExtension = 'jpg';
            if ($attachment) {
                $savedAttachment = resizeAndUploadImage($attachment, $storePath, $fileName, $fileExtension, resizeAvatar());
            }
            $user = User::create([
                'name' => $request->input('name'),
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'password' => bcrypt($request->input('password')),
                'role_id' => $request->input('role_id'),
                'order' => $request->input('order') ?? 0,
                'active' => $active,
                'avatar' => $savedAttachment['pathFile'] ?? config('setting.image_default_value'),
            ]);
            $action_id = $request->input('action_id');
            if ($action_id) {
                foreach ($action_id as $action) {
                    UserPermission::create([
                        'user_id' => $user->id,
                        'role_id' => $user->role_id,
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
        $data['field_data'] = User::find($field_data);
        $data['roles'] = Role::select('id', 'name_'.app()->getLocale().' as name')
            ->where('active', 'Y')
            ->where('slug', '!=', 'owner')
            ->orderBy('order', 'asc')
            ->get()->toArray();
        $data['modules'] = Module::select('id', 'name_'.app()->getLocale().' as name', 'icon')->where('active', 'Y')->orderBy('order', 'asc')->get()->toArray();
        $data['page'] = Page::select('id', 'name_'.app()->getLocale().' as name', 'module_id', 'icon')->where('active', 'Y')->orderBy('order', 'asc')->get()->toArray();
        $data['pageAction'] = PageAction::select('id', 'name_'.app()->getLocale().' as name', 'page_id', 'icon')->where('active', 'Y')->orderBy('order', 'asc')->get()->toArray();
        $data['userPermission'] = UserPermission::where('user_id', $field_data)
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
            'name' => 'required|string|max:255',
            // 'username' => 'required|string|max:255|unique:users,username,' . $field_data,
            // 'email' => 'required|string|max:255|unique:users,email,' . $field_data,
            'phone' => 'required|string|min:7|max:255|unique:users,phone,'.$field_data,
            // 'password' => 'required|string|min:6|max:255|confirmed',
            'role_id' => 'required|integer|exists:roles,id',
            'order' => 'nullable|numeric',
            'active' => 'required|string|in:Y,N',
            'action_id' => 'nullable|array',
            'action_id.*' => 'nullable|distinct',
            'attachment' => [
                File::types(['png', 'jpg', 'jpeg'])
                    ->max(config('setting.upload_max_size')),
            ],
        ]);
        if ($validator->fails()) {
            notify_error(trans('update_error'), $validator->errors());

            return redirect()->route($this->prefix_route.'edit', $field_data)->withErrors($validator);
        }
        $field_data = User::find($field_data);
        if (! $field_data) {
            notify_error(trans('update_error'), trans('field_data_not_found'));

            return redirect()->route($this->prefix_route.'index');
        }
        $active = $request->input('active') ?? 'Y';

        DB::beginTransaction();
        try {
            if ($request->get('attachment_') == 'deleted') {
                deleteFile(config('setting.disk_name'), $field_data->avatar);
            }

            $attachment = $request->file('attachment');

            if ($attachment) {
                deleteFile(config('setting.disk_name'), $field_data->avatar);
                $storePath = 'user';
                $fileName = null;
                $fileExtension = 'jpg';
                $savedAttachment = resizeAndUploadImage($attachment, $storePath, $fileName, $fileExtension, resizeAvatar());
            }
            $field_data->update([
                'name' => $request->input('name') ?? $field_data->name,
                'role_id' => $request->input('role_id') ?? $field_data->role_id,
                'phone' => $request->input('phone') ?? $field_data->phone,
                'order' => $request->input('order') ?? $field_data->order,
                'active' => $active,
                'avatar' => $savedAttachment['pathFile'] ?? $field_data->avatar,
            ]);
            $action_id = $request->input('action_id');
            UserPermission::where('user_id', $field_data->id)->delete();
            if ($action_id) {
                foreach ($action_id as $action) {
                    UserPermission::create([
                        'user_id' => $field_data->id,
                        'role_id' => $field_data->role_id,
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
        $field_data = User::find($field_data);
        $field_data->delete();
        notify_success(trans('delete_success'), trans('delete_success_message'));

        return redirect()->route($this->prefix_route.'index');
    }

    public function restore($field_data)
    {
        $field_data = User::where('id', $field_data);
        $field_data->restore();
        notify_success(trans('restore_success'), trans('restore_success_message'));

        return redirect()->route($this->prefix_route.'index');
    }

    public function changePassword($field_data)
    {
        $data = $this->data;
        $data['field_data'] = User::find($field_data);

        return view($this->load_page.'change-password', compact('data'));
    }

    public function updatePassword(Request $request, $field_data)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|max:255|confirmed',
        ]);
        if ($validator->fails()) {
            notify_error(trans('change_password_error'), $validator->errors());

            return redirect()->route($this->prefix_route.'changePassword', $field_data)->withErrors($validator);
        }
        $field_data = User::find($field_data);
        $field_data->update([
            'password' => bcrypt($request->input('password')),
        ]);
        notify_success(trans('change_password_success'), trans('change_password_success_message'));

        return redirect()->route($this->prefix_route.'index');
    }

    public function profile(Request $request)
    {
        $user = Auth::user();
        $data = $this->data;

        return view($this->load_page.'profile', compact('data', 'user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.Auth::id(),
            'email' => 'required|email|max:255|unique:users,email,'.Auth::id(),
            'phone' => 'nullable|string|max:15',
            'password' => 'required|string',
            'attachment' => [
                File::types(['png', 'jpg', 'jpeg'])
                    ->max(config('setting.upload_max_size')),
            ],
        ]);

        if (! Hash::check($request->password, Auth::user()->password)) {
            return response()->json(['status' => 'error', 'message' => 'Incorrect password'], 403);
        }
        $attachment = $request->file('attachment');
        if ($attachment) {
            deleteFile(config('setting.disk_name'), Auth::user()->avatar);
            $storePath = 'user';
            $fileName = null;
            $fileExtension = 'jpg';
            $savedAttachment = resizeAndUploadImage($attachment, $storePath, $fileName, $fileExtension, resizeAvatar());
        }
        $user = Auth::user();
        $field_data = User::find($user->id);
        $field_data->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'avatar' => $savedAttachment['pathFile'] ?? $user->avatar,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Profile updated successfully']);
    }

    public function resetPassword(Request $request)
    {
        if ($request->isMethod('get')) {
            $data = $this->data;
            $data['field_data'] = Auth::user();

            return view($this->load_page.'user-change-password', compact('data'));
        }

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string|min:6|max:255',
                'password' => 'required|string|min:6|max:255|confirmed',
            ]);

            if ($validator->fails()) {
                notify_error(trans('change_password_error'), $validator->errors());

                return redirect()->route($this->prefix_route.'resetPassword')->withErrors($validator);
            }

            if (! Hash::check($request->current_password, Auth::user()->password)) {
                notify_error(trans('change_password_error'), trans('password_mismatch'));

                return redirect()->route($this->prefix_route.'resetPassword')->withErrors(['current_password' => trans('password_mismatch')]);
            }

            $user = Auth::user();
            $field_data = User::find($user->id);
            $field_data->update([
                'password' => bcrypt($request->input('password')),
            ]);

            notify_success(trans('change_password_success'), trans('change_password_success_message'));

            return redirect()->route($this->prefix_route.'profile');
        }
    }
}
