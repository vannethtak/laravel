<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Modules\Settings\App\Models\Module;
use Modules\Settings\App\Models\Page;
use Modules\Settings\App\Models\PageAction;
use Modules\Settings\App\Models\RolePermission;
use Modules\Settings\App\Models\UserPermission;

class GlobalHelper
{
    private $active_page_actions;

    private $breadcrumbs;

    private $restore;

    public function hasPageAccess()
    {
        if (session('is_owner') || Route::currentRouteName() == 'dashboard.index'
            || Route::currentRouteName() == 'settings.user.profile'
            || Route::currentRouteName() == 'settings.user.resetPassword'
            || Request::ajax()) {
            return true;
        }

        $action = PageAction::where('route_name', Route::currentRouteName())->first();
        $action_id = $action->id ?? null;

        if (! $action_id) {
            return false;
        }

        $rolePermission = RolePermission::where('role_id', Auth::user()->role_id)
            ->where('action_id', $action_id)
            ->exists();

        $userPermission = UserPermission::where('user_id', Auth::id())
            ->where('action_id', $action_id)
            ->exists();

        return $rolePermission || $userPermission;
    }

    public function sideBars()
    {
        $availableModules = [];
        $availableSubModules = [];
        $availablePages = [];

        // $modModule = new Module();
        // $modPage = new Page();
        // $modPageAction = new PageAction();

        // $availableActions = Auth::user()->role->rolePermissions()->pluck('action_id');
        $availableActions = [];
        if (! session('is_owner')) {
            $user = Auth::user();
            $roleActions = $user->role->rolePermissions()->pluck('action_id');
            $userActions = $user->permissions->pluck('action_id');
            $availableActions = $roleActions->merge($userActions)->unique()->values();
        }
        $modules = [];
        $pages = [];
        $actions = [];

        $pageActionsArr = Cache::remember('sidebar_actions_'.Auth::id(), now()->addDay(), function () use ($availableActions) {
            if (session('is_owner')) {
                $pageActionsObj = DB::table('page_actions')->orderBy('order')->where('active', 'Y')->whereNull('deleted_at')->get()->toArray();
            } else {
                $pageActionsObj = DB::table('page_actions')->whereIn('id', $availableActions)->where('active', 'Y')->orderBy('order')->get()->toArray();
            }
            $pageActionsArr = json_decode(json_encode($pageActionsObj), true);

            $dashboardAction = DB::table('page_actions')->where('route_name', 'dashboard.index')->where('active', 'Y')->whereNull('deleted_at')->first();
            if ($dashboardAction) {
                $pageActionsArr[] = (array) $dashboardAction;
            }

            return $pageActionsArr;
        });
        // dd($pageActionsArr);
        if (session('is_owner')) {
            foreach ($pageActionsArr as $action) {
                $actions[$action['page_id']][$action['type']] = $action;
                $availablePages[] = $action['page_id'];
            }
        } else {
            foreach ($pageActionsArr as $action) {
                $actions[$action['page_id']][$action['type']] = $action;
                $availablePages[] = $action['page_id'];
            }
        }
        $availablePages = array_unique($availablePages);
        $pageArr = DB::table('pages')->whereIn('id', $availablePages)->where('active', 'Y')->orderBy('order')->whereNull('deleted_at')->get()->toArray();
        foreach ($pageArr as $page) {
            if ($page->module_id !== null) {
                $pages['module'][$page->module_id][] = $page;
                if (! in_array($page->module_id, $availableModules)) {
                    $availableModules[] = $page->module_id;
                }
            } else {
                $pages['root'][] = $page;
            }
        }

        $availableModules = array_unique($availableModules);
        $availableSubModules = array_unique($availableSubModules);

        $moduleObj = DB::table('modules')->whereIn('id', $availableModules)->where('active', 'Y')->orderBy('order')->whereNull('deleted_at')->get();
        $moduleArr = json_decode(json_encode($moduleObj), true);
        foreach ($moduleArr as $module) {
            $modules[$module['id']] = $module;
        }

        $sideBars = $this->buildSideBars($modules, $pages, $actions);
        $data['sideBars'] = $sideBars;
        $data['active_page_actions'] = $this->active_page_actions;
        $data['breadcrumbs'] = $this->breadcrumbs;
        $data['restore'] = $this->restore;

        return $data;
    }

