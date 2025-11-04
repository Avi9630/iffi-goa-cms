<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DatabaseSwitchController extends Controller
{   
    public function switchDatabase(Request $request)
    {
        $database = $request->input('db');

        if (empty($database)) {
            return redirect()->back()->with('danger', 'No database selected!');
        }

        // Update connection dynamically
        Config::set('database.connections.dynamic', [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => $database,
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
        ]);

        try {
            DB::purge('dynamic');
            DB::reconnect('dynamic');
            DB::connection('dynamic')->select('SHOW TABLES');
            session(['selected_db' => $database]);
            return redirect()
                ->back()
                ->with('success', "Database switched to {$database}");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('danger', 'Failed to connect: ' . $e->getMessage());
        }
    }
}
