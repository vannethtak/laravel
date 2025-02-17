<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        $user = Auth::user();
        $user->last_activity = Carbon::now();
        if ($user instanceof \Illuminate\Database\Eloquent\Model) {
            $user->save();
        }
        if ($user instanceof \Illuminate\Database\Eloquent\Model) {
            activity('logined')
                ->causedBy($user)
                ->performedOn($user)
                ->withProperties([
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ])
                ->log('logined');
        }
        notify_success(__('Success'), __('Welcome back, :name!', ['name' => Auth::user()->name]));

        return redirect()->intended(route('dashboard.index', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        Cache::forget('sidebar_actions_'.Auth::id());
        Artisan::call('optimize:clear');
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        Cache::forget('sidebar_actions_'.Auth::id());
        Artisan::call('optimize:clear');
        // $request->session()->forget('user_account');
        $request->session()->forget('is_admin');

        return redirect()->route('login');
    }
}
