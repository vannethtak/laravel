<?php

namespace Modules\Dashboard\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard::index');
    }

    public function count()
    {
        $data = [];
        $driver = DB::getDriverName();
        if ($driver == 'pgsql') {
            $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema='public'");
        } elseif ($driver == 'mysql') {
            $tables = DB::select('SELECT table_name FROM information_schema.tables WHERE table_schema=DATABASE()');
        } else {
            $tables = [];
        }
        $data['tables'] = count($tables);
        $data['modules'] = DB::table('modules')->count();
        $data['pages'] = DB::table('pages')->count();
        $data['users'] = DB::table('users')->count();

        return response()->json($data);
    }
}
