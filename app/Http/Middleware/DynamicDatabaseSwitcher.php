<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DynamicDatabaseSwitcher
{
    public function handle($request, Closure $next)
    {
        // Check if user has selected a DB previously
        if (session()->has('selected_db')) {
            $database = session('selected_db');

            // Dynamically apply it
            Config::set('database.connections.mysql.database', $database);

            DB::purge('mysql');
            DB::reconnect('mysql');
        }

        return $next($request);
    }
}