    public function buildSideBars($modules, $pages, $actions)
    {
        $html = '<div class="sidebar-wrapper scrollbar scrollbar-inner">';
        $html .= '<div class="sidebar-content">';
        $html .= '<ul class="nav nav-secondary">';

        $locale = '_'.app()->getLocale();

        foreach ($modules as $module) {
            $modulePages = $pages['module'][$module['id']] ?? null;

            if ($modulePages && count($modulePages) === 1) {
                $page = $modulePages[0];
                if (isset($actions[$page->id])) {
                    $act = $actions[$page->id][$page->default_action];
                    $route_name = $act['route_name'];
                    $active_route = request()->routeIs($route_name) ? ' active' : '';
                    if ($active_route === ' active') {
                        $this->activePageActions($page, $actions);
                    }

                    $html .= '<li class="nav-item'.$active_route.'">';
                    $html .= '<a href="'.(Route::has($route_name) ? route($route_name) : '#').'" class="nav-link'.$active_route.'">';
                    $html .= '<i class="nav-icon '.$module['icon'].'"></i>';
                    $pageName = $page->{'name'.$locale} ?? $page->name_en;
                    $html .= '<p>'.$pageName.'</p>';
                    $html .= '</a>';
                    $html .= '</li>';
                }
            } else {
                $module_open = request()->routeIs($module['slug'].'.*') ? ' nav-item-open menu-is-opening menu-open' : '';
                $module_show = request()->routeIs($module['slug'].'.*') ? ' show' : '';
                $html .= '<li class="nav-item">';
                $html .= '<a data-bs-toggle="collapse" href="#'.$module['slug'].'">';
                $html .= '<i class="nav-icon '.$module['icon'].'"></i>';
                $html .= '<p>'.($module["name{$locale}"] ?? $module['name_en']).'</p>';
                $html .= '<span class="caret"></span>';
                $html .= '</a>';
                $html .= '<div class="collapse'.$module_show.'" id="'.$module['slug'].'">';
                $html .= '<ul class="nav nav-collapse">';

                if ($modulePages) {
                    foreach ($modulePages as $page) {
                        if (isset($actions[$page->id])) {
                            $act = $actions[$page->id][$page->default_action];
                            $route_name = $act['route_name'];
                            $route_segments = explode('.', $route_name);
                            $segment0 = $route_segments[0];
                            $segment1 = $route_segments[1];
                            $active_route = request()->routeIs("{$segment0}.{$segment1}.*") ? ' active' : '';

                            if ($active_route === ' active') {
                                $this->activePageActions($page, $actions);
                            }

                            $html .= '<li class="nav-item'.$active_route.'">';
                            $html .= '<a href="'.(Route::has($route_name) ? route($route_name) : '#').'" class="nav-link'.$active_route.'">';
                            $html .= '<span class="sub-item">'.($page->{'name'.$locale} ?? $page->{'name_en'}).'</span>';
                            // $html .= '<i class="nav-icon ' . $page->icon . ' " style="font-size: 0.9em;"></i>';
                            // $html .= '<p>' . $page->{'name' . $locale} . '</p>';

                            $html .= '</a>';
                            $html .= '</li>';
                        }
                    }
                }

                $html .= '</ul>';
                $html .= '</div>';
                $html .= '</li>';
            }
        }

        $html .= '</ul>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    public function currentAction($routeName)
    {
        return PageAction::where('route_name', $routeName)->first();
    }

    public function activePageActions($page, $actions)
    {
        $currentAction = $this->currentAction(Route::currentRouteName());
        $this->breadcrumbs['current_action'] = $currentAction;
        foreach ($actions[$page->id] as $type => $act) {
            if ($act['type'] == $page->default_action) {
                $this->breadcrumbs['page_action'] = $act;
                $this->breadcrumbs['page_slug'] = $page->slug;
            }

            // if (isset($currentAction) && $currentAction['type'] !== $act['parent']) {
            //     continue;
            // }

            if ($type == 'restore') {
                $this->restore = [
                    'action_id' => $act['id'],
                    'action_name_en' => $act['name_en'],
                    'action_name_kh' => $act['name_kh'],
                    'action_route' => $act['route_name'],
                    'action_icon' => $act['icon'],
                    'action_type' => $act['type'],
                    'page_slug' => $page->slug,
                ];

                continue;
            }
            $this->active_page_actions[$act['position']][] = [
                'action_id' => $act['id'],
                'action_name_en' => $act['name_en'],
                'action_name_kh' => $act['name_kh'],
                'action_route' => $act['route_name'],
                'action_icon' => $act['icon'],
                'action_type' => $act['type'],
                'page_slug' => $page->slug,
            ];
        }
    }
}
