<?php

namespace App\Http\Middleware;

use App\Helpers\GlobalHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class Sidebar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->method() == 'POST') {
            return $next($request);
        }
        $helper = new GlobalHelper;
        /*
         * Check User Account Cannot access page
         */
        if (! $helper->hasPageAccess()) {
            notify_warning(__('Error'), __('no_role_access'));

            return redirect()->back();
        }

        $sideBars = $helper->sideBars(Auth::user()->role_id);
        $data = [
            'sideBars' => $sideBars,
            'pageActions' => isset($sideBars['active_page_actions']) ? $sideBars['active_page_actions'] : null,
            'breadcrumbs' => isset($sideBars['breadcrumbs']) ? $sideBars['breadcrumbs'] : null,
            'restore' => isset($sideBars['restore']) ? $sideBars['restore'] : null,
            'locale' => '_'.App()->getLocale(),
        ];
        // dd($data, session('is_owner'));
        View::share($data);

        return $next($request);
    }
}
